<?php

declare(strict_types=1);

namespace Modules\Waste\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Registro cronologico di carico e scarico di un'unità locale (art. 190 D.Lgs. 152/2006, DM 59/2023).
 * Le scritture si aggiungono solo con PostRegisterEntryAction / RectifyRegisterEntryAction.
 *
 * @property int $id
 * @property int $operator_site_id
 * @property string $name
 * @property string|null $rentri_identifier
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read OperatorSite $site
 * @property-read \Illuminate\Database\Eloquent\Collection<int, RegisterEntry> $entries
 */
class Register extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'operator_site_id',
        'name',
        'rentri_identifier',
        'is_active',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * @return BelongsTo<OperatorSite, $this>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(OperatorSite::class, 'operator_site_id');
    }

    /**
     * @return HasMany<RegisterEntry, $this>
     */
    public function entries(): HasMany
    {
        return $this->hasMany(RegisterEntry::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'operator_site_id' => 'integer',
            'is_active' => 'boolean',
        ]);
    }
}
