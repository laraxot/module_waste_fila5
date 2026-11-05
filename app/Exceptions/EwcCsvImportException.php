<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

final class EwcCsvImportException extends WasteDomainException
{
    public static function unreadable(string $path): self
    {
        return new self(self::message('ewc_csv_import.unreadable', ['path' => $path]));
    }

    public static function invalidRow(int $line, string $reason): self
    {
        return new self(self::message('ewc_csv_import.invalid_row', ['line' => $line, 'reason' => $reason]));
    }
}
