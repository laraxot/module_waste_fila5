<?php

declare(strict_types=1);

namespace Modules\Waste\Models;

use Modules\Xot\Models\XotBaseModel;

/**
 * Modello base del modulo Waste (connessione `waste`).
 *
 * XotBaseModel fornisce HasXotFactory, Updater (created_by / updated_by) e i cast comuni.
 */
abstract class BaseModel extends XotBaseModel
{
    /** @var string */
    protected $connection = 'waste';

    /**
     * Le chiavi primarie del modulo sono `bigIncrements`: il cast `id => string` di XotBaseModel
     * renderebbe incoerenti i confronti con le FK interne (int) e le liste di id (load_entry_ids).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'id' => 'integer',
        ]);
    }
}
