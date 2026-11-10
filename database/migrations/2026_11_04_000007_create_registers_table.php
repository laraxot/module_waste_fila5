<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Waste\Models\Register;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = Register::class;

    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('operator_site_id')->index();
            $table->string('name');
            $table->string('rentri_identifier')->nullable()->index()->comment('Identificativo del registro vidimato');
            $table->boolean('is_active')->default(true)->index();
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, true);
        });
    }

    /**
     * Mai `drop`: la tabella contiene registri di carico e scarico. Un `migrate:rollback` non deve
     * cancellare dati legali (audit database R15); si torna indietro solo con una migrazione in avanti.
     */
    public function down(): void
    {
        throw new \LogicException('Rollback vietato: '.$this->getTable().' contiene dati legali/di tracciabilità; usare una migrazione in avanti.');
    }
};
