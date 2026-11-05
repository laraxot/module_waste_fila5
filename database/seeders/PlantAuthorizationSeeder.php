<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Waste\Enums\AuthorizationTypeEnum;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\OperatorSite;
use Modules\Waste\Models\PlantAuthorization;

/**
 * Autorizzazione DEMO (art. 208, dati fittizi) dell'impianto creato da OperatorSeeder, con alcuni codici autorizzati.
 * Richiede EwcCodeSeeder e OperatorSeeder.
 */
class PlantAuthorizationSeeder extends Seeder
{
    /**
     * codice EER => [operazioni, max stoccaggio istantaneo kg, max annuo kg]
     *
     * @var array<string, array{0: list<WasteOperationEnum>, 1: float, 2: float}>
     */
    private const array AUTHORIZED = [
        '15 01 01' => [[WasteOperationEnum::R13, WasteOperationEnum::R3], 60000.0, 2000000.0],
        '15 01 02' => [[WasteOperationEnum::R13, WasteOperationEnum::R3, WasteOperationEnum::R12], 60000.0, 2000000.0],
        '15 01 06' => [[WasteOperationEnum::R13, WasteOperationEnum::R12], 80000.0, 3000000.0],
        '17 09 04' => [[WasteOperationEnum::R13, WasteOperationEnum::R5, WasteOperationEnum::R12], 200000.0, 10000000.0],
        '19 12 04' => [[WasteOperationEnum::R13, WasteOperationEnum::R3], 50000.0, 1500000.0],
        '19 12 12' => [[WasteOperationEnum::R13, WasteOperationEnum::R12], 100000.0, 4000000.0],
        '16 01 07' => [[WasteOperationEnum::R13], 5000.0, 60000.0],
        '20 01 35' => [[WasteOperationEnum::R13, WasteOperationEnum::R12], 20000.0, 300000.0],
    ];

    public function run(): void
    {
        $plant = OperatorSite::query()->where('name', OperatorSeeder::PLANT_SITE_NAME)->first();
        if (! $plant instanceof OperatorSite) {
            return;
        }

        $authorization = PlantAuthorization::query()->updateOrCreate(
            ['operator_site_id' => $plant->id, 'number' => 'DEMO-208/2024'],
            [
                'type' => AuthorizationTypeEnum::ORDINARY_208,
                'issuing_authority' => 'Autorità competente demo',
                'issued_at' => '2024-01-15',
                'expires_at' => '2034-01-14',
                'is_active' => true,
                'notes' => 'Autorizzazione fittizia per ambiente dimostrativo.',
            ],
        );

        foreach (self::AUTHORIZED as $code => [$operations, $maxStorageKg, $maxAnnualKg]) {
            $ewcCode = EwcCode::query()->where('code', $code)->first();
            if (! $ewcCode instanceof EwcCode) {
                continue;
            }

            $authorization->authorizedWastes()->updateOrCreate(
                ['ewc_code_id' => $ewcCode->id],
                [
                    'operations' => $operations,
                    'max_storage_kg' => $maxStorageKg,
                    'max_annual_kg' => $maxAnnualKg,
                ],
            );
        }
    }
}
