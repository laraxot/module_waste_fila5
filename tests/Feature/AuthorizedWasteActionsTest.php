<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Modules\Waste\Actions\FindAuthorizedWasteAction;
use Modules\Waste\Actions\GetAuthorizedOperationsAction;
use Modules\Waste\Actions\GetAuthorizedQuantityUsageAction;
use Modules\Waste\Actions\PostRegisterEntryAction;
use Modules\Waste\Actions\RectifyRegisterEntryAction;
use Modules\Waste\Database\Factories\AuthorizedWasteFactory;
use Modules\Waste\Database\Factories\OperatorSiteFactory;
use Modules\Waste\Database\Factories\PlantAuthorizationFactory;
use Modules\Waste\Datas\RegisterEntryData;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Exceptions\InvalidQuantityException;
use Modules\Waste\Models\AuthorizedWaste;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\OperatorSite;
use Modules\Waste\Models\PlantAuthorization;
use Modules\Waste\Models\Register;
use Modules\Waste\Tests\Support\WasteFixtures;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @return array{0: OperatorSite, 1: EwcCode, 2: AuthorizedWaste}
 */
function authorizedPlant(?PlantAuthorization $authorization = null): array
{
    $authorization ??= PlantAuthorization::factory()->createOne();
    $ewc = WasteFixtures::ewc();
    $authorized = AuthorizedWasteFactory::new()
        ->withOperations(WasteOperationEnum::R13, WasteOperationEnum::R3)
        ->createOne([
            'plant_authorization_id' => $authorization->id,
            'ewc_code_id' => $ewc->id,
            'max_storage_kg' => 1000,
            'max_annual_kg' => 10000,
        ]);

    return [$authorization->site, $ewc, $authorized];
}

describe('FindAuthorizedWasteAction', function (): void {
    test('finds the authorized code for a valid permit, code and operation', function (): void {
        [$plant, $ewc, $authorized] = authorizedPlant();

        $found = app(FindAuthorizedWasteAction::class)->execute($plant, $ewc, WasteOperationEnum::R13);

        Assert::assertInstanceOf(AuthorizedWaste::class, $found);
        Assert::assertSame($authorized->id, $found->id);
    });

    test('returns null for an operation not authorized for the code', function (): void {
        [$plant, $ewc] = authorizedPlant();

        Assert::assertNull(app(FindAuthorizedWasteAction::class)->execute($plant, $ewc, WasteOperationEnum::R12));
    });

    test('returns null for an expired permit', function (): void {
        [$plant, $ewc] = authorizedPlant(PlantAuthorizationFactory::new()->expired()->createOne());

        Assert::assertNull(app(FindAuthorizedWasteAction::class)->execute($plant, $ewc, WasteOperationEnum::R13));
    });

    test('a permit is valid on its expiry date', function (): void {
        [$plant, $ewc] = authorizedPlant(PlantAuthorization::factory()->createOne(['expires_at' => CarbonImmutable::today()->toDateString()]));

        Assert::assertNotNull(app(FindAuthorizedWasteAction::class)->execute($plant, $ewc, WasteOperationEnum::R13));
    });

    test('returns null for an inactive or not yet issued permit', function (): void {
        [$plantA, $ewcA] = authorizedPlant(PlantAuthorizationFactory::new()->inactive()->createOne());
        [$plantB, $ewcB] = authorizedPlant(PlantAuthorization::factory()->createOne(['issued_at' => CarbonImmutable::tomorrow()->toDateString()]));

        Assert::assertNull(app(FindAuthorizedWasteAction::class)->execute($plantA, $ewcA, WasteOperationEnum::R13));
        Assert::assertNull(app(FindAuthorizedWasteAction::class)->execute($plantB, $ewcB, WasteOperationEnum::R13));
    });

    test('returns null for another plant or another code', function (): void {
        [$plant, $ewc] = authorizedPlant();
        $otherPlant = OperatorSiteFactory::new()->plant()->createOne();

        Assert::assertNull(app(FindAuthorizedWasteAction::class)->execute($otherPlant, $ewc, WasteOperationEnum::R13));
        Assert::assertNull(app(FindAuthorizedWasteAction::class)->execute($plant, WasteFixtures::ewc(), WasteOperationEnum::R13));
    });
});

describe('FindAuthorizedWasteAction alla data dell\'operazione', function (): void {
    test('ignores a permit that was expired on the operation date', function (): void {
        [$plant, $ewc] = authorizedPlant(PlantAuthorization::factory()->createOne([
            'issued_at' => CarbonImmutable::today()->subYears(2)->toDateString(),
            'expires_at' => CarbonImmutable::today()->subMonth()->toDateString(),
        ]));

        Assert::assertNotNull(app(FindAuthorizedWasteAction::class)->execute($plant, $ewc, WasteOperationEnum::R13, CarbonImmutable::today()->subMonths(2)));
        Assert::assertNull(app(FindAuthorizedWasteAction::class)->execute($plant, $ewc, WasteOperationEnum::R13, CarbonImmutable::today()));
    });

    test('ignores a permit not yet issued on the operation date', function (): void {
        [$plant, $ewc] = authorizedPlant(PlantAuthorization::factory()->createOne([
            'issued_at' => CarbonImmutable::today()->subDays(10)->toDateString(),
        ]));

        Assert::assertNull(app(FindAuthorizedWasteAction::class)->execute($plant, $ewc, WasteOperationEnum::R13, CarbonImmutable::today()->subDays(11)));
        Assert::assertNotNull(app(FindAuthorizedWasteAction::class)->execute($plant, $ewc, WasteOperationEnum::R13, CarbonImmutable::today()->subDays(10)));
    });

    test('ignores an inactive permit whatever the date', function (): void {
        [$plant, $ewc] = authorizedPlant(PlantAuthorizationFactory::new()->inactive()->createOne());

        Assert::assertNull(app(FindAuthorizedWasteAction::class)->execute($plant, $ewc, WasteOperationEnum::R13, CarbonImmutable::today()->subMonth()));
    });

    test('with an old and a renewed permit for the same code the most recently issued wins', function (): void {
        $renewed = PlantAuthorization::factory()->createOne(['issued_at' => CarbonImmutable::today()->subMonth()->toDateString()]);
        [$plant, $ewc, $renewedCode] = authorizedPlant($renewed);
        $old = PlantAuthorization::factory()->createOne([
            'operator_site_id' => $plant->id,
            'issued_at' => CarbonImmutable::today()->subYears(5)->toDateString(),
        ]);
        AuthorizedWasteFactory::new()->withOperations(WasteOperationEnum::R13)->createOne([
            'plant_authorization_id' => $old->id,
            'ewc_code_id' => $ewc->id,
            'max_storage_kg' => 5000,
        ]);

        $found = app(FindAuthorizedWasteAction::class)->execute($plant, $ewc, WasteOperationEnum::R13);

        Assert::assertInstanceOf(AuthorizedWaste::class, $found);
        Assert::assertSame($renewedCode->id, $found->id);
        Assert::assertSame(1000.0, $found->maxStorageKg());
    });

    test('the validOn scope keeps only codes of valid permits', function (): void {
        [, , $valid] = authorizedPlant();
        [, , $expired] = authorizedPlant(PlantAuthorizationFactory::new()->expired()->createOne());

        $ids = AuthorizedWaste::query()->validOn(CarbonImmutable::today())->whereKey([$valid->id, $expired->id])->pluck('id')->all();

        Assert::assertSame([$valid->id], $ids);
        Assert::assertTrue($valid->isValidOn(CarbonImmutable::today()));
        Assert::assertFalse($expired->isValidOn(CarbonImmutable::today()));
    });
});

describe('GetAuthorizedQuantityUsageAction', function (): void {
    test('computes instantaneous and annual usage from the loads of the year', function (): void {
        [$plant, $ewc, $authorized] = authorizedPlant();
        $register = Register::factory()->createOne(['operator_site_id' => $plant->id]);
        $post = app(PostRegisterEntryAction::class);

        $post->execute(WasteFixtures::loadData($register, $ewc, quantityKg: 2000.0));
        $post->execute(WasteFixtures::loadData($register, $ewc, quantityKg: 3000.0));
        $toRectify = $post->execute(WasteFixtures::loadData($register, $ewc, quantityKg: 500.0));
        $corrected = RegisterEntryData::fromEntry($toRectify);
        $corrected->quantityKg = 700.0;
        app(RectifyRegisterEntryAction::class)->execute($toRectify, $corrected, 'correzione peso');
        // fuori conteggio: anno precedente, scarico, altro codice, altro impianto
        $post->execute(WasteFixtures::loadData($register, $ewc, quantityKg: 9000.0, operationAt: CarbonImmutable::now()->subYear()));
        $load = $post->execute(WasteFixtures::loadData($register, $ewc, quantityKg: 100.0));
        $post->execute(WasteFixtures::unloadData($register, $ewc, [$load->id], quantityKg: 100.0));
        $post->execute(WasteFixtures::loadData($register, WasteFixtures::ewc(), quantityKg: 4000.0));
        $post->execute(WasteFixtures::loadData(WasteFixtures::register(), $ewc, quantityKg: 4000.0));

        $usage = app(GetAuthorizedQuantityUsageAction::class)->execute($authorized, 250.0);

        Assert::assertSame(250.0, $usage->currentStockKg);
        Assert::assertSame(1000.0, $usage->maxStorageKg);
        Assert::assertSame(25.0, $usage->storageUsagePct);
        Assert::assertSame(5800.0, $usage->annualLoadedKg);
        Assert::assertSame(10000.0, $usage->maxAnnualKg);
        Assert::assertSame(58.0, $usage->annualUsagePct);
        Assert::assertSame(4200.0, $usage->remainingAnnualKg());
        Assert::assertFalse($usage->isStorageExceeded());
    });

    test('percentages are null when the limits are not set', function (): void {
        [, , $authorized] = authorizedPlant();
        $authorized->update(['max_storage_kg' => null, 'max_annual_kg' => null]);

        $usage = app(GetAuthorizedQuantityUsageAction::class)->execute($authorized->refresh(), 10.0);

        Assert::assertNull($usage->storageUsagePct);
        Assert::assertNull($usage->annualUsagePct);
        Assert::assertSame(0.0, $usage->annualLoadedKg);
    });

    test('rejects a negative current stock', function (): void {
        [, , $authorized] = authorizedPlant();

        app(GetAuthorizedQuantityUsageAction::class)->execute($authorized, -1.0);
    })->throws(InvalidQuantityException::class);
});

describe('GetAuthorizedOperationsAction', function (): void {
    test('lists the operations of valid permits for the code, in enum order', function (): void {
        [$plant, $ewc] = authorizedPlant();

        Assert::assertSame(
            [WasteOperationEnum::R3, WasteOperationEnum::R13],
            app(GetAuthorizedOperationsAction::class)->execute($plant, $ewc),
        );
    });

    test('ignores expired permits and other codes', function (): void {
        [$plant, $ewc] = authorizedPlant(PlantAuthorizationFactory::new()->expired()->createOne());
        $otherEwc = WasteFixtures::ewc();

        Assert::assertSame([], app(GetAuthorizedOperationsAction::class)->execute($plant, $ewc));
        Assert::assertSame([], app(GetAuthorizedOperationsAction::class)->execute($plant, $otherEwc));
    });
});
