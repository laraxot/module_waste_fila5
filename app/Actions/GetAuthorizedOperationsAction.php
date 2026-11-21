<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Models\AuthorizedWaste;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\OperatorSite;
use Modules\Waste\Models\PlantAuthorization;
use Spatie\QueueableAction\QueueableAction;

/**
 * Operazioni R/D che l'impianto puo' svolgere su un codice EER alla data indicata (titoli validi, stesse
 * regole di FindAuthorizedWasteAction), nell'ordine dell'enum. Serve ai moduli per proporre solo le scelte
 * lecite: la verifica vera resta in FindAuthorizedWasteAction, dentro la action che esegue l'operazione.
 */
class GetAuthorizedOperationsAction
{
    use QueueableAction;

    /**
     * @return list<WasteOperationEnum>
     */
    public function execute(OperatorSite $plant, EwcCode $ewc, ?CarbonInterface $on = null): array
    {
        $day = $on ?? CarbonImmutable::today();

        $granted = AuthorizedWaste::query()
            ->where('ewc_code_id', $ewc->id)
            ->whereHas('plantAuthorization', static function (Builder $query) use ($plant, $day): void {
                /** @var Builder<PlantAuthorization> $query */
                $query->where('operator_site_id', $plant->id);
                (new PlantAuthorization)->scopeValidOn($query, $day);
            })
            ->get()
            ->flatMap(static fn (AuthorizedWaste $authorized): array => $authorized->operations->all())
            ->map(static fn (WasteOperationEnum $operation): string => $operation->value)
            ->unique()
            ->all();

        return array_values(array_filter(
            WasteOperationEnum::cases(),
            static fn (WasteOperationEnum $operation): bool => \in_array($operation->value, $granted, true),
        ));
    }
}
