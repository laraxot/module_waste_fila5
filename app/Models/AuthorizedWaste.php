<?php

declare(strict_types=1);

namespace Modules\Waste\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Waste\Enums\WasteOperationEnum;

/**
 * Codice EER autorizzato in un titolo, con le operazioni R/D ammesse e i limiti quantitativi
 * (`max_storage_kg` istantaneo, `max_annual_kg` annuo), entrambi opzionali.
 *
 * Un codice autorizzato vale solo se il suo titolo è valido alla data dell'operazione (attivo, già
 * rilasciato, non scaduto): `isValidOn()` / scope `validOn()`. Con più titoli per lo stesso EER (rinnovo)
 * i limiti NON si sommano: vale il titolo valido più recente (FindAuthorizedWasteAction).
 *
 * @property int $id
 * @property int $plant_authorization_id
 * @property int $ewc_code_id
 * @property Collection<int, WasteOperationEnum> $operations
 * @property string|null $max_storage_kg
 * @property string|null $max_annual_kg
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property-read PlantAuthorization $plantAuthorization
 * @property-read EwcCode $ewcCode
 */
class AuthorizedWaste extends BaseModel
{
    use SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'plant_authorization_id',
        'ewc_code_id',
        'operations',
        'max_storage_kg',
        'max_annual_kg',
        'notes',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'operations' => '[]',
    ];

    /**
     * @return BelongsTo<PlantAuthorization, $this>
     */
    public function plantAuthorization(): BelongsTo
    {
        return $this->belongsTo(PlantAuthorization::class);
    }

    /**
     * @return BelongsTo<EwcCode, $this>
     */
    public function ewcCode(): BelongsTo
    {
        return $this->belongsTo(EwcCode::class);
    }

    public function allowsOperation(WasteOperationEnum $operation): bool
    {
        return $this->operations->contains(static fn (WasteOperationEnum $item): bool => $item === $operation);
    }

    /** Il titolo di appartenenza è valido alla data indicata (PlantAuthorization::isValidOn). */
    public function isValidOn(CarbonInterface $date): bool
    {
        return $this->plantAuthorization->isValidOn($date);
    }

    /**
     * Solo i codici di titoli validi alla data indicata (titolo attivo, rilasciato, non scaduto, non eliminato).
     *
     * @param  Builder<AuthorizedWaste>  $query
     * @return Builder<AuthorizedWaste>
     */
    public function scopeValidOn(Builder $query, CarbonInterface $date): Builder
    {
        return $query->whereHas('plantAuthorization', static function (Builder $authorization) use ($date): void {
            /** @var Builder<PlantAuthorization> $authorization */
            (new PlantAuthorization)->scopeValidOn($authorization, $date);
        });
    }

    public function maxStorageKg(): ?float
    {
        return $this->max_storage_kg === null ? null : (float) $this->max_storage_kg;
    }

    public function maxAnnualKg(): ?float
    {
        return $this->max_annual_kg === null ? null : (float) $this->max_annual_kg;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'plant_authorization_id' => 'integer',
            'ewc_code_id' => 'integer',
            'operations' => AsEnumCollection::of(WasteOperationEnum::class),
            'max_storage_kg' => 'decimal:3',
            'max_annual_kg' => 'decimal:3',
        ]);
    }
}
