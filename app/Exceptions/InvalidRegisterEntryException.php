<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

final class InvalidRegisterEntryException extends WasteDomainException
{
    public static function registerNotFound(int $registerId): self
    {
        return new self(self::message('invalid_register_entry.register_not_found', ['id' => $registerId]));
    }

    public static function registerInactive(string $name): self
    {
        return new self(self::message('invalid_register_entry.register_inactive', ['register' => $name]));
    }

    public static function causeNotAllowed(string $cause, string $type): self
    {
        return new self(self::message('invalid_register_entry.cause_not_allowed', ['cause' => $cause, 'type' => $type]));
    }

    public static function operationRequired(string $cause): self
    {
        return new self(self::message('invalid_register_entry.operation_required', ['cause' => $cause]));
    }

    public static function counterpartRequired(string $cause): self
    {
        return new self(self::message('invalid_register_entry.counterpart_required', ['cause' => $cause]));
    }

    public static function counterpartNotFound(int $operatorId): self
    {
        return new self(self::message('invalid_register_entry.counterpart_not_found', ['id' => $operatorId]));
    }

    public static function operationInFuture(string $operationAt): self
    {
        return new self(self::message('invalid_register_entry.operation_in_future', ['operation_at' => $operationAt]));
    }

    public static function loadReferencesOnLoad(): self
    {
        return new self(self::message('invalid_register_entry.load_references_on_load'));
    }

    public static function loadReferencesMissing(): self
    {
        return new self(self::message('invalid_register_entry.load_references_missing'));
    }

    public static function loadReferenceInvalid(int $entryId): self
    {
        return new self(self::message('invalid_register_entry.load_reference_invalid', ['id' => $entryId]));
    }

    public static function loadReferenceEwcMismatch(int $entryId, string $expected, string $actual): self
    {
        return new self(self::message('invalid_register_entry.load_reference_ewc_mismatch', [
            'id' => $entryId,
            'expected' => $expected,
            'actual' => $actual,
        ]));
    }

    public static function loadReferenceSuperseded(int $entryId): self
    {
        return new self(self::message('invalid_register_entry.load_reference_superseded', ['id' => $entryId]));
    }

    public static function firNotFound(int $firId): self
    {
        return new self(self::message('invalid_register_entry.fir_not_found', ['id' => $firId]));
    }

    public static function firCancelled(string $number): self
    {
        return new self(self::message('invalid_register_entry.fir_cancelled', ['fir' => $number]));
    }

    public static function firEwcMismatch(string $firNumber, string $expected): self
    {
        return new self(self::message('invalid_register_entry.fir_ewc_mismatch', ['fir' => $firNumber, 'code' => $expected]));
    }

    public static function rectificationRegisterMismatch(): self
    {
        return new self(self::message('invalid_register_entry.rectification_register_mismatch'));
    }

    public static function rectificationTypeMismatch(): self
    {
        return new self(self::message('invalid_register_entry.rectification_type_mismatch'));
    }

    public static function rectificationReasonMissing(): self
    {
        return new self(self::message('invalid_register_entry.rectification_reason_missing'));
    }
}
