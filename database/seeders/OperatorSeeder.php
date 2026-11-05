<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Waste\Enums\OperatorRoleEnum;
use Modules\Waste\Models\Operator;
use Modules\Waste\Models\OperatorSite;

/**
 * Soggetti e unità locali DEMO (dati fittizi): l'impianto gestito, un produttore, un trasportatore.
 */
class OperatorSeeder extends Seeder
{
    public const string PLANT_OPERATOR_FISCAL_CODE = 'DEMO00000000001';

    public const string PLANT_SITE_NAME = 'Impianto demo di cernita e triturazione';

    public function run(): void
    {
        $plantOperator = $this->operator(
            fiscalCode: self::PLANT_OPERATOR_FISCAL_CODE,
            businessName: 'Impianto Demo S.r.l.',
            roles: [OperatorRoleEnum::RECIPIENT, OperatorRoleEnum::PRODUCER, OperatorRoleEnum::HOLDER],
        );
        $producer = $this->operator(
            fiscalCode: 'DEMO00000000002',
            businessName: 'Produttore Demo S.p.A.',
            roles: [OperatorRoleEnum::PRODUCER],
        );
        $this->operator(
            fiscalCode: 'DEMO00000000003',
            businessName: 'Trasporti Demo S.n.c.',
            roles: [OperatorRoleEnum::TRANSPORTER],
        );

        $this->site($plantOperator, self::PLANT_SITE_NAME, isPlant: true, address: [
            'route' => 'Via dell\'Artigianato',
            'street_number' => '1',
            'postal_code' => '00000',
            'locality' => 'Comune Demo',
            'country' => 'Italia',
        ]);
        $this->site($producer, 'Stabilimento demo', isPlant: false, address: [
            'route' => 'Via dell\'Industria',
            'street_number' => '2',
            'postal_code' => '00000',
            'locality' => 'Comune Demo',
            'country' => 'Italia',
        ]);
    }

    /**
     * @param  list<OperatorRoleEnum>  $roles
     */
    private function operator(string $fiscalCode, string $businessName, array $roles): Operator
    {
        return Operator::query()->updateOrCreate(
            ['fiscal_code' => $fiscalCode],
            [
                'business_name' => $businessName,
                'vat_number' => null,
                'roles' => $roles,
                'email' => null,
                'pec' => null,
                'is_active' => true,
            ],
        );
    }

    /**
     * @param  array<string, string>  $address
     */
    private function site(Operator $operator, string $name, bool $isPlant, array $address): OperatorSite
    {
        $site = OperatorSite::query()->updateOrCreate(
            ['operator_id' => $operator->id, 'name' => $name],
            ['is_plant' => $isPlant, 'is_active' => true],
        );

        if (! $site->addresses()->exists()) {
            $site->addAddress($address, true);
        }

        return $site;
    }
}
