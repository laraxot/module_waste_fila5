<?php

declare(strict_types=1);

namespace Modules\Waste\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\RegisterCauseEnum;
use Modules\Waste\Enums\RegisterEntryTypeEnum;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Exceptions\ImmutableRegisterEntryException;

/**
 * Scrittura del registro cronologico di carico e scarico.
 *
 * Regole di integrità (garantite qui, a livello di modello, oltre che dalle Actions):
 * - `register_id`, `year`, `number`, `recorded_at`, `rectifies_entry_id` non cambiano mai;
 * - dopo la trasmissione (`transmitted_at` valorizzato) la scrittura è immutabile;
 * - una scrittura non si cancella mai: si corregge con una rettifica (nuova scrittura con `rectifies_entry_id`).
 *
 * Il modulo Rentri marca la trasmissione salvando `transmitted_at` e `rentri_transmission_ref` nello stesso save
 * (gli update di massa via query builder aggirano questi controlli e non vanno usati).
 *
 * @property int $id
 * @property int $register_id
 * @property int $year
 * @property int $number
 * @property RegisterEntryTypeEnum $type
 * @property RegisterCauseEnum $cause
 * @property CarbonImmutable $recorded_at
 * @property CarbonImmutable $operation_at
 * @property int $ewc_code_id
 * @property PhysicalStateEnum $physical_state
 * @property Collection<int, HazardPropertyEnum> $hazard_properties
 * @property string $quantity_kg
 * @property WasteOperationEnum|null $operation
 * @property int|null $fir_id
 * @property int|null $counterpart_operator_id
 * @property string|null $reference_type
 * @property int|null $reference_id
 * @property list<int> $load_entry_ids
 * @property int|null $rectifies_entry_id
 * @property string|null $rectification_reason
 * @property string|null $notes
 * @property CarbonImmutable|null $transmitted_at
 * @property string|null $rentri_transmission_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read string $hazard_codes
 * @property-read Register $register
 * @property-read EwcCode $ewcCode
 * @property-read Fir|null $fir
 * @property-read Operator|null $counterpart
 * @property-read Model|null $reference
 * @property-read RegisterEntry|null $rectifiedEntry
 * @property-read RegisterEntry|null $rectification
 */
class RegisterEntry extends BaseModel
{
    /** Colonne che identificano la scrittura nel registro: mai modificabili, nemmeno prima della trasmissione. */
    public const array NUMBERING_COLUMNS = ['register_id', 'year', 'number', 'recorded_at', 'rectifies_entry_id'];

    /** @var list<string> */
    protected $fillable = [
        'register_id',
        'year',
        'number',
        'type',
        'cause',
        'recorded_at',
        'operation_at',
        'ewc_code_id',
        'physical_state',
        'hazard_properties',
        'quantity_kg',
        'operation',
        'fir_id',
        'counterpart_operator_id',
        'reference_type',
        'reference_id',
        'load_entry_ids',
        'rectifies_entry_id',
        'rectification_reason',
        'notes',
        'transmitted_at',
        'rentri_transmission_ref',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'hazard_properties' => '[]',
        'load_entry_ids' => '[]',
    ];

    /**
     * @return BelongsTo<Register, $this>
     */
    public function register(): BelongsTo
    {
        return $this->belongsTo(Register::class);
    }

    /**
     * @return BelongsTo<EwcCode, $this>
     */
    public function ewcCode(): BelongsTo
    {
        return $this->belongsTo(EwcCode::class);
    }

    /**
     * @return BelongsTo<Fir, $this>
     */
    public function fir(): BelongsTo
    {
        return $this->belongsTo(Fir::class);
    }

    /**
     * @return BelongsTo<Operator, $this>
     */
    public function counterpart(): BelongsTo
    {
        return $this->belongsTo(Operator::class, 'counterpart_operator_id');
    }

    /**
     * Oggetto di dominio che ha originato la scrittura (in Roosko: Delivery, ProcessingOrder, Shipment…).
     *
     * @return MorphTo<Model, $this>
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * La scrittura che questa rettifica.
     *
     * @return BelongsTo<RegisterEntry, $this>
     */
    public function rectifiedEntry(): BelongsTo
    {
        return $this->belongsTo(self::class, 'rectifies_entry_id');
    }

    /**
     * La rettifica che sostituisce questa scrittura (al più una).
     *
     * @return HasOne<RegisterEntry, $this>
     */
    public function rectification(): HasOne
    {
        return $this->hasOne(self::class, 'rectifies_entry_id');
    }

    /**
     * Scritture valide: esclude quelle sostituite da una rettifica.
     *
     * @param  Builder<RegisterEntry>  $query
     * @return Builder<RegisterEntry>
     */
    public function scopeEffective(Builder $query): Builder
    {
        return $query->whereDoesntHave('rectification');
    }

    public function isTransmitted(): bool
    {
        return $this->transmitted_at !== null;
    }

    public function isRectified(): bool
    {
        return $this->rectification()->exists();
    }

    public function quantityKg(): float
    {
        return (float) $this->quantity_kg;
    }

    /** Identificativo leggibile della scrittura: `{anno}/{numero}`. */
    public function displayNumber(): string
    {
        return $this->year.'/'.$this->number;
    }

    protected static function booted(): void
    {
        static::updating(static function (RegisterEntry $entry): void {
            $year = (int) $entry->getOriginal('year');
            $number = (int) $entry->getOriginal('number');

            if ($entry->getOriginal('transmitted_at') !== null) {
                throw ImmutableRegisterEntryException::transmitted($year, $number);
            }

            if ($entry->isDirty(self::NUMBERING_COLUMNS)) {
                throw ImmutableRegisterEntryException::numbering($year, $number);
            }
        });

        static::deleting(static function (RegisterEntry $entry): void {
            throw ImmutableRegisterEntryException::deletion($entry->year, $entry->number);
        });
    }

    /**
     * Codici HP separati da virgola (per export e viste compatte).
     *
     * @return Attribute<string, never>
     */
    protected function hazardCodes(): Attribute
    {
        return Attribute::get(fn (): string => $this->hazard_properties
            ->map(static fn (HazardPropertyEnum $property): string => $property->value)
            ->implode(', '));
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'register_id' => 'integer',
            'year' => 'integer',
            'number' => 'integer',
            'type' => RegisterEntryTypeEnum::class,
            'cause' => RegisterCauseEnum::class,
            'recorded_at' => 'immutable_datetime',
            'operation_at' => 'immutable_datetime',
            'ewc_code_id' => 'integer',
            'physical_state' => PhysicalStateEnum::class,
            'hazard_properties' => AsEnumCollection::of(HazardPropertyEnum::class),
            'quantity_kg' => 'decimal:3',
            'operation' => WasteOperationEnum::class,
            'fir_id' => 'integer',
            'counterpart_operator_id' => 'integer',
            'reference_id' => 'integer',
            'load_entry_ids' => 'array',
            'rectifies_entry_id' => 'integer',
            'transmitted_at' => 'immutable_datetime',
        ]);
    }
}
