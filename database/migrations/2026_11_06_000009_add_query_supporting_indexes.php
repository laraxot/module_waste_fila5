<?php

declare(strict_types=1);

use Modules\Waste\Models\RegisterEntry;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Indici di supporto alle query del registro (audit database R14), sulla connessione `waste`.
 *
 * Solo aggiunte idempotenti (XotBaseMigration::ensureIndexes). Niente down(): togliere indici in produzione
 * è una decisione dell'utente.
 */
return new class extends XotBaseMigration
{
    protected ?string $model_class = RegisterEntry::class;

    public function up(): void
    {
        $this->ensureIndexes([
            // GetAuthorizedQuantityUsageAction: ewc_code_id = ? AND type = 'load' AND operation_at BETWEEN inizio/fine anno.
            (new RegisterEntry)->getTable() => [
                'register_entries_ewc_type_operation_at_index' => ['ewc_code_id', 'type', 'operation_at'],
            ],
        ]);
    }
};
