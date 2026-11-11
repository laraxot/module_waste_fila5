<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

use Modules\Waste\Enums\FirStatusEnum;

final class InvalidFirStatusTransitionException extends WasteDomainException
{
    public static function make(FirStatusEnum $from, FirStatusEnum $to): self
    {
        return new self(self::message('invalid_fir_status_transition.not_allowed', ['from' => $from->value, 'to' => $to->value]));
    }

    public static function acceptanceRequiresData(FirStatusEnum $to): self
    {
        return new self(self::message('invalid_fir_status_transition.acceptance_requires_data', ['to' => $to->value]));
    }

    /**
     * @param  list<string>  $missing  campi mancanti (`number`, `transporter_operator_id`)
     */
    public static function notReadyForTransport(array $missing): self
    {
        $labels = array_map(static fn (string $field): string => self::message('invalid_fir_status_transition.fields.'.$field), $missing);

        return new self(self::message('invalid_fir_status_transition.not_ready_for_transport', ['fields' => implode(', ', $labels)]));
    }

    public static function invalidInitialStatus(FirStatusEnum $status): self
    {
        return new self(self::message('invalid_fir_status_transition.invalid_initial_status', ['status' => $status->value]));
    }
}
