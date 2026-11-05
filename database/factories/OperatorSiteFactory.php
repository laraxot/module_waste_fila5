<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Waste\Models\Operator;
use Modules\Waste\Models\OperatorSite;

/**
 * @extends Factory<OperatorSite>
 */
class OperatorSiteFactory extends Factory
{
    /** @var class-string<OperatorSite> */
    protected $model = OperatorSite::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'operator_id' => Operator::factory(),
            'name' => $this->faker->city().' – '.$this->faker->streetName(),
            'rentri_site_number' => null,
            'is_plant' => false,
            'is_active' => true,
        ];
    }

    public function plant(): static
    {
        return $this->state(fn (array $_attributes): array => ['is_plant' => true]);
    }
}
