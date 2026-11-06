<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Modules\Waste\Actions\PostRegisterEntryAction;
use Modules\Waste\Actions\RectifyRegisterEntryAction;
use Modules\Waste\Datas\RegisterEntryData;
use Modules\Waste\Exceptions\ImmutableRegisterEntryException;
use Modules\Waste\Exceptions\InvalidRegisterEntryException;
use Modules\Waste\Exceptions\RegisterEntryAlreadyRectifiedException;
use Modules\Waste\Models\RegisterEntry;
use Modules\Waste\Tests\Support\WasteFixtures;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @return array{0: RegisterEntry, 1: RegisterEntryData}
 */
function postedLoad(float $kg = 100.0): array
{
    $data = WasteFixtures::loadData(WasteFixtures::register(), WasteFixtures::ewc(), quantityKg: $kg);

    return [app(PostRegisterEntryAction::class)->execute($data), $data];
}

describe('RegisterEntry immutability', function (): void {
    test('the transmission can be marked once, then nothing changes', function (): void {
        [$entry] = postedLoad();

        $entry->transmitted_at = CarbonImmutable::now();
        $entry->rentri_transmission_ref = 'TX-1';
        $entry->save();

        $entry->notes = 'modifica tardiva';
        $entry->save();
    })->throws(ImmutableRegisterEntryException::class);

    test('a transmitted entry cannot be deleted', function (): void {
        [$entry] = postedLoad();
        $entry->transmitted_at = CarbonImmutable::now();
        $entry->save();

        $entry->delete();
    })->throws(ImmutableRegisterEntryException::class);

    test('no entry can be deleted, not even before transmission', function (): void {
        [$entry] = postedLoad();

        $entry->delete();
    })->throws(ImmutableRegisterEntryException::class);

    test('the numbering of an entry never changes', function (): void {
        [$entry] = postedLoad();

        $entry->number = 99;
        $entry->save();
    })->throws(ImmutableRegisterEntryException::class);
});

describe('RectifyRegisterEntryAction', function (): void {
    test('records a new entry that supersedes the original, which stays unchanged', function (): void {
        [$original, $data] = postedLoad(100.0);
        $original->transmitted_at = CarbonImmutable::now();
        $original->save();

        $corrected = RegisterEntryData::fromEntry($original);
        $corrected->quantityKg = 120.0;
        $rectification = app(RectifyRegisterEntryAction::class)->execute($original, $corrected, 'Pesata a destino errata');

        $fresh = RegisterEntry::query()->findOrFail($original->id);
        Assert::assertSame('100.000', $fresh->quantity_kg);
        Assert::assertSame($original->id, $rectification->rectifies_entry_id);
        Assert::assertSame('120.000', $rectification->quantity_kg);
        Assert::assertSame('Pesata a destino errata', $rectification->rectification_reason);
        Assert::assertSame($original->number + 1, $rectification->number);
        Assert::assertSame($data->registerId, $rectification->register_id);

        $effective = RegisterEntry::query()->effective()->where('register_id', $data->registerId)->pluck('id')->all();
        Assert::assertSame([$rectification->id], $effective);
    });

    test('an entry is rectified only once', function (): void {
        [$original] = postedLoad();
        $rectify = app(RectifyRegisterEntryAction::class);
        $rectify->execute($original, RegisterEntryData::fromEntry($original), 'prima');

        $rectify->execute($original, RegisterEntryData::fromEntry($original), 'seconda');
    })->throws(RegisterEntryAlreadyRectifiedException::class);

    test('the rectification can itself be rectified', function (): void {
        [$original] = postedLoad();
        $rectify = app(RectifyRegisterEntryAction::class);
        $first = $rectify->execute($original, RegisterEntryData::fromEntry($original), 'prima');

        $second = $rectify->execute($first, RegisterEntryData::fromEntry($first), 'seconda');

        Assert::assertSame($first->id, $second->rectifies_entry_id);
    });

    test('requires a reason', function (): void {
        [$original] = postedLoad();

        app(RectifyRegisterEntryAction::class)->execute($original, RegisterEntryData::fromEntry($original), '  ');
    })->throws(InvalidRegisterEntryException::class);

    test('must stay on the same register', function (): void {
        [$original] = postedLoad();
        $corrected = RegisterEntryData::fromEntry($original);
        $corrected->registerId = WasteFixtures::register()->id;

        app(RectifyRegisterEntryAction::class)->execute($original, $corrected, 'registro sbagliato');
    })->throws(InvalidRegisterEntryException::class);

    test('a superseded load cannot be referenced by an unload', function (): void {
        [$original] = postedLoad();
        app(RectifyRegisterEntryAction::class)->execute($original, RegisterEntryData::fromEntry($original), 'correzione');
        $register = $original->register;

        app(PostRegisterEntryAction::class)->execute(WasteFixtures::unloadData($register, $original->ewcCode, [$original->id]));
    })->throws(InvalidRegisterEntryException::class);
});
