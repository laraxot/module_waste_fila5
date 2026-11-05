<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Models\AuthorizedWaste;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\OperatorSite;
use Modules\Waste\Models\PlantAuthorization;
use Spatie\QueueableAction\QueueableAction;

/**
 * Cerca, tra i titoli dell'impianto validi oggi (attivi, già rilasciati, non scaduti: la data di scadenza è
 * inclusa), il codice autorizzato per quel codice EER e quell'operazione R/D. Se più titoli lo autorizzano
 * restituisce quello inserito per ultimo. Null = conferimento/operazione non autorizzati.
 */
class FindAuthorizedWasteAction
{
    use QueueableAction;

    public function execute(OperatorSite $plant, EwcCode $ewc, WasteOperationEnum $operation): ?AuthorizedWaste
    {
        $today = CarbonImmutable::today();

        return AuthorizedWaste::query()
            ->where('ewc_code_id', $ewc->id)
            ->whereJsonContains('operations', $operation->value)
            ->whereHas('plantAuthorization', static function (Builder $query) use ($plant, $today): void {
                /** @var Builder<PlantAuthorization> $query */
                $query->where('operator_site_id', $plant->id);
                (new PlantAuthorization)->scopeValidOn($query, $today);
            })
            ->orderByDesc('id')
            ->first();
    }
}
