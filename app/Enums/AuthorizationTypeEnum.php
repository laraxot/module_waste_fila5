<?php

declare(strict_types=1);

namespace Modules\Waste\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Titolo autorizzativo dell'impianto di gestione rifiuti.
 */
enum AuthorizationTypeEnum: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    use EnumTrait;

    case AIA = 'aia';
    case AUA = 'aua';
    case ORDINARY_208 = 'ordinary_208';
    case SIMPLIFIED_214 = 'simplified_214';
    case MOBILE_PLANT_208 = 'mobile_plant_208';
    case OTHER = 'other';
}
