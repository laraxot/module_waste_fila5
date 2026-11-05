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
 * Cerca, tra i titoli dell'impianto validi alla data dell'operazione (attivi, già rilasciati, non scaduti:
 * la data di scadenza è inclusa; senza data = oggi), il codice autorizzato per quel codice EER e quell'operazione
 * R/D. Null = conferimento/operazione non autorizzati.
 *
 * Titoli sovrapposti (rinnovo con il vecchio titolo ancora attivo): vince il titolo rilasciato per ultimo
 * (`issued_at`), a parità quello inserito per ultimo. I limiti di titoli diversi non si sommano mai.
 */
class FindAuthorizedWasteAction
{
    use QueueableAction;

    public function execute(OperatorSite $plant, EwcCode $ewc, WasteOperationEnum $operation, ?CarbonInterface $on = null): ?AuthorizedWaste
    {
        $day = $on ?? CarbonImmutable::today();
        $authorizations = (new PlantAuthorization)->getTable();
        $authorizedWastes = (new AuthorizedWaste)->getTable();

        return AuthorizedWaste::query()
            ->select($authorizedWastes.'.*')
            ->join($authorizations, $authorizations.'.id', '=', $authorizedWastes.'.plant_authorization_id')
            ->where($authorizedWastes.'.ewc_code_id', $ewc->id)
            ->whereJsonContains($authorizedWastes.'.operations', $operation->value)
            ->whereHas('plantAuthorization', static function (Builder $query) use ($plant, $day): void {
                /** @var Builder<PlantAuthorization> $query */
                $query->where('operator_site_id', $plant->id);
                (new PlantAuthorization)->scopeValidOn($query, $day);
            })
            ->orderByDesc($authorizations.'.issued_at')
            ->orderByDesc($authorizedWastes.'.id')
            ->first();
    }
}
