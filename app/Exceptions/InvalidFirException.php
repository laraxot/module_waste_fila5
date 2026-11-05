<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

final class InvalidFirException extends WasteDomainException
{
    public static function operatorNotFound(int $operatorId): self
    {
        return new self(self::message('invalid_fir.operator_not_found', ['id' => $operatorId]));
    }

    public static function operatorRoleMismatch(string $operator, string $role): self
    {
        return new self(self::message('invalid_fir.operator_role_mismatch', ['operator' => $operator, 'role' => $role]));
    }

    public static function siteNotOfOperator(int $siteId, string $operator): self
    {
        return new self(self::message('invalid_fir.site_not_of_operator', ['id' => $siteId, 'operator' => $operator]));
    }

    public static function reasonRequired(string $acceptance): self
    {
        return new self(self::message('invalid_fir.reason_required', ['acceptance' => $acceptance]));
    }

    public static function duplicateNumber(string $number): self
    {
        return new self(self::message('invalid_fir.duplicate_number', ['number' => $number]));
    }
}
