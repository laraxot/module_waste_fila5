<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Models\AuthorizedWaste;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\PlantAuthorization;

/**
 * @extends Factory<AuthorizedWaste>
 */
class AuthorizedWasteFactory extends Factory
{
    /** @var class-string<AuthorizedWaste> */
    protected $model = AuthorizedWaste::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plant_authorization_id' => PlantAuthorization::factory(),
            'ewc_code_id' => EwcCode::factory(),
            'operations' => [WasteOperationEnum::R13, WasteOperationEnum::R3],
            'max_storage_kg' => 100000,
            'max_annual_kg' => 1000000,
            'notes' => null,
        ];
    }

    public function withOperations(WasteOperationEnum ...$operations): static
    {
        return $this->state(fn (array $_attributes): array => ['operations' => $operations]);
    }
}
