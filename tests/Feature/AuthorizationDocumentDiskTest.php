<?php

declare(strict_types=1);

use Modules\Waste\Models\PlantAuthorization;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('authorization documents default to the private local disk, never public', function (): void {
    config(['waste.authorization_document.disk' => null]);

    Assert::assertSame('local', PlantAuthorization::documentDisk());

    $authorization = PlantAuthorization::factory()->createOne();
    $authorization->registerMediaCollections();
    $collection = collect($authorization->mediaCollections)->firstWhere('name', PlantAuthorization::DOCUMENT_COLLECTION);

    Assert::assertNotNull($collection);
    Assert::assertSame('local', $collection->diskName);
});

test('the module default configuration is the private disk', function (): void {
    $config = require dirname(__DIR__, 2).'/config/config.php';

    Assert::assertSame('local', data_get($config, 'authorization_document.disk'));
});

test('a configured disk is respected', function (): void {
    config(['waste.authorization_document.disk' => 's3-private']);

    Assert::assertSame('s3-private', PlantAuthorization::documentDisk());
});
