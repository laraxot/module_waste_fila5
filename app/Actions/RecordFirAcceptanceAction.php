<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Carbon\CarbonImmutable;
use Modules\Waste\Enums\AcceptanceEnum;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Exceptions\InvalidFirException;
use Modules\Waste\Exceptions\InvalidFirStatusTransitionException;
use Modules\Waste\Exceptions\InvalidQuantityException;
use Modules\Waste\Models\Fir;
use Spatie\QueueableAction\QueueableAction;

/**
 * Registra l'esito dell'accettazione a destino (quarta copia / accettazione xFIR).
 *
 * - full / partial: quantità accettata (kg, peso verificato a destino) > 0;
 * - rejected: quantità accettata = 0;
 * - partial e rejected richiedono la motivazione.
 *
 * Un FIR ancora `issued` o `in_transit` passa per `delivered` (l'arrivo coincide con la registrazione
 * dell'accettazione), poi allo stato di esito; ogni passaggio rispetta FirStatusEnum::canTransitionTo().
 */
class RecordFirAcceptanceAction
{
    use QueueableAction;

    public function execute(Fir $fir, AcceptanceEnum $acceptance, float $acceptedKg, \DateTimeInterface $arrivedAt, ?string $reason = null): Fir
    {
        if (! is_finite($acceptedKg) || $acceptedKg < 0.0) {
            throw InvalidQuantityException::negative($acceptedKg);
        }
        if ($acceptance === AcceptanceEnum::REJECTED && $acceptedKg > 0.0) {
            throw InvalidQuantityException::rejectedMustBeZero($acceptedKg);
        }
        if ($acceptance !== AcceptanceEnum::REJECTED && $acceptedKg <= 0.0) {
            throw InvalidQuantityException::notPositive($acceptedKg);
        }

        $reason = $reason !== null ? trim($reason) : null;
        if ($acceptance->requiresReason() && ($reason === null || $reason === '')) {
            throw InvalidFirException::reasonRequired($acceptance->value);
        }

        $status = $fir->status;
        foreach ([FirStatusEnum::IN_TRANSIT, FirStatusEnum::DELIVERED] as $step) {
            if ($status !== $step && $status->canTransitionTo($step)) {
                $status = $step;
            }
        }

        $target = $acceptance->toFirStatus();
        if (! $status->canTransitionTo($target)) {
            throw InvalidFirStatusTransitionException::make($fir->status, $target);
        }

        $fir->fill([
            'status' => $target,
            'acceptance' => $acceptance,
            'accepted_quantity_kg' => round($acceptedKg, 3),
            'rejection_reason' => $acceptance->requiresReason() ? $reason : null,
            'arrived_at' => CarbonImmutable::instance($arrivedAt),
        ]);
        $fir->save();

        return $fir;
    }
}
