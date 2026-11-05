<?php

declare(strict_types=1);

use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('WasteOperationEnum', function (): void {
    test('lists R1-R13 and D1-D15 with the legal codes as values', function (): void {
        $values = array_map(static fn (WasteOperationEnum $op): string => $op->value, WasteOperationEnum::cases());

        $expected = [
            ...array_map(static fn (int $n): string => 'R'.$n, range(1, 13)),
            ...array_map(static fn (int $n): string => 'D'.$n, range(1, 15)),
        ];
        Assert::assertSame($expected, $values);
    });

    test('splits recovery and disposal operations', function (): void {
        $recovery = array_filter(WasteOperationEnum::cases(), static fn (WasteOperationEnum $op): bool => $op->isRecovery());
        $disposal = array_filter(WasteOperationEnum::cases(), static fn (WasteOperationEnum $op): bool => $op->isDisposal());

        Assert::assertCount(13, $recovery);
        Assert::assertCount(15, $disposal);
        foreach (WasteOperationEnum::cases() as $op) {
            Assert::assertNotSame($op->isRecovery(), $op->isDisposal(), $op->value);
        }
    });

    test('only R13 and D15 are preliminary storage', function (): void {
        $storage = array_values(array_filter(
            WasteOperationEnum::cases(),
            static fn (WasteOperationEnum $op): bool => $op->isPreliminaryStorage(),
        ));

        Assert::assertSame([WasteOperationEnum::R13, WasteOperationEnum::D15], $storage);
    });

    test('exposes the operation number', function (): void {
        Assert::assertSame(12, WasteOperationEnum::R12->number());
        Assert::assertSame(15, WasteOperationEnum::D15->number());
    });
});

describe('HazardPropertyEnum', function (): void {
    test('lists HP1-HP15 with the legal codes as values', function (): void {
        $values = array_map(static fn (HazardPropertyEnum $hp): string => $hp->value, HazardPropertyEnum::cases());

        Assert::assertSame(array_map(static fn (int $n): string => 'HP'.$n, range(1, 15)), $values);
    });

    test('normalizes a list: no duplicates, legal order', function (): void {
        $normalized = HazardPropertyEnum::normalizeList([
            HazardPropertyEnum::HP14,
            HazardPropertyEnum::HP4,
            HazardPropertyEnum::HP14,
            HazardPropertyEnum::HP10,
        ]);

        Assert::assertSame([HazardPropertyEnum::HP4, HazardPropertyEnum::HP10, HazardPropertyEnum::HP14], $normalized);
    });
});
