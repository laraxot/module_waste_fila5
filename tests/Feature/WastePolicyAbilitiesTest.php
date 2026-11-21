<?php

declare(strict_types=1);

use Modules\Waste\Database\Factories\FirFactory;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Models\Policies\AuthorizedWastePolicy;
use Modules\Waste\Models\Policies\EwcCodePolicy;
use Modules\Waste\Models\Policies\FirPolicy;
use Modules\Waste\Models\Policies\OperatorPolicy;
use Modules\Waste\Models\Policies\OperatorSitePolicy;
use Modules\Waste\Models\Policies\PlantAuthorizationPolicy;
use Modules\Waste\Models\Policies\RegisterEntryPolicy;
use Modules\Waste\Models\Policies\RegisterPolicy;
use Modules\Waste\Tests\TestCase;
use Modules\Xot\Contracts\UserContract;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('every Waste policy denies the Filament abilities it does not declare', function (string $policy): void {
    /** @var UserContract&Mockery\MockInterface $user */
    $user = Mockery::mock(UserContract::class);
    $instance = new $policy;
    if (! method_exists($instance, 'deleteAny') || ! method_exists($instance, 'forceDeleteAny') || ! method_exists($instance, 'restoreAny') || ! method_exists($instance, 'reorder')) {
        throw new LogicException($policy.' non usa DeniesUndeclaredFilamentAbilities');
    }

    Assert::assertFalse($instance->deleteAny($user));
    Assert::assertFalse($instance->forceDeleteAny($user));
    Assert::assertFalse($instance->reorder($user));
})->with([
    AuthorizedWastePolicy::class,
    EwcCodePolicy::class,
    FirPolicy::class,
    OperatorPolicy::class,
    OperatorSitePolicy::class,
    PlantAuthorizationPolicy::class,
    RegisterEntryPolicy::class,
    RegisterPolicy::class,
]);

test('importing EWC codes needs ewc_code.import, not viewAny', function (): void {
    /** @var UserContract&Mockery\MockInterface $user */
    $user = Mockery::mock(UserContract::class);
    $user->shouldReceive('hasPermissionTo')->with('ewc_code.import')->once()->andReturn(false);

    Assert::assertFalse((new EwcCodePolicy)->import($user));
});

test('recording the acceptance needs fir.record_acceptance and an open form', function (): void {
    /** @var UserContract&Mockery\MockInterface $user */
    $user = Mockery::mock(UserContract::class);
    $user->shouldReceive('hasPermissionTo')->with('fir.record_acceptance')->andReturn(true);
    $policy = new FirPolicy;

    Assert::assertTrue($policy->recordAcceptance($user, FirFactory::new()->status(FirStatusEnum::DELIVERED)->makeOne()));
    Assert::assertFalse($policy->recordAcceptance($user, FirFactory::new()->status(FirStatusEnum::DRAFT)->makeOne()));
    Assert::assertFalse($policy->recordAcceptance($user, FirFactory::new()->status(FirStatusEnum::ACCEPTED)->makeOne()));
});
