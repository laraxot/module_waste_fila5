<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Waste\Models\OperatorSite;
use Modules\Waste\Models\Register;

/**
 * Registro di carico e scarico DEMO dell'impianto (senza scritture: si annotano con PostRegisterEntryAction).
 */
class RegisterSeeder extends Seeder
{
    public function run(): void
    {
        $plant = OperatorSite::query()->where('name', OperatorSeeder::PLANT_SITE_NAME)->first();
        if (! $plant instanceof OperatorSite) {
            return;
        }

        Register::query()->firstOrCreate(
            ['operator_site_id' => $plant->id, 'name' => 'Registro impianto demo'],
            ['is_active' => true],
        );
    }
}
