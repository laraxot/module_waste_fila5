<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

final class HazardPropertiesMismatchException extends WasteDomainException
{
    /** Codice EER pericoloso (asterisco) senza alcuna caratteristica HP. */
    public static function missingForHazardous(string $code): self
    {
        return new self(self::message('hazard_mismatch.missing_for_hazardous', ['code' => $code]));
    }

    /** Caratteristiche HP indicate per un codice EER non pericoloso. */
    public static function presentForNonHazardous(string $code): self
    {
        return new self(self::message('hazard_mismatch.present_for_non_hazardous', ['code' => $code]));
    }
}
