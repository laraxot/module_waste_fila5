<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Modules\Waste\Actions\ChangeFirStatusAction;
use Modules\Waste\Actions\CreateFirAction;
use Modules\Waste\Actions\RecordFirAcceptanceAction;
use Modules\Waste\Database\Factories\FirFactory;
use Modules\Waste\Database\Factories\OperatorFactory;
use Modules\Waste\Datas\FirData;
use Modules\Waste\Enums\AcceptanceEnum;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\OperatorRoleEnum;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Exceptions\HazardPropertiesMismatchException;
use Modules\Waste\Exceptions\InvalidFirException;
use Modules\Waste\Exceptions\InvalidFirStatusTransitionException;
use Modules\Waste\Exceptions\InvalidQuantityException;
use Modules\Waste\Models\Operator;
use Modules\Waste\Tests\Support\WasteFixtures;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

function firData(bool $hazardous = false, array $hazardProperties = [], FirStatusEnum $status = FirStatusEnum::DRAFT, ?Operator $producer = null): FirData
{
    $producer ??= OperatorFactory::new()->withRoles(OperatorRoleEnum::PRODUCER)->createOne();
    $recipient = OperatorFactory::new()->withRoles(OperatorRoleEnum::RECIPIENT)->createOne();

    return new FirData(
        producerOperatorId: $producer->id,
        recipientOperatorId: $recipient->id,
        ewcCodeId: WasteFixtures::ewc(hazardous: $hazardous)->id,
        physicalState: PhysicalStateEnum::SOLID_NON_POWDERY,
        operation: WasteOperationEnum::R13,
        quantity: 1500.0,
        hazardProperties: $hazardProperties,
        status: $status,
    );
}

describe('CreateFirAction', function (): void {
    test('creates a waste form with normalized data', function (): void {
        $fir = app(CreateFirAction::class)->execute(firData(hazardous: true, hazardProperties: [HazardPropertyEnum::HP14, HazardPropertyEnum::HP5]));

        Assert::assertSame(FirStatusEnum::DRAFT, $fir->status);
        Assert::assertSame('1500.000', $fir->quantity);
        Assert::assertSame(['HP5', 'HP14'], $fir->hazard_properties->map(static fn (HazardPropertyEnum $hp): string => $hp->value)->all());
    });

    test('a paper form already travelling gets the transport start', function (): void {
        $fir = app(CreateFirAction::class)->execute(firData(status: FirStatusEnum::IN_TRANSIT));

        Assert::assertNotNull($fir->transport_started_at);
    });

    test('rejects an outcome as initial status', function (): void {
        app(CreateFirAction::class)->execute(firData(status: FirStatusEnum::ACCEPTED));
    })->throws(InvalidFirStatusTransitionException::class);

    test('checks the role of the producer', function (): void {
        app(CreateFirAction::class)->execute(firData(producer: OperatorFactory::new()->withRoles(OperatorRoleEnum::TRANSPORTER)->createOne()));
    })->throws(InvalidFirException::class);

    test('checks the hazard consistency of the EWC code', function (): void {
        app(CreateFirAction::class)->execute(firData(hazardous: true));
    })->throws(HazardPropertiesMismatchException::class);

    test('rejects a non-positive quantity', function (): void {
        $data = firData();
        $data->quantity = 0.0;

        app(CreateFirAction::class)->execute($data);
    })->throws(InvalidQuantityException::class);
});

describe('ChangeFirStatusAction and RecordFirAcceptanceAction', function (): void {
    test('follows the lifecycle and records the transport start', function (): void {
        $fir = FirFactory::new()->status(FirStatusEnum::ISSUED)->createOne();

        app(ChangeFirStatusAction::class)->execute($fir, FirStatusEnum::IN_TRANSIT);

        Assert::assertSame(FirStatusEnum::IN_TRANSIT, $fir->refresh()->status);
        Assert::assertNotNull($fir->transport_started_at);
    });

    test('refuses a transition not allowed by the enum', function (): void {
        $fir = FirFactory::new()->status(FirStatusEnum::DRAFT)->createOne();

        app(ChangeFirStatusAction::class)->execute($fir, FirStatusEnum::DELIVERED);
    })->throws(InvalidFirStatusTransitionException::class);

    test('refuses an acceptance outcome without acceptance data', function (): void {
        $fir = FirFactory::new()->status(FirStatusEnum::DELIVERED)->createOne();

        app(ChangeFirStatusAction::class)->execute($fir, FirStatusEnum::ACCEPTED);
    })->throws(InvalidFirStatusTransitionException::class);

    test('records a full acceptance from in transit, passing through delivered', function (): void {
        $fir = FirFactory::new()->status(FirStatusEnum::IN_TRANSIT)->createOne();
        $arrivedAt = CarbonImmutable::now()->subHour();

        app(RecordFirAcceptanceAction::class)->execute($fir, AcceptanceEnum::FULL, 1480.5, $arrivedAt);

        $fresh = $fir->refresh();
        Assert::assertSame(FirStatusEnum::ACCEPTED, $fresh->status);
        Assert::assertSame(AcceptanceEnum::FULL, $fresh->acceptance);
        Assert::assertSame('1480.500', $fresh->accepted_quantity_kg);
        Assert::assertSame($arrivedAt->toDateTimeString(), $fresh->arrived_at?->toDateTimeString());
    });

    test('a rejection needs zero accepted kg and a reason', function (float $kg, ?string $reason, string $exception): void {
        $fir = FirFactory::new()->status(FirStatusEnum::DELIVERED)->createOne();

        try {
            app(RecordFirAcceptanceAction::class)->execute($fir, AcceptanceEnum::REJECTED, $kg, CarbonImmutable::now(), $reason);
            Assert::fail('exception expected');
        } catch (Throwable $e) {
            Assert::assertInstanceOf($exception, $e);
        }
    })->with([
        'kg not zero' => [10.0, 'non conforme', InvalidQuantityException::class],
        'no reason' => [0.0, null, InvalidFirException::class],
    ]);

    test('cannot accept a cancelled or draft form', function (FirStatusEnum $status): void {
        $fir = FirFactory::new()->status($status)->createOne();

        app(RecordFirAcceptanceAction::class)->execute($fir, AcceptanceEnum::FULL, 100.0, CarbonImmutable::now());
    })->with([[FirStatusEnum::DRAFT], [FirStatusEnum::CANCELLED]])->throws(InvalidFirStatusTransitionException::class);
});
