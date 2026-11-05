<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Waste\Models\Register;

/**
 * @extends Factory<Register>
 */
class RegisterFactory extends Factory
{
    /** @var class-string<Register> */
    protected $model = Register::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'operator_site_id' => OperatorSiteFactory::new()->plant(),
            'name' => 'Registro '.$this->faker->word(),
            'rentri_identifier' => null,
            'is_active' => true,
        ];
    }
}
