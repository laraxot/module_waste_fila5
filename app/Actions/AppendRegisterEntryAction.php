<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Waste\Datas\RegisterEntryData;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Exceptions\InvalidRegisterEntryException;
use Modules\Waste\Exceptions\RegisterEntryAlreadyRectifiedException;
use Modules\Waste\Models\Register;
use Modules\Waste\Models\RegisterEntry;
use Spatie\QueueableAction\QueueableAction;

/**
 * Unico scrittore del registro: valida, assegna il numero progressivo per registro e anno e salva.
 * Usata da PostRegisterEntryAction (nuova scrittura) e RectifyRegisterEntryAction (rettifica);
 * gli altri moduli chiamano quelle due.
 *
 * Numerazione: dentro una transazione si blocca la riga del registro (`lockForUpdate`), che serializza tutte
 * le scritture dello stesso registro anche quando l'anno non ha ancora scritture; il numero è
 * `max(number) + 1` per (registro, anno di registrazione). Il vincolo unique (register_id, year, number) è
 * l'ultima difesa: in caso di collisione (o di deadlock / "database is locked") si riprova fino a
 * `waste.register.transaction_attempts` volte. L'anno è quello di `recorded_at` (data di annotazione),
 * non di `operation_at`: a inizio anno una movimentazione di dicembre annotata a gennaio prende il numero
 * del nuovo anno.
 */
class AppendRegisterEntryAction
{
    use QueueableAction;

    public function __construct(
        private readonly ValidateRegisterEntryDataAction $validate,
    ) {}

    public function execute(RegisterEntryData $data, ?RegisterEntry $rectifies = null, ?string $rectificationReason = null): RegisterEntry
    {
        $attempts = max(1, Config::integer('waste.register.transaction_attempts', 5));
        $connection = $this->connection();

        for ($attempt = 1; ; $attempt++) {
            try {
                return $connection->transaction(
                    fn (): RegisterEntry => $this->append($data, $rectifies, $rectificationReason),
                    $attempts,
                );
            } catch (UniqueConstraintViolationException $exception) {
                if ($attempt >= $attempts) {
                    throw $exception;
                }
            }
        }
    }

    private function append(RegisterEntryData $data, ?RegisterEntry $rectifies, ?string $rectificationReason): RegisterEntry
    {
        $register = Register::query()->whereKey($data->registerId)->lockForUpdate()->first();
        if (! $register instanceof Register) {
            throw InvalidRegisterEntryException::registerNotFound($data->registerId);
        }
        if (! $register->is_active) {
            throw InvalidRegisterEntryException::registerInactive($register->name);
        }

        $this->validate->execute($data);

        if ($rectifies !== null) {
            $this->ensureRectifiable($data, $rectifies, $rectificationReason);
        }

        $recordedAt = CarbonImmutable::now();
        $year = $recordedAt->year;

        $last = RegisterEntry::query()
            ->where('register_id', $register->id)
            ->where('year', $year)
            ->lockForUpdate()
            ->max('number');
        $number = (is_numeric($last) ? (int) $last : 0) + 1;

        $entry = new RegisterEntry;
        $entry->fill([
            'register_id' => $register->id,
            'year' => $year,
            'number' => $number,
            'type' => $data->type,
            'cause' => $data->cause,
            'recorded_at' => $recordedAt,
            'operation_at' => $data->operationAt,
            'ewc_code_id' => $data->ewcCodeId,
            'physical_state' => $data->physicalState,
            'hazard_properties' => HazardPropertyEnum::normalizeList($data->hazardProperties),
            'quantity_kg' => round($data->quantityKg, 3),
            'operation' => $data->operation,
            'fir_id' => $data->firId,
            'counterpart_operator_id' => $data->counterpartOperatorId,
            'reference_type' => $data->reference?->getMorphClass(),
            'reference_id' => $data->reference?->getKey(),
            'load_entry_ids' => $data->normalizedLoadEntryIds(),
            'rectifies_entry_id' => $rectifies?->id,
            'rectification_reason' => $rectificationReason,
            'notes' => $data->notes,
        ]);
        $entry->save();

        return $entry;
    }

    private function ensureRectifiable(RegisterEntryData $data, RegisterEntry $rectifies, ?string $reason): void
    {
        if ($reason === null || trim($reason) === '') {
            throw InvalidRegisterEntryException::rectificationReasonMissing();
        }

        if ($rectifies->register_id !== $data->registerId) {
            throw InvalidRegisterEntryException::rectificationRegisterMismatch();
        }

        if ($rectifies->type !== $data->type) {
            throw InvalidRegisterEntryException::rectificationTypeMismatch();
        }

        $alreadyRectified = RegisterEntry::query()
            ->where('rectifies_entry_id', $rectifies->id)
            ->lockForUpdate()
            ->exists();
        if ($alreadyRectified) {
            throw RegisterEntryAlreadyRectifiedException::make($rectifies->year, $rectifies->number);
        }
    }

    private function connection(): ConnectionInterface
    {
        return DB::connection((new RegisterEntry)->getConnectionName());
    }
}
