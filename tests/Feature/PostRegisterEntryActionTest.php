<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Modules\Waste\Actions\PostRegisterEntryAction;
use Modules\Waste\Datas\RegisterEntryData;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\RegisterCauseEnum;
use Modules\Waste\Enums\RegisterEntryTypeEnum;
use Modules\Waste\Exceptions\HazardPropertiesMismatchException;
use Modules\Waste\Exceptions\InvalidQuantityException;
use Modules\Waste\Exceptions\InvalidRegisterEntryException;
use Modules\Waste\Models\RegisterEntry;
use Modules\Waste\Tests\Support\WasteFixtures;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('PostRegisterEntryAction — numbering', function (): void {
    test('numbers entries progressively per register and year, starting from 1', function (): void {
        $register = WasteFixtures::register();
        $other = WasteFixtures::register();
        $ewc = WasteFixtures::ewc();
        $post = app(PostRegisterEntryAction::class);

        $first = $post->execute(WasteFixtures::loadData($register, $ewc));
        $second = $post->execute(WasteFixtures::loadData($register, $ewc));
        $otherFirst = $post->execute(WasteFixtures::loadData($other, $ewc));
        $third = $post->execute(WasteFixtures::loadData($register, $ewc));

        Assert::assertSame([1, 2, 3], [$first->number, $second->number, $third->number]);
        Assert::assertSame(1, $otherFirst->number);
        Assert::assertSame(CarbonImmutable::now()->year, $first->year);
    });

    test('restarts from 1 when the year changes (year of recording)', function (): void {
        $register = WasteFixtures::register();
        $ewc = WasteFixtures::ewc();
        $post = app(PostRegisterEntryAction::class);

        $this->travelTo(CarbonImmutable::create(2026, 12, 31, 18, 0, 0));
        $post->execute(WasteFixtures::loadData($register, $ewc));
        $lastOfYear = $post->execute(WasteFixtures::loadData($register, $ewc));

        $this->travelTo(CarbonImmutable::create(2027, 1, 2, 9, 0, 0));
        $december = CarbonImmutable::create(2026, 12, 31, 17, 0, 0);
        $firstOfYear = $post->execute(WasteFixtures::loadData($register, $ewc, operationAt: $december));

        Assert::assertSame([2026, 2], [$lastOfYear->year, $lastOfYear->number]);
        Assert::assertSame([2027, 1], [$firstOfYear->year, $firstOfYear->number]);
        Assert::assertSame(2026, $firstOfYear->operation_at->year);
    });

    test('records recorded_at = now and the normalized data', function (): void {
        $this->travelTo(CarbonImmutable::create(2026, 11, 4, 10, 30, 0));
        $register = WasteFixtures::register();
        $ewc = WasteFixtures::ewc(hazardous: true);

        $entry = app(PostRegisterEntryAction::class)->execute(WasteFixtures::loadData(
            $register,
            $ewc,
            quantityKg: 12.3456,
            hazardProperties: [HazardPropertyEnum::HP14, HazardPropertyEnum::HP4, HazardPropertyEnum::HP14],
        ));

        $fresh = RegisterEntry::query()->findOrFail($entry->id);
        Assert::assertSame('2026-11-04 10:30:00', $fresh->recorded_at->format('Y-m-d H:i:s'));
        Assert::assertSame('12.346', $fresh->quantity_kg);
        Assert::assertSame(['HP4', 'HP14'], $fresh->hazard_properties->map(static fn (HazardPropertyEnum $hp): string => $hp->value)->all());
    });
});

describe('PostRegisterEntryAction — validation', function (): void {
    test('rejects a quantity that is not greater than zero', function (float $quantity): void {
        $register = WasteFixtures::register();
        app(PostRegisterEntryAction::class)->execute(WasteFixtures::loadData($register, WasteFixtures::ewc(), quantityKg: $quantity));
    })->with([[0.0], [-5.0]])->throws(InvalidQuantityException::class);

    test('requires at least one HP for a hazardous EWC code', function (): void {
        $register = WasteFixtures::register();
        app(PostRegisterEntryAction::class)->execute(WasteFixtures::loadData($register, WasteFixtures::ewc(hazardous: true)));
    })->throws(HazardPropertiesMismatchException::class);

    test('forbids HP on a non-hazardous EWC code', function (): void {
        $register = WasteFixtures::register();
        app(PostRegisterEntryAction::class)->execute(WasteFixtures::loadData(
            $register,
            WasteFixtures::ewc(),
            hazardProperties: [HazardPropertyEnum::HP14],
        ));
    })->throws(HazardPropertiesMismatchException::class);

    test('forbids a cause that does not match the entry type', function (): void {
        $register = WasteFixtures::register();
        $ewc = WasteFixtures::ewc();

        app(PostRegisterEntryAction::class)->execute(new RegisterEntryData(
            registerId: $register->id,
            type: RegisterEntryTypeEnum::LOAD,
            cause: RegisterCauseEnum::TO_THIRD_PARTIES,
            ewcCodeId: $ewc->id,
            quantityKg: 10.0,
            physicalState: PhysicalStateEnum::SOLID_NON_POWDERY,
            operationAt: CarbonImmutable::now(),
        ));
    })->throws(InvalidRegisterEntryException::class);

    test('forbids an operation date in the future', function (): void {
        $register = WasteFixtures::register();
        app(PostRegisterEntryAction::class)->execute(WasteFixtures::loadData(
            $register,
            WasteFixtures::ewc(),
            operationAt: CarbonImmutable::now()->addDay(),
        ));
    })->throws(InvalidRegisterEntryException::class);

    test('accepts an unload that references loads of the same EWC code', function (): void {
        $register = WasteFixtures::register();
        $ewc = WasteFixtures::ewc();
        $post = app(PostRegisterEntryAction::class);
        $load = $post->execute(WasteFixtures::loadData($register, $ewc));

        $unload = $post->execute(WasteFixtures::unloadData($register, $ewc, [$load->id]));

        Assert::assertSame(RegisterEntryTypeEnum::UNLOAD, $unload->type);
        Assert::assertSame([$load->id], $unload->load_entry_ids);
        Assert::assertSame(2, $unload->number);
    });

    test('rejects an unload without load references', function (): void {
        $register = WasteFixtures::register();
        app(PostRegisterEntryAction::class)->execute(WasteFixtures::unloadData($register, WasteFixtures::ewc(), []));
    })->throws(InvalidRegisterEntryException::class);

    test('rejects an unload whose loads have another EWC code', function (): void {
        $register = WasteFixtures::register();
        $post = app(PostRegisterEntryAction::class);
        $load = $post->execute(WasteFixtures::loadData($register, WasteFixtures::ewc()));

        $post->execute(WasteFixtures::unloadData($register, WasteFixtures::ewc(), [$load->id]));
    })->throws(InvalidRegisterEntryException::class);

    test('rejects an unload referencing a load of another register', function (): void {
        $ewc = WasteFixtures::ewc();
        $post = app(PostRegisterEntryAction::class);
        $load = $post->execute(WasteFixtures::loadData(WasteFixtures::register(), $ewc));

        $post->execute(WasteFixtures::unloadData(WasteFixtures::register(), $ewc, [$load->id]));
    })->throws(InvalidRegisterEntryException::class);

    test('rejects a load that references other loads', function (): void {
        $register = WasteFixtures::register();
        $ewc = WasteFixtures::ewc();
        $post = app(PostRegisterEntryAction::class);
        $load = $post->execute(WasteFixtures::loadData($register, $ewc));
        $data = WasteFixtures::loadData($register, $ewc);
        $data->loadEntryIds = [$load->id];

        $post->execute($data);
    })->throws(InvalidRegisterEntryException::class);
});
