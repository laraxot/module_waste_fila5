<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Carbon\CarbonImmutable;
use Modules\Waste\Datas\RegisterEntryData;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Enums\RegisterEntryTypeEnum;
use Modules\Waste\Exceptions\InvalidEwcCodeException;
use Modules\Waste\Exceptions\InvalidQuantityException;
use Modules\Waste\Exceptions\InvalidRegisterEntryException;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\Fir;
use Modules\Waste\Models\Operator;
use Modules\Waste\Models\RegisterEntry;
use Spatie\QueueableAction\QueueableAction;

/**
 * Regole di validità di una scrittura del registro, comuni a nuova scrittura e rettifica.
 *
 * - quantità > 0 (kg);
 * - causale compatibile con il tipo (carico/scarico);
 * - codice EER esistente e attivo, caratteristiche HP coerenti con la pericolosità del codice;
 * - operazione R/D obbligatoria per gli avvii a trattamento (terzi o interno);
 * - controparte obbligatoria per le movimentazioni con terzi;
 * - data operazione non nel futuro (tolleranza di 5 minuti per lo sfasamento degli orologi);
 * - carico senza riferimenti a carichi; scarico con almeno un carico di riferimento dello stesso registro,
 *   dello stesso codice EER e non sostituito da una rettifica;
 * - FIR, se indicato, esistente, non annullato e con lo stesso codice EER.
 */
class ValidateRegisterEntryDataAction
{
    use QueueableAction;

    private const int FUTURE_TOLERANCE_MINUTES = 5;

    public function __construct(
        private readonly EnsureHazardConsistencyAction $ensureHazardConsistency,
    ) {}

    public function execute(RegisterEntryData $data): void
    {
        if (! is_finite($data->quantityKg) || $data->quantityKg <= 0.0) {
            throw InvalidQuantityException::notPositive($data->quantityKg);
        }

        if (! $data->cause->allowsType($data->type)) {
            throw InvalidRegisterEntryException::causeNotAllowed($data->cause->value, $data->type->value);
        }

        $ewcCode = EwcCode::query()->find($data->ewcCodeId);
        if (! $ewcCode instanceof EwcCode) {
            throw InvalidEwcCodeException::notFound($data->ewcCodeId);
        }
        if (! $ewcCode->is_active) {
            throw InvalidEwcCodeException::inactive($ewcCode->display_code);
        }

        $this->ensureHazardConsistency->execute($ewcCode, $data->hazardProperties);

        if ($data->cause->requiresOperation() && $data->operation === null) {
            throw InvalidRegisterEntryException::operationRequired($data->cause->value);
        }

        $this->validateCounterpart($data);

        if ($data->operationAt->greaterThan(CarbonImmutable::now()->addMinutes(self::FUTURE_TOLERANCE_MINUTES))) {
            throw InvalidRegisterEntryException::operationInFuture($data->operationAt->toDateTimeString());
        }

        $this->validateLoadReferences($data, $ewcCode);

        $this->validateFir($data, $ewcCode);
    }

    private function validateCounterpart(RegisterEntryData $data): void
    {
        if ($data->counterpartOperatorId === null) {
            if ($data->cause->requiresCounterpart()) {
                throw InvalidRegisterEntryException::counterpartRequired($data->cause->value);
            }

            return;
        }

        if (! Operator::query()->whereKey($data->counterpartOperatorId)->exists()) {
            throw InvalidRegisterEntryException::counterpartNotFound($data->counterpartOperatorId);
        }
    }

    private function validateLoadReferences(RegisterEntryData $data, EwcCode $ewcCode): void
    {
        $loadIds = $data->normalizedLoadEntryIds();

        if ($data->type === RegisterEntryTypeEnum::LOAD) {
            if ($loadIds !== []) {
                throw InvalidRegisterEntryException::loadReferencesOnLoad();
            }

            return;
        }

        if ($loadIds === []) {
            throw InvalidRegisterEntryException::loadReferencesMissing();
        }

        $loads = RegisterEntry::query()
            ->with('ewcCode')
            ->withExists('rectification')
            ->whereKey($loadIds)
            ->get()
            ->keyBy('id');

        foreach ($loadIds as $loadId) {
            $load = $loads->get($loadId);
            if (! $load instanceof RegisterEntry
                || $load->register_id !== $data->registerId
                || $load->type !== RegisterEntryTypeEnum::LOAD) {
                throw InvalidRegisterEntryException::loadReferenceInvalid($loadId);
            }

            if ($load->ewc_code_id !== $ewcCode->id) {
                throw InvalidRegisterEntryException::loadReferenceEwcMismatch($loadId, $ewcCode->display_code, $load->ewcCode->display_code);
            }

            if ((bool) $load->getAttribute('rectification_exists')) {
                throw InvalidRegisterEntryException::loadReferenceSuperseded($loadId);
            }
        }
    }

    private function validateFir(RegisterEntryData $data, EwcCode $ewcCode): void
    {
        if ($data->firId === null) {
            return;
        }

        $fir = Fir::query()->find($data->firId);
        if (! $fir instanceof Fir) {
            throw InvalidRegisterEntryException::firNotFound($data->firId);
        }

        if ($fir->status === FirStatusEnum::CANCELLED) {
            throw InvalidRegisterEntryException::firCancelled($fir->displayNumber());
        }

        if ($fir->ewc_code_id !== $ewcCode->id) {
            throw InvalidRegisterEntryException::firEwcMismatch($fir->displayNumber(), $ewcCode->display_code);
        }
    }
}
