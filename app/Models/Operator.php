<?php

declare(strict_types=1);

namespace Modules\Waste\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
>>>>>>> laraxot/dev
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Waste\Enums\OperatorRoleEnum;
<<<<<<< HEAD
=======
use Modules\Xot\Datas\XotData;
>>>>>>> laraxot/dev

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
<<<<<<< HEAD
=======
 * @property string|null $tenant_id cliente dell'area clienti
>>>>>>> laraxot/dev
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
<<<<<<< HEAD
=======
        'tenant_id',
>>>>>>> laraxot/dev
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'roles' => '[]',
        'is_active' => true,
    ];

    /**
<<<<<<< HEAD
=======
     * Cliente dell'area clienti: gli utenti di quel tenant vedono i cantieri, i FIR e i
     * conferimenti di questo soggetto. Unica fonte del legame soggetto-cliente.
     *
     * @return BelongsTo<Model, $this>
     */
    public function tenant(): BelongsTo
    {
        /** @var class-string<Model> $tenantClass */
        $tenantClass = XotData::make()->getTenantClass();

        return $this->belongsTo($tenantClass, 'tenant_id');
    }

    /**
     * @param  Builder<Operator>  $query
     * @return Builder<Operator>
     */
    public function scopeOfTenant(Builder $query, Model $tenant): Builder
    {
        return $query->where('tenant_id', $tenant->getKey());
    }

    /**
>>>>>>> laraxot/dev
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
