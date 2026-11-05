<?php

declare(strict_types=1);

use Modules\Waste\Enums\RegisterCauseEnum;
use Modules\Waste\Enums\RegisterEntryTypeEnum;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('RegisterCauseEnum', function (): void {
    test('matches each cause with the entry types it can be used for', function (RegisterCauseEnum $cause, bool $load, bool $unload): void {
        Assert::assertSame($load, $cause->allowsType(RegisterEntryTypeEnum::LOAD));
        Assert::assertSame($unload, $cause->allowsType(RegisterEntryTypeEnum::UNLOAD));
    })->with([
        'new production' => [RegisterCauseEnum::NEW_PRODUCTION, true, false],
        'from third parties' => [RegisterCauseEnum::FROM_THIRD_PARTIES, true, false],
        'to third parties' => [RegisterCauseEnum::TO_THIRD_PARTIES, false, true],
        'internal treatment' => [RegisterCauseEnum::INTERNAL_TREATMENT, false, true],
        'rejected return' => [RegisterCauseEnum::REJECTED_RETURN, true, true],
        'adjustment' => [RegisterCauseEnum::ADJUSTMENT, true, true],
    ]);

    test('requires the R/D operation only for movements to treatment', function (): void {
        $requiring = array_values(array_filter(RegisterCauseEnum::cases(), static fn (RegisterCauseEnum $c): bool => $c->requiresOperation()));

        Assert::assertSame([RegisterCauseEnum::TO_THIRD_PARTIES, RegisterCauseEnum::INTERNAL_TREATMENT], $requiring);
    });

    test('requires the counterpart only for movements with third parties', function (): void {
        $requiring = array_values(array_filter(RegisterCauseEnum::cases(), static fn (RegisterCauseEnum $c): bool => $c->requiresCounterpart()));

        Assert::assertSame([RegisterCauseEnum::FROM_THIRD_PARTIES, RegisterCauseEnum::TO_THIRD_PARTIES], $requiring);
    });
});
