<?php

declare(strict_types=1);

namespace Modules\Waste\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Esito dell'accettazione del rifiuto a destino. Il codice RENTRI (A/P/R) è mappato dal modulo Rentri.
 */
enum AcceptanceEnum: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case FULL = 'full';
    case PARTIAL = 'partial';
    case REJECTED = 'rejected';

    public function toFirStatus(): FirStatusEnum
    {
        return match ($this) {
            self::FULL => FirStatusEnum::ACCEPTED,
            self::PARTIAL => FirStatusEnum::PARTIALLY_ACCEPTED,
            self::REJECTED => FirStatusEnum::REJECTED,
        };
    }

    /** Accettazione parziale e respingimento richiedono la motivazione. */
    public function requiresReason(): bool
    {
        return $this !== self::FULL;
    }
}
