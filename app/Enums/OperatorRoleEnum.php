<?php

declare(strict_types=1);

namespace Modules\Waste\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Ruoli di un soggetto nella filiera (art. 183 D.Lgs. 152/2006). Un soggetto può averne più d'uno.
 */
enum OperatorRoleEnum: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case PRODUCER = 'producer';
    case HOLDER = 'holder';
    case TRANSPORTER = 'transporter';
    case INTERMEDIARY = 'intermediary';
    case DEALER = 'dealer';
    case RECIPIENT = 'recipient';
}
