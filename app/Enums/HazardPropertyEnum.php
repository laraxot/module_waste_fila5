<?php

declare(strict_types=1);

namespace Modules\Waste\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Caratteristiche di pericolo dei rifiuti (Allegato I alla Parte IV del D.Lgs. 152/2006,
 * come sostituito in conformità al Reg. UE 1357/2014).
 *
 * Etichette e descrizioni: lang/{it,en}/hazard_property_enum.php.
 */
enum HazardPropertyEnum: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    use EnumTrait;

    case HP1 = 'HP1';
    case HP2 = 'HP2';
    case HP3 = 'HP3';
    case HP4 = 'HP4';
    case HP5 = 'HP5';
    case HP6 = 'HP6';
    case HP7 = 'HP7';
    case HP8 = 'HP8';
    case HP9 = 'HP9';
    case HP10 = 'HP10';
    case HP11 = 'HP11';
    case HP12 = 'HP12';
    case HP13 = 'HP13';
    case HP14 = 'HP14';
    case HP15 = 'HP15';

    /**
     * Normalizza una lista di caratteristiche: niente duplicati, ordine numerico HP1…HP15.
     *
     * @param  iterable<self>  $properties
     * @return list<self>
     */
    public static function normalizeList(iterable $properties): array
    {
        $byValue = [];
        foreach ($properties as $property) {
            $byValue[$property->value] = $property;
        }

        $result = [];
        foreach (self::cases() as $case) {
            if (isset($byValue[$case->value])) {
                $result[] = $case;
            }
        }

        return $result;
    }
}
