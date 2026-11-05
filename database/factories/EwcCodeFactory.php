<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Waste\Models\EwcCode;

/**
 * Codici fittizi nel formato EER valido (capitoli 01-20, sottocapitoli 90-99): servono ai test, non sono voci dell'elenco.
 * Per i dati reali usare EwcCodeSeeder o ImportEwcCodesFromCsvAction.
 *
 * @extends Factory<EwcCode>
 */
class EwcCodeFactory extends Factory
{
    /** @var class-string<EwcCode> */
    protected $model = EwcCode::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Sottocapitoli 90-99: non esistono nell'elenco EER, così i codici fittizi non collidono con quelli reali.
        $seq = $this->faker->unique()->numberBetween(0, 20 * 10 * 99 - 1);
        $code = sprintf('%02d %02d %02d', intdiv($seq, 10 * 99) + 1, intdiv($seq % (10 * 99), 99) + 90, $seq % 99 + 1);

        return [
            'code' => $code,
            'description' => $this->faker->sentence(6),
            'is_hazardous' => false,
            'is_mirror' => false,
            'is_active' => true,
        ];
    }

    public function hazardous(): static
    {
        return $this->state(fn (array $_attributes): array => ['is_hazardous' => true]);
    }

    public function mirror(): static
    {
        return $this->state(fn (array $_attributes): array => ['is_mirror' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $_attributes): array => ['is_active' => false]);
    }
}
