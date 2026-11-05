<?php

declare(strict_types=1);

namespace Modules\Waste\Datas;

use Carbon\CarbonImmutable;
use Modules\Waste\Datas\Casts\EnumListCast;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\QuantityUnitEnum;
use Modules\Waste\Enums\WasteOperationEnum;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * Dati per creare un formulario. `status` iniziale: draft (FIR da emettere), issued o in_transit
 * (inserimento di un formulario cartaceo già compilato dal produttore).
 * `from()` accetta chiavi snake_case (colonne / form Filament).
 */
#[MapInputName(SnakeCaseMapper::class)]
final class FirData extends Data
{
    /**
     * @param  list<HazardPropertyEnum>  $hazardProperties
     */
    public function __construct(
        public int $producerOperatorId,
        public int $recipientOperatorId,
        public int $ewcCodeId,
        public PhysicalStateEnum $physicalState,
        public WasteOperationEnum $operation,
        public float $quantity,
        public QuantityUnitEnum $quantityUnit = QuantityUnitEnum::KG,
        public bool $isQuantityEstimated = false,
        #[WithCast(EnumListCast::class, HazardPropertyEnum::class)]
        public array $hazardProperties = [],
        public ?string $number = null,
        public bool $isDigital = false,
        public FirStatusEnum $status = FirStatusEnum::DRAFT,
        public ?int $producerSiteId = null,
        public ?int $transporterOperatorId = null,
        public ?int $recipientSiteId = null,
        public ?int $intermediaryOperatorId = null,
        public ?string $wasteDescription = null,
        public ?string $vehiclePlate = null,
        public ?string $trailerPlate = null,
        public ?string $driverName = null,
        public ?string $adrUnNumber = null,
        #[WithCast(DateTimeInterfaceCast::class, format: [DATE_ATOM, 'Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d\TH:i:s.u\Z', 'Y-m-d\TH:i'])]
        public ?CarbonImmutable $transportStartedAt = null,
        public ?string $notes = null,
    ) {}
}
