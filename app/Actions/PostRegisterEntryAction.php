<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Modules\Waste\Datas\RegisterEntryData;
use Modules\Waste\Models\RegisterEntry;
use Spatie\QueueableAction\QueueableAction;

/**
 * Annota una nuova scrittura (carico o scarico) sul registro cronologico: numero progressivo per registro e
 * anno assegnato sotto lock, `recorded_at = now()`. Dopo la trasmissione a RENTRI la scrittura è immutabile;
 * le correzioni si fanno con RectifyRegisterEntryAction.
 *
 * @see AppendRegisterEntryAction per numerazione, lock e retry
 * @see ValidateRegisterEntryDataAction per le regole di validità
 */
class PostRegisterEntryAction
{
    use QueueableAction;

    public function __construct(
        private readonly AppendRegisterEntryAction $append,
    ) {}

    public function execute(RegisterEntryData $data): RegisterEntry
    {
        return $this->append->execute($data);
    }
}
