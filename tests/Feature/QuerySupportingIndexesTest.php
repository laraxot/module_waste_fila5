<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Modules\Waste\Models\RegisterEntry;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('the register usage index exists and the migration is idempotent', function (): void {
    $migration = require dirname(__DIR__, 2).'/database/migrations/2026_11_06_000009_add_query_supporting_indexes.php';

    if (! \is_object($migration) || ! method_exists($migration, 'up')) {
        throw new LogicException('La migrazione degli indici deve restituire un oggetto con up().');
    }

    $migration->up();
    $migration->up();

    Assert::assertTrue(Schema::connection('waste')->hasIndex((new RegisterEntry)->getTable(), ['ewc_code_id', 'type', 'operation_at']));
});
