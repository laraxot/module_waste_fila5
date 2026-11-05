<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

final class InvalidEwcCodeException extends WasteDomainException
{
    public static function malformed(string $raw): self
    {
        return new self(self::message('invalid_ewc_code.malformed', ['code' => $raw]));
    }

    public static function chapterOutOfRange(string $raw): self
    {
        return new self(self::message('invalid_ewc_code.chapter_out_of_range', ['code' => $raw]));
    }

    public static function notFound(int $id): self
    {
        return new self(self::message('invalid_ewc_code.not_found', ['id' => $id]));
    }

    public static function inactive(string $code): self
    {
        return new self(self::message('invalid_ewc_code.inactive', ['code' => $code]));
    }
}
