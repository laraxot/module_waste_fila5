<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

final class RegisterEntryAlreadyRectifiedException extends WasteDomainException
{
    public static function make(int $year, int $number): self
    {
        return new self(self::message('register_entry_already_rectified', ['year' => $year, 'number' => $number]));
    }
}
