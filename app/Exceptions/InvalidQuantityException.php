<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

final class InvalidQuantityException extends WasteDomainException
{
    public static function notPositive(float $quantity): self
    {
        return new self(self::message('invalid_quantity.not_positive', ['quantity' => $quantity]));
    }

    public static function negative(float $quantity): self
    {
        return new self(self::message('invalid_quantity.negative', ['quantity' => $quantity]));
    }

    public static function rejectedMustBeZero(float $quantity): self
    {
        return new self(self::message('invalid_quantity.rejected_must_be_zero', ['quantity' => $quantity]));
    }
}
