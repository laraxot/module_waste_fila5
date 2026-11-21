<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Carbon\CarbonImmutable;
use Modules\Waste\Datas\FirData;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\OperatorRoleEnum;
use Modules\Waste\Exceptions\InvalidEwcCodeException;
use Modules\Waste\Exceptions\InvalidFirException;
use Modules\Waste\Exceptions\InvalidFirStatusTransitionException;
use Modules\Waste\Exceptions\InvalidQuantityException;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\Fir;
use Modules\Waste\Models\Operator;
use Modules\Waste\Models\OperatorSite;
use Spatie\QueueableAction\QueueableAction;

/**
 * Crea un formulario. Verifica: quantità > 0, stato iniziale ammesso (draft, issued, in_transit), codice EER
 * attivo e caratteristiche HP coerenti, ruoli dei soggetti (produttore o detentore, destinatario, trasportatore,
 * intermediario o commerciante), appartenenza delle unità locali ai rispettivi soggetti, unicità del numero.
 */
class CreateFirAction
{
    use QueueableAction;

    public function __construct(
        private readonly EnsureHazardConsistencyAction $ensureHazardConsistency,
    ) {}

    public function execute(FirData $data): Fir
    {
        if (! is_finite($data->quantity) || $data->quantity <= 0.0) {
            throw InvalidQuantityException::notPositive($data->quantity);
        }

        if (! $data->status->isInitial()) {
            throw InvalidFirStatusTransitionException::invalidInitialStatus($data->status);
        }

        $ewcCode = EwcCode::query()->find($data->ewcCodeId);
        if (! $ewcCode instanceof EwcCode) {
            throw InvalidEwcCodeException::notFound($data->ewcCodeId);
        }
        if (! $ewcCode->is_active) {
            throw InvalidEwcCodeException::inactive($ewcCode->display_code);
        }
        $this->ensureHazardConsistency->execute($ewcCode, $data->hazardProperties);

        $producer = $this->operatorWithRole($data->producerOperatorId, OperatorRoleEnum::PRODUCER, OperatorRoleEnum::HOLDER);
        $recipient = $this->operatorWithRole($data->recipientOperatorId, OperatorRoleEnum::RECIPIENT);
        if ($data->transporterOperatorId !== null) {
            $this->operatorWithRole($data->transporterOperatorId, OperatorRoleEnum::TRANSPORTER);
        }
        if ($data->intermediaryOperatorId !== null) {
            $this->operatorWithRole($data->intermediaryOperatorId, OperatorRoleEnum::INTERMEDIARY, OperatorRoleEnum::DEALER);
        }

        $this->ensureSiteOfOperator($data->producerSiteId, $producer);
        $this->ensureSiteOfOperator($data->recipientSiteId, $recipient);

        $number = $data->number !== null ? trim($data->number) : null;
        $number = $number === '' ? null : $number;
        if ($number !== null && Fir::query()->where('number', $number)->exists()) {
            throw InvalidFirException::duplicateNumber($number);
        }

        $transportStartedAt = $data->transportStartedAt;
        if ($transportStartedAt === null && $data->status === FirStatusEnum::IN_TRANSIT) {
            $transportStartedAt = CarbonImmutable::now();
        }

        $fir = new Fir;
        $fir->fill([
            'number' => $number,
            'is_digital' => $data->isDigital,
            'status' => $data->status,
            'producer_operator_id' => $producer->id,
            'producer_site_id' => $data->producerSiteId,
            'transporter_operator_id' => $data->transporterOperatorId,
            'recipient_operator_id' => $recipient->id,
            'recipient_site_id' => $data->recipientSiteId,
            'intermediary_operator_id' => $data->intermediaryOperatorId,
            'ewc_code_id' => $ewcCode->id,
            'waste_description' => $data->wasteDescription,
            'physical_state' => $data->physicalState,
            'hazard_properties' => HazardPropertyEnum::normalizeList($data->hazardProperties),
            'operation' => $data->operation,
            'quantity' => round($data->quantity, 3),
            'quantity_unit' => $data->quantityUnit,
            'is_quantity_estimated' => $data->isQuantityEstimated,
            'vehicle_plate' => $data->vehiclePlate,
            'trailer_plate' => $data->trailerPlate,
            'driver_name' => $data->driverName,
            'adr_un_number' => $data->adrUnNumber,
            'transport_started_at' => $transportStartedAt,
            'notes' => $data->notes,
        ]);
        $fir->save();

        return $fir;
    }

    private function operatorWithRole(int $operatorId, OperatorRoleEnum ...$roles): Operator
    {
        $operator = Operator::query()->find($operatorId);
        if (! $operator instanceof Operator) {
            throw InvalidFirException::operatorNotFound($operatorId);
        }

        foreach ($roles as $role) {
            if ($operator->playsRole($role)) {
                return $operator;
            }
        }

        // Il ruolo nella lingua dell'utente ("destinatario"), non il valore interno ("recipient").
        throw InvalidFirException::operatorRoleMismatch($operator->business_name, implode(' / ', array_map(
            static fn (OperatorRoleEnum $role): string => mb_strtolower($role->getLabel()),
            $roles,
        )));
    }

    private function ensureSiteOfOperator(?int $siteId, Operator $operator): void
    {
        if ($siteId === null) {
            return;
        }

        $belongs = OperatorSite::query()
            ->whereKey($siteId)
            ->where('operator_id', $operator->id)
            ->exists();

        if (! $belongs) {
            throw InvalidFirException::siteNotOfOperator($siteId, $operator->business_name);
        }
    }
}
