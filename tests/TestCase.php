<?php

declare(strict_types=1);

namespace Modules\Waste\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Geo\Providers\GeoServiceProvider;
use Modules\Waste\Providers\WasteServiceProvider;
use Modules\Waste\Tests\Support\WasteFixtures;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base dei test del modulo Waste: sqlite condiviso (come Activity), ogni test in transazione.
 * Se le tabelle del modulo non esistono ancora le crea dalle migrazioni del modulo.
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['sqlite', 'waste', 'geo', 'user'];

    protected function setUp(): void
    {
        if ($this->app === null) {
            $this->refreshApplication();
        }
        $this->ensureModuleConnections();
        $this->prepareSharedSqliteForTesting();

        parent::setUp();

        WasteFixtures::ensureSchema();
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            GeoServiceProvider::class,
            WasteServiceProvider::class,
        ];
    }

    /** Le connessioni `waste` e `geo` ricadono su sqlite se l'applicazione di test non le definisce. */
    private function ensureModuleConnections(): void
    {
        $sqlite = config('database.connections.sqlite');
        if (! is_array($sqlite)) {
            return;
        }

        foreach (['waste', 'geo'] as $connection) {
            if (config('database.connections.'.$connection) === null) {
                config(['database.connections.'.$connection => $sqlite]);
            }
        }
    }
}
