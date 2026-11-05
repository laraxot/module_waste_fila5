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

    public static function invalidInitialStatus(FirStatusEnum $status): self
    {
        return new self(self::message('invalid_fir_status_transition.invalid_initial_status', ['status' => $status->value]));
    }
}
