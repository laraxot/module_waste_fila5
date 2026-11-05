<?php

declare(strict_types=1);

use Modules\Waste\Enums\AcceptanceEnum;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('FirStatusEnum::canTransitionTo', function (): void {
    test('allows the lifecycle of a waste form', function (FirStatusEnum $from, FirStatusEnum $to): void {
        Assert::assertTrue($from->canTransitionTo($to));
    })->with([
        'draft → issued' => [FirStatusEnum::DRAFT, FirStatusEnum::ISSUED],
        'draft → cancelled' => [FirStatusEnum::DRAFT, FirStatusEnum::CANCELLED],
        'issued → in transit' => [FirStatusEnum::ISSUED, FirStatusEnum::IN_TRANSIT],
        'issued → cancelled' => [FirStatusEnum::ISSUED, FirStatusEnum::CANCELLED],
        'in transit → delivered' => [FirStatusEnum::IN_TRANSIT, FirStatusEnum::DELIVERED],
        'delivered → accepted' => [FirStatusEnum::DELIVERED, FirStatusEnum::ACCEPTED],
        'delivered → partially accepted' => [FirStatusEnum::DELIVERED, FirStatusEnum::PARTIALLY_ACCEPTED],
        'delivered → rejected' => [FirStatusEnum::DELIVERED, FirStatusEnum::REJECTED],
    ]);

    test('forbids skipping or going back', function (FirStatusEnum $from, FirStatusEnum $to): void {
        Assert::assertFalse($from->canTransitionTo($to));
    })->with([
        'draft → delivered' => [FirStatusEnum::DRAFT, FirStatusEnum::DELIVERED],
        'issued → accepted' => [FirStatusEnum::ISSUED, FirStatusEnum::ACCEPTED],
        'in transit → cancelled' => [FirStatusEnum::IN_TRANSIT, FirStatusEnum::CANCELLED],
        'in transit → draft' => [FirStatusEnum::IN_TRANSIT, FirStatusEnum::DRAFT],
        'delivered → in transit' => [FirStatusEnum::DELIVERED, FirStatusEnum::IN_TRANSIT],
        'same status' => [FirStatusEnum::ISSUED, FirStatusEnum::ISSUED],
    ]);

    test('acceptance outcomes and cancellation are final', function (FirStatusEnum $status): void {
        Assert::assertTrue($status->isFinal());
        foreach (FirStatusEnum::cases() as $to) {
            Assert::assertFalse($status->canTransitionTo($to));
        }
    })->with([
        [FirStatusEnum::ACCEPTED],
        [FirStatusEnum::PARTIALLY_ACCEPTED],
        [FirStatusEnum::REJECTED],
        [FirStatusEnum::CANCELLED],
    ]);

    test('every acceptance maps to its outcome status', function (): void {
        Assert::assertSame(FirStatusEnum::ACCEPTED, AcceptanceEnum::FULL->toFirStatus());
        Assert::assertSame(FirStatusEnum::PARTIALLY_ACCEPTED, AcceptanceEnum::PARTIAL->toFirStatus());
        Assert::assertSame(FirStatusEnum::REJECTED, AcceptanceEnum::REJECTED->toFirStatus());
        foreach (AcceptanceEnum::cases() as $acceptance) {
            Assert::assertTrue($acceptance->toFirStatus()->isAcceptanceOutcome());
        }
    });
});
