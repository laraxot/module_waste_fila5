<?php

declare(strict_types=1);

namespace Modules\Waste\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Geo\Models\Traits\HasAddress;

/**
 * Unità locale di un soggetto (sede operativa / impianto). L'indirizzo è un `Address` di Geo
 * (relazione polimorfica `model`), gestito con il trait HasAddress.
 *
 * @property int $id
 * @property int $operator_id
 * @property string $name
 * @property string|null $rentri_site_number
 * @property bool $is_plant
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property-read Operator $operator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PlantAuthorization> $plantAuthorizations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Register> $registers
 */
class OperatorSite extends BaseModel
{
    /** @use HasAddress<OperatorSite> */
    use HasAddress;
    use SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'operator_id',
        'name',
        'rentri_site_number',
        'is_plant',
        'is_active',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'is_plant' => false,
        'is_active' => true,
    ];

    /**
     * @return BelongsTo<Operator, $this>
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * @return HasMany<PlantAuthorization, $this>
     */
    public function plantAuthorizations(): HasMany
    {
        return $this->hasMany(PlantAuthorization::class);
    }

    /**
     * @return HasMany<Register, $this>
     */
    public function registers(): HasMany
    {
        return $this->hasMany(Register::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'operator_id' => 'integer',
            'is_plant' => 'boolean',
            'is_active' => 'boolean',
        ]);
    }
}
