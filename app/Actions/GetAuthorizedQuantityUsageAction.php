<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Modules\Waste\Datas\AuthorizationUsageData;
use Modules\Waste\Enums\RegisterEntryTypeEnum;
use Modules\Waste\Exceptions\InvalidQuantityException;
use Modules\Waste\Models\AuthorizedWaste;
use Modules\Waste\Models\Register;
use Modules\Waste\Models\RegisterEntry;
use Spatie\QueueableAction\QueueableAction;

/**
 * Utilizzo dei limiti di un codice autorizzato.
 *
 * - Istantaneo: `$currentStockKg` (giacenza attuale, calcolata da chi chiama: in Roosko dal magazzino)
 *   rispetto a `max_storage_kg`.
 * - Annuo: somma dei carichi (`type = load`) dell'anno solare corrente per quel codice EER sui registri
 *   dell'unità locale del titolo, per data operazione, escluse le scritture sostituite da rettifica
 *   (vale la rettifica), rispetto a `max_annual_kg`.
 */
class GetAuthorizedQuantityUsageAction
{
    use QueueableAction;

    public function execute(AuthorizedWaste $authorized, float $currentStockKg): AuthorizationUsageData
    {
        if ($currentStockKg < 0.0) {
            throw InvalidQuantityException::negative($currentStockKg);
        }

        $authorized->loadMissing('plantAuthorization');
        $siteId = $authorized->plantAuthorization->operator_site_id;

        $now = CarbonImmutable::now();
        $sum = RegisterEntry::query()
            ->effective()
            ->where('type', RegisterEntryTypeEnum::LOAD->value)
            ->where('ewc_code_id', $authorized->ewc_code_id)
            ->whereBetween('operation_at', [$now->startOfYear(), $now->endOfYear()])
            ->whereHas('register', static function (Builder $query) use ($siteId): void {
                /** @var Builder<Register> $query */
                $query->where('operator_site_id', $siteId);
            })
            ->sum('quantity_kg');
        $annualLoadedKg = is_numeric($sum) ? round((float) $sum, 3) : 0.0;

        $maxStorageKg = $authorized->maxStorageKg();
        $maxAnnualKg = $authorized->maxAnnualKg();

        return new AuthorizationUsageData(
            currentStockKg: round($currentStockKg, 3),
            maxStorageKg: $maxStorageKg,
            storageUsagePct: $this->percentage($currentStockKg, $maxStorageKg),
            annualLoadedKg: $annualLoadedKg,
            maxAnnualKg: $maxAnnualKg,
            annualUsagePct: $this->percentage($annualLoadedKg, $maxAnnualKg),
        );
    }

    private function percentage(float $value, ?float $limit): ?float
    {
        if ($limit === null || $limit <= 0.0) {
            return null;
        }

        return round($value / $limit * 100, 2);
    }
}
