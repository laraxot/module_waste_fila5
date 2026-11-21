<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Carbon\CarbonImmutable;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Exceptions\InvalidFirStatusTransitionException;
use Modules\Waste\Models\Fir;
use Spatie\QueueableAction\QueueableAction;

/**
 * Cambia lo stato di un formulario secondo FirStatusEnum::canTransitionTo(). Gli esiti di accettazione
 * (accettato, parziale, respinto) richiedono la quantità accettata: si registrano con RecordFirAcceptanceAction.
 * Alla partenza valorizza `transport_started_at`, alla consegna `arrived_at`, se non già indicati.
 *
 * Un FIR cartaceo non si emette e non parte senza numero e trasportatore: il numero è quello del modulo
 * vidimato, il trasportatore firma la presa in carico, e dopo la bozza il formulario non si modifica più.
 * Il FIR digitale riceve il numero da RENTRI, che lo emette (ApplyRentriTransmissionOutcomeAction).
 */
class ChangeFirStatusAction
{
    use QueueableAction;

    public function execute(Fir $fir, FirStatusEnum $status): Fir
    {
        if ($status->isAcceptanceOutcome()) {
            throw InvalidFirStatusTransitionException::acceptanceRequiresData($status);
        }

        if (! $fir->status->canTransitionTo($status)) {
            throw InvalidFirStatusTransitionException::make($fir->status, $status);
        }

        if (\in_array($status, [FirStatusEnum::ISSUED, FirStatusEnum::IN_TRANSIT], true) && $fir->status === FirStatusEnum::DRAFT && ! $fir->is_digital) {
            $this->assertReadyForTransport($fir);
        }

        $now = CarbonImmutable::now();
        $fir->status = $status;
        if ($status === FirStatusEnum::IN_TRANSIT && $fir->transport_started_at === null) {
            $fir->transport_started_at = $now;
        }
        if ($status === FirStatusEnum::DELIVERED && $fir->arrived_at === null) {
            $fir->arrived_at = $now;
        }
        $fir->save();

        return $fir;
    }

    private function assertReadyForTransport(Fir $fir): void
    {
        $missing = [];
        if (! \is_string($fir->number) || trim($fir->number) === '') {
            $missing[] = 'number';
        }
        if ($fir->transporter_operator_id === null) {
            $missing[] = 'transporter_operator_id';
        }
        if ($missing !== []) {
            throw InvalidFirStatusTransitionException::notReadyForTransport($missing);
        }
    }
}
