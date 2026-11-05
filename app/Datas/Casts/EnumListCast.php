<?php

declare(strict_types=1);

namespace Modules\Waste\Datas\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

/**
 * Converte una lista di valori (stringhe dal form, enum già istanziati) in `list<TEnum>`.
 * Necessario perché spatie/laravel-data non casta gli array di enum se la feature
 * `cast_and_transform_iterables` è disattivata (default).
 */
final readonly class EnumListCast implements Cast
{
    /**
     * @param  class-string<\BackedEnum>  $enumClass
     */
    public function __construct(private string $enumClass) {}

    /**
     * @param  array<string, mixed>  $properties
     * @return list<\BackedEnum>|Uncastable
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): array|Uncastable
    {
        if (! is_iterable($value)) {
            return Uncastable::create();
        }

        $enumClass = $this->enumClass;
        $result = [];
        foreach ($value as $item) {
            if ($item instanceof $enumClass) {
                $result[] = $item;

                continue;
            }
            if (is_string($item) || is_int($item)) {
                $result[] = $enumClass::from($item);
            }
        }

        return $result;
    }
}
