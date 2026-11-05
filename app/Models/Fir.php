<?php

declare(strict_types=1);

namespace Modules\Waste\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Waste\Enums\AcceptanceEnum;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\QuantityUnitEnum;
use Modules\Waste\Enums\WasteOperationEnum;

/**
 * Formulario di identificazione del rifiuto (art. 193 D.Lgs. 152/2006), cartaceo o digitale (xFIR RENTRI).
 *
 * Si crea con CreateFirAction; stato e accettazione cambiano solo tramite ChangeFirStatusAction e
 * RecordFirAcceptanceAction. L'emissione digitale su RENTRI è del modulo Rentri.
 *
 * @property int $id
 * @property string|null $number
 * @property bool $is_digital
 * @property FirStatusEnum $status
 * @property int $producer_operator_id
 * @property int|null $producer_site_id
 * @property int|null $transporter_operator_id
 * @property int $recipient_operator_id
 * @property int|null $recipient_site_id
 * @property int|null $intermediary_operator_id
 * @property int $ewc_code_id
 * @property string|null $waste_description
 * @property PhysicalStateEnum $physical_state
 * @property Collection<int, HazardPropertyEnum> $hazard_properties
 * @property WasteOperationEnum $operation
 * @property string $quantity
 * @property QuantityUnitEnum $quantity_unit
 * @property bool $is_quantity_estimated
 * @property string|null $accepted_quantity_kg
 * @property AcceptanceEnum|null $acceptance
 * @property string|null $rejection_reason
 * @property string|null $vehicle_plate
 * @property string|null $trailer_plate
 * @property string|null $driver_name
 * @property string|null $adr_un_number
 * @property CarbonImmutable|null $transport_started_at
 * @property CarbonImmutable|null $arrived_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read Operator $producer
 * @property-read OperatorSite|null $producerSite
 * @property-read Operator|null $transporter
 * @property-read Operator $recipient
 * @property-read OperatorSite|null $recipientSite
 * @property-read Operator|null $intermediary
 * @property-read EwcCode $ewcCode
 * @property-read \Illuminate\Database\Eloquent\Collection<int, RegisterEntry> $registerEntries
 */
class Fir extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'number',
        'is_digital',
        'status',
        'producer_operator_id',
        'producer_site_id',
        'transporter_operator_id',
        'recipient_operator_id',
        'recipient_site_id',
        'intermediary_operator_id',
        'ewc_code_id',
        'waste_description',
        'physical_state',
        'hazard_properties',
        'operation',
        'quantity',
        'quantity_unit',
        'is_quantity_estimated',
        'accepted_quantity_kg',
        'acceptance',
        'rejection_reason',
        'vehicle_plate',
        'trailer_plate',
        'driver_name',
        'adr_un_number',
        'transport_started_at',
        'arrived_at',
        'notes',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'is_digital' => false,
        'status' => 'draft',
        'hazard_properties' => '[]',
        'quantity_unit' => 'kg',
        'is_quantity_estimated' => false,
    ];

    /**
     * @return BelongsTo<Operator, $this>
     */
    public function producer(): BelongsTo
    {
        return $this->belongsTo(Operator::class, 'producer_operator_id');
    }

    /**
     * @return BelongsTo<OperatorSite, $this>
     */
    public function producerSite(): BelongsTo
    {
        return $this->belongsTo(OperatorSite::class, 'producer_site_id');
    }

    /**
     * @return BelongsTo<Operator, $this>
     */
    public function transporter(): BelongsTo
    {
        return $this->belongsTo(Operator::class, 'transporter_operator_id');
    }

    /**
     * @return BelongsTo<Operator, $this>
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Operator::class, 'recipient_operator_id');
    }

    /**
     * @return BelongsTo<OperatorSite, $this>
     */
    public function recipientSite(): BelongsTo
    {
        return $this->belongsTo(OperatorSite::class, 'recipient_site_id');
    }

    /**
     * @return BelongsTo<Operator, $this>
     */
    public function intermediary(): BelongsTo
    {
        return $this->belongsTo(Operator::class, 'intermediary_operator_id');
    }

    /**
     * @return BelongsTo<EwcCode, $this>
     */
    public function ewcCode(): BelongsTo
    {
        return $this->belongsTo(EwcCode::class);
    }

    /**
     * @return HasMany<RegisterEntry, $this>
     */
    public function registerEntries(): HasMany
    {
        return $this->hasMany(RegisterEntry::class);
    }

    /** Numero del formulario o, finché non è assegnato, l'identificativo interno. */
    public function displayNumber(): string
    {
        return $this->number ?? '#'.$this->id;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_digital' => 'boolean',
            'status' => FirStatusEnum::class,
            'producer_operator_id' => 'integer',
            'producer_site_id' => 'integer',
            'transporter_operator_id' => 'integer',
            'recipient_operator_id' => 'integer',
            'recipient_site_id' => 'integer',
            'intermediary_operator_id' => 'integer',
            'ewc_code_id' => 'integer',
            'physical_state' => PhysicalStateEnum::class,
            'hazard_properties' => AsEnumCollection::of(HazardPropertyEnum::class),
            'operation' => WasteOperationEnum::class,
            'quantity' => 'decimal:3',
            'quantity_unit' => QuantityUnitEnum::class,
            'is_quantity_estimated' => 'boolean',
            'accepted_quantity_kg' => 'decimal:3',
            'acceptance' => AcceptanceEnum::class,
            'transport_started_at' => 'immutable_datetime',
            'arrived_at' => 'immutable_datetime',
        ]);
    }
}
