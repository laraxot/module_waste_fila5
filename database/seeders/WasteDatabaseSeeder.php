<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Orchestratore Waste: catalogo EER di base, poi dati demo (soggetti, impianto, autorizzazione, registro).
 */
class WasteDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('WasteDatabaseSeeder: codici EER e dati demo…');

        $this->call([
            EwcCodeSeeder::class,
            OperatorSeeder::class,
            PlantAuthorizationSeeder::class,
            RegisterSeeder::class,
        ]);

        $this->command?->info('WasteDatabaseSeeder: completato.');
    }
}
