<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Exceptions\HazardPropertiesMismatchException;
use Modules\Waste\Models\EwcCode;
use Spatie\QueueableAction\QueueableAction;

/**
 * Coerenza tra codice EER e caratteristiche di pericolo: un codice pericoloso (asterisco) richiede almeno
 * una caratteristica HP; un codice non pericoloso non ne può avere. Per le voci a specchio la scelta tra le due
 * voci (con/senza asterisco) è a monte, in fase di classificazione: qui si verifica solo la coerenza.
 */
class EnsureHazardConsistencyAction
{
    use QueueableAction;

    /**
     * @param  list<HazardPropertyEnum>  $hazardProperties
     */
    public function execute(EwcCode $ewcCode, array $hazardProperties): void
    {
        if ($ewcCode->is_hazardous && $hazardProperties === []) {
            throw HazardPropertiesMismatchException::missingForHazardous($ewcCode->display_code);
        }

        if (! $ewcCode->is_hazardous && $hazardProperties !== []) {
            throw HazardPropertiesMismatchException::presentForNonHazardous($ewcCode->display_code);
        }
    }
}
