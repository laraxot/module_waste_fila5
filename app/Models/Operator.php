<?php

declare(strict_types=1);

namespace Modules\Waste\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Waste\Enums\OperatorRoleEnum;

/**
 * Soggetto della filiera rifiuti (produttore, trasportatore, destinatario, …), identificato dal codice fiscale.
 *
 * @property int $id
 * @property string $business_name
 * @property string $fiscal_code
 * @property string|null $vat_number
 * @property string|null $rentri_registration_number
 * @property string|null $albo_registration_number
 * @property Collection<int, OperatorRoleEnum> $roles
 * @property string|null $email
 * @property string|null $pec
 * @property string|null $phone
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OperatorSite> $sites
 */
class Operator extends BaseModel
{
    use SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'business_name',
        'fiscal_code',
        'vat_number',
        'rentri_registration_number',
        'albo_registration_number',
        'roles',
        'email',
        'pec',
        'phone',
        'is_active',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'roles' => '[]',
        'is_active' => true,
    ];

    /**
     * @return HasMany<OperatorSite, $this>
     */
    public function sites(): HasMany
    {
        return $this->hasMany(OperatorSite::class);
    }

    public function playsRole(OperatorRoleEnum $role): bool
    {
        return $this->roles->contains(static fn (OperatorRoleEnum $item): bool => $item === $role);
    }

    /**
     * @param  Builder<Operator>  $query
     * @return Builder<Operator>
     */
    public function scopeWithRole(Builder $query, OperatorRoleEnum $role): Builder
    {
        return $query->whereJsonContains('roles', $role->value);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'roles' => AsEnumCollection::of(OperatorRoleEnum::class),
            'is_active' => 'boolean',
        ]);
    }
}
