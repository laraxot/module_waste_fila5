<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\RegisterCauseEnum;
use Modules\Waste\Enums\RegisterEntryTypeEnum;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\Register;
use Modules\Waste\Models\RegisterEntry;

/**
 * Inserisce la scrittura senza passare dalla numerazione sotto lock: solo per test e demo.
 * In applicazione usare PostRegisterEntryAction.
 *
 * @extends Factory<RegisterEntry>
 */
class RegisterEntryFactory extends Factory
{
    /** @var class-string<RegisterEntry> */
    protected $model = RegisterEntry::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'register_id' => Register::factory(),
            'year' => (int) now()->year,
            'number' => $this->faker->unique()->numberBetween(100000, 999999),
            'type' => RegisterEntryTypeEnum::LOAD,
            'cause' => RegisterCauseEnum::NEW_PRODUCTION,
            'recorded_at' => now(),
            'operation_at' => now(),
            'ewc_code_id' => EwcCode::factory(),
            'physical_state' => PhysicalStateEnum::SOLID_NON_POWDERY,
            'hazard_properties' => [],
            'quantity_kg' => $this->faker->randomFloat(3, 10, 5000),
            'load_entry_ids' => [],
        ];
    }
}
