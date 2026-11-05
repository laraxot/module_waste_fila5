<?php

declare(strict_types=1);

namespace Modules\Waste\Datas;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Modules\Waste\Datas\Casts\EnumListCast;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\RegisterCauseEnum;
use Modules\Waste\Enums\RegisterEntryTypeEnum;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Models\RegisterEntry;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * Dati di una scrittura del registro (carico o scarico).
 *
 * `operationAt` è dichiarato subito dopo i campi obbligatori (non dopo `counterpartOperatorId` come nell'elenco
 * della SPEC) perché un parametro obbligatorio dopo parametri opzionali è deprecato in PHP 8: costruire sempre
 * con argomenti nominati. `from()` accetta sia camelCase sia snake_case (chiavi dei form Filament).
 */
#[MapInputName(SnakeCaseMapper::class)]
final class RegisterEntryData extends Data
{
    /**
     * @param  list<HazardPropertyEnum>  $hazardProperties
     * @param  list<int>  $loadEntryIds  per gli scarichi: i carichi da cui proviene il rifiuto
     */
    public function __construct(
        public int $registerId,
        public RegisterEntryTypeEnum $type,
        public RegisterCauseEnum $cause,
        public int $ewcCodeId,
        public float $quantityKg,
        public PhysicalStateEnum $physicalState,
        #[WithCast(DateTimeInterfaceCast::class, format: [DATE_ATOM, 'Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d\TH:i:s.u\Z', 'Y-m-d\TH:i'])]
        public CarbonImmutable $operationAt,
        #[WithCast(EnumListCast::class, HazardPropertyEnum::class)]
        public array $hazardProperties = [],
        public ?WasteOperationEnum $operation = null,
        public ?int $firId = null,
        public ?int $counterpartOperatorId = null,
        public ?Model $reference = null,
        public array $loadEntryIds = [],
        public ?string $notes = null,
    ) {}

    /** Dati di una scrittura esistente: base di partenza per una rettifica. */
    public static function fromEntry(RegisterEntry $entry): self
    {
        $reference = $entry->reference_type !== null ? $entry->reference : null;

        return new self(
            registerId: $entry->register_id,
            type: $entry->type,
            cause: $entry->cause,
            ewcCodeId: $entry->ewc_code_id,
            quantityKg: $entry->quantityKg(),
            physicalState: $entry->physical_state,
            operationAt: $entry->operation_at,
            hazardProperties: array_values($entry->hazard_properties->all()),
            operation: $entry->operation,
            firId: $entry->fir_id,
            counterpartOperatorId: $entry->counterpart_operator_id,
            reference: $reference,
            loadEntryIds: array_values(array_map(intval(...), $entry->load_entry_ids)),
            notes: $entry->notes,
        );
    }

    /**
     * @return list<int>
     */
    public function normalizedLoadEntryIds(): array
    {
        return array_values(array_unique(array_map(intval(...), $this->loadEntryIds)));
    }
}
