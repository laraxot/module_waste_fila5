<?php

declare(strict_types=1);

namespace Modules\Waste\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Stato fisico del rifiuto come indicato su FIR e registro. Il codice RENTRI corrispondente è mappato dal modulo Rentri.
 */
enum PhysicalStateEnum: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    use EnumTrait;

    case SOLID_POWDERY = 'solid_powdery';
    case SOLID_NON_POWDERY = 'solid_non_powdery';
    case SLUDGE = 'sludge';
    case LIQUID = 'liquid';
    case VISCOUS = 'viscous';
}
