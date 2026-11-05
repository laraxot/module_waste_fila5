<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Waste\Enums\OperatorRoleEnum;
use Modules\Waste\Models\Operator;

/**
 * @extends Factory<Operator>
 */
class OperatorFactory extends Factory
{
    /** @var class-string<Operator> */
    protected $model = Operator::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_name' => $this->faker->company(),
            'fiscal_code' => $this->faker->unique()->numerify('###########'),
            'vat_number' => $this->faker->numerify('IT###########'),
            'rentri_registration_number' => null,
            'albo_registration_number' => null,
            'roles' => [OperatorRoleEnum::PRODUCER],
            'email' => $this->faker->safeEmail(),
            'pec' => $this->faker->safeEmail(),
            'phone' => $this->faker->numerify('0## #######'),
            'is_active' => true,
        ];
    }

    public function withRoles(OperatorRoleEnum ...$roles): static
    {
        return $this->state(fn (array $_attributes): array => ['roles' => $roles]);
    }
}
