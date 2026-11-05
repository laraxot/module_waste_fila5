<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

final class ImmutableRegisterEntryException extends WasteDomainException
{
    public static function transmitted(int $year, int $number): self
    {
        return new self(self::message('immutable_register_entry.transmitted', ['year' => $year, 'number' => $number]));
    }

    public static function numbering(int $year, int $number): self
    {
        return new self(self::message('immutable_register_entry.numbering', ['year' => $year, 'number' => $number]));
    }

    public static function deletion(int $year, int $number): self
    {
        return new self(self::message('immutable_register_entry.deletion', ['year' => $year, 'number' => $number]));
    }
}
