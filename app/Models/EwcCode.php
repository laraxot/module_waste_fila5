<?php

declare(strict_types=1);

namespace Modules\Waste\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Waste\Actions\NormalizeEwcCodeAction;

/**
 * Voce dell'Elenco Europeo dei Rifiuti (Decisione 2000/532/CE come sostituita dalla Decisione 2014/955/UE).
 *
 * `code` è sempre nel formato normalizzato `'19 12 04'` (senza asterisco); la pericolosità è `is_hazardous`.
 * `chapter` e `sub_chapter` sono derivati dal codice in salvataggio, non si impostano a mano.
 *
 * @property int $id
 * @property string $code
 * @property string $chapter
 * @property string $sub_chapter
 * @property string $description
 * @property bool $is_hazardous
 * @property bool $is_mirror
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read string $display_code
 */
class EwcCode extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'code',
        'description',
        'is_hazardous',
        'is_mirror',
        'is_active',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'is_hazardous' => false,
        'is_mirror' => false,
        'is_active' => true,
    ];

    /**
     * @return HasMany<AuthorizedWaste, $this>
     */
    public function authorizedWastes(): HasMany
    {
        return $this->hasMany(AuthorizedWaste::class);
    }

    /**
     * @return HasMany<Fir, $this>
     */
    public function firs(): HasMany
    {
        return $this->hasMany(Fir::class);
    }

    /**
     * @return HasMany<RegisterEntry, $this>
     */
    public function registerEntries(): HasMany
    {
        return $this->hasMany(RegisterEntry::class);
    }

    /**
     * @param  Builder<EwcCode>  $query
     * @return Builder<EwcCode>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    protected static function booted(): void
    {
        static::saving(static function (EwcCode $ewcCode): void {
            $code = app(NormalizeEwcCodeAction::class)->execute($ewcCode->code);
            $ewcCode->code = $code;
            $ewcCode->chapter = substr($code, 0, 2);
            $ewcCode->sub_chapter = substr($code, 0, 5);
        });
    }

    /**
     * Codice come stampato su FIR ed etichette: con asterisco se pericoloso (`16 01 07*`).
     *
     * @return Attribute<string, never>
     */
    protected function displayCode(): Attribute
    {
        return Attribute::get(fn (): string => $this->code.($this->is_hazardous ? '*' : ''));
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_hazardous' => 'boolean',
            'is_mirror' => 'boolean',
            'is_active' => 'boolean',
        ]);
    }
}
