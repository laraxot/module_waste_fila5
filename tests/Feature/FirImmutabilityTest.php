<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Modules\Waste\Actions\ChangeFirStatusAction;
use Modules\Waste\Actions\RecordFirAcceptanceAction;
use Modules\Waste\Database\Factories\FirFactory;
use Modules\Waste\Enums\AcceptanceEnum;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Exceptions\ImmutableFirException;
use Modules\Waste\Filament\Resources\FirResource;
use Modules\Waste\Models\Fir;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

function immutableFir(FirStatusEnum $status = FirStatusEnum::DRAFT): Fir
{
    return FirFactory::new()->status($status)->createOne();
}

/** FIR emesso senza numero: il numero lo assegna RENTRI (o l'operatore per il cartaceo). */
function issuedFir(): Fir
{
    $draft = immutableFir();
    $draft->update(['number' => null]);

    return app(ChangeFirStatusAction::class)->execute($draft, FirStatusEnum::ISSUED);
}

describe('FIR in bozza', function (): void {
    test('every field can still be changed', function (): void {
        $fir = immutableFir();

        $fir->update(['vehicle_plate' => 'ZZ999ZZ', 'quantity' => 999.0, 'notes' => 'corretto']);

        Assert::assertSame('ZZ999ZZ', $fir->refresh()->vehicle_plate);
    });

    test('a draft can be deleted by the model', function (): void {
        $fir = immutableFir();

        Assert::assertTrue((bool) $fir->delete());
    });
});

describe('FIR emesso', function (): void {
    test('the content of an issued form cannot be changed, not even bypassing the policy', function (string $field, mixed $value): void {
        $fir = issuedFir();

        $fir->update([$field => $value]);
    })->with([
        'quantity' => ['quantity', 1.0],
        'ewc code' => ['ewc_code_id', 999999],
        'producer' => ['producer_operator_id', 999999],
        'plate' => ['vehicle_plate', 'ZZ999ZZ'],
        'notes' => ['notes', 'ritocco'],
        'digital flag back to paper' => ['is_digital', false],
    ])->throws(ImmutableFirException::class);

    test('acceptance fields change only together with an acceptance outcome', function (): void {
        $fir = issuedFir();

        $fir->update(['accepted_quantity_kg' => 10.0]);
    })->throws(ImmutableFirException::class);

    test('the status can only move along the allowed transitions', function (): void {
        $fir = app(ChangeFirStatusAction::class)->execute(issuedFir(), FirStatusEnum::IN_TRANSIT);

        $fir->update(['status' => FirStatusEnum::DRAFT]);
    })->throws(ImmutableFirException::class);

    test('an issued form cannot be deleted', function (): void {
        issuedFir()->delete();
    })->throws(ImmutableFirException::class);

    test('the edit page is not available after the draft', function (): void {
        Assert::assertFalse(FirResource::canEdit(issuedFir()));
    });
});

describe('le actions di Waste e Rentri restano consentite', function (): void {
    test('status changes and acceptance go through', function (): void {
        $fir = issuedFir();
        $fir = app(ChangeFirStatusAction::class)->execute($fir, FirStatusEnum::IN_TRANSIT);
        Assert::assertNotNull($fir->transport_started_at);

        $fir = app(RecordFirAcceptanceAction::class)->execute($fir, AcceptanceEnum::PARTIAL, 800.0, CarbonImmutable::now(), 'umido');

        Assert::assertSame(FirStatusEnum::PARTIALLY_ACCEPTED, $fir->refresh()->status);
        Assert::assertSame('800.000', $fir->accepted_quantity_kg);
    });

    test('acceptance straight from issued passes through delivered in one save', function (): void {
        $fir = app(RecordFirAcceptanceAction::class)->execute(issuedFir(), AcceptanceEnum::FULL, 1000.0, CarbonImmutable::now());

        Assert::assertSame(FirStatusEnum::ACCEPTED, $fir->refresh()->status);
    });

    test('RENTRI can assign the number and the digital flag once', function (): void {
        $fir = issuedFir();

        $fir->forceFill(['number' => 'XRAB 000123 AA', 'is_digital' => true])->save();
        Assert::assertSame('XRAB 000123 AA', $fir->refresh()->number);

        $fir->update(['number' => 'XRAB 000999 ZZ']);
    })->throws(ImmutableFirException::class);

    test('cancelling an issued form is allowed', function (): void {
        $fir = app(ChangeFirStatusAction::class)->execute(issuedFir(), FirStatusEnum::CANCELLED);

        Assert::assertSame(FirStatusEnum::CANCELLED, $fir->refresh()->status);
    });
});

describe('FirStatusEnum::canReach', function (): void {
    test('follows the transitions transitively', function (): void {
        Assert::assertTrue(FirStatusEnum::ISSUED->canReach(FirStatusEnum::ACCEPTED));
        Assert::assertTrue(FirStatusEnum::DRAFT->canReach(FirStatusEnum::REJECTED));
        Assert::assertFalse(FirStatusEnum::IN_TRANSIT->canReach(FirStatusEnum::CANCELLED));
        Assert::assertFalse(FirStatusEnum::ACCEPTED->canReach(FirStatusEnum::DRAFT));
        Assert::assertFalse(FirStatusEnum::ISSUED->canReach(FirStatusEnum::ISSUED));
    });
});
