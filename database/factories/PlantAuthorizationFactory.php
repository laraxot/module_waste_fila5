<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Waste\Enums\AuthorizationTypeEnum;
use Modules\Waste\Models\PlantAuthorization;

/**
 * @extends Factory<PlantAuthorization>
 */
class PlantAuthorizationFactory extends Factory
{
    /** @var class-string<PlantAuthorization> */
    protected $model = PlantAuthorization::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'operator_site_id' => OperatorSiteFactory::new()->plant(),
            'type' => AuthorizationTypeEnum::ORDINARY_208,
            'number' => $this->faker->numerify('AUT/####/'.now()->subYear()->year),
            'issuing_authority' => 'Provincia di '.$this->faker->city(),
            'issued_at' => now()->subYear()->toDateString(),
            'expires_at' => now()->addYears(9)->toDateString(),
            'is_active' => true,
            'notes' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $_attributes): array => [
            'issued_at' => now()->subYears(11)->toDateString(),
            'expires_at' => now()->subDay()->toDateString(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $_attributes): array => ['is_active' => false]);
    }
}
