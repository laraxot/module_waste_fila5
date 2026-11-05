<?php

declare(strict_types=1);

namespace Modules\Waste\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Tipo di operazione sul registro cronologico di carico e scarico (art. 190 D.Lgs. 152/2006).
 */
enum RegisterEntryTypeEnum: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case LOAD = 'load';
    case UNLOAD = 'unload';
}
