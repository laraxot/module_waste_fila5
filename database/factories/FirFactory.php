<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Enums\OperatorRoleEnum;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\QuantityUnitEnum;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\Fir;

/**
 * Crea il record direttamente (stato a scelta): per i test. In applicazione usare CreateFirAction.
 *
 * @extends Factory<Fir>
 */
class FirFactory extends Factory
{
    /** @var class-string<Fir> */
    protected $model = Fir::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->unique()->bothify('???######??'),
            'is_digital' => false,
            'status' => FirStatusEnum::DRAFT,
            'producer_operator_id' => OperatorFactory::new()->withRoles(OperatorRoleEnum::PRODUCER),
            'recipient_operator_id' => OperatorFactory::new()->withRoles(OperatorRoleEnum::RECIPIENT),
            'transporter_operator_id' => OperatorFactory::new()->withRoles(OperatorRoleEnum::TRANSPORTER),
            'ewc_code_id' => EwcCode::factory(),
            'physical_state' => PhysicalStateEnum::SOLID_NON_POWDERY,
            'hazard_properties' => [],
            'operation' => WasteOperationEnum::R13,
            'quantity' => $this->faker->randomFloat(3, 100, 20000),
            'quantity_unit' => QuantityUnitEnum::KG,
            'is_quantity_estimated' => true,
            'vehicle_plate' => strtoupper($this->faker->bothify('??###??')),
        ];
    }

    public function status(FirStatusEnum $status): static
    {
        return $this->state(fn (array $_attributes): array => ['status' => $status]);
    }
}
