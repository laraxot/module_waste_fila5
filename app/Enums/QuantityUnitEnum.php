<?php

declare(strict_types=1);

namespace Modules\Waste\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Unità di misura della quantità sul FIR. Il registro e i limiti autorizzativi sono sempre in kg.
 */
enum QuantityUnitEnum: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case KG = 'kg';
    case L = 'l';
    case M3 = 'm3';
}
