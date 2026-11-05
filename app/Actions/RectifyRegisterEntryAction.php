<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Modules\Waste\Datas\RegisterEntryData;
use Modules\Waste\Models\RegisterEntry;
use Spatie\QueueableAction\QueueableAction;

/**
 * Rettifica una scrittura: la scrittura originale resta invariata e una nuova scrittura, con il suo numero
 * progressivo, la sostituisce (`rectifies_entry_id`) riportando i dati corretti e la motivazione.
 *
 * Vincoli: stesso registro e stesso tipo (carico/scarico) dell'originale, motivazione obbligatoria,
 * una scrittura si rettifica una sola volta (per correggere ancora si rettifica la rettifica).
 * Le scritture sostituite sono escluse da RegisterEntry::effective() e non possono essere usate come
 * carico di riferimento di uno scarico.
 */
class RectifyRegisterEntryAction
{
    use QueueableAction;

    public function __construct(
        private readonly AppendRegisterEntryAction $append,
    ) {}

    public function execute(RegisterEntry $entry, RegisterEntryData $corrected, string $reason): RegisterEntry
    {
        return $this->append->execute($corrected, $entry, $reason);
    }
}
