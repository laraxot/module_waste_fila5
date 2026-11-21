<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Waste\Models\RegisterEntry;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Registro cronologico: `number` progressivo per (register_id, year), con vincolo unique come ultima difesa
 * contro la doppia numerazione (la numerazione è assegnata sotto lock da AppendRegisterEntryAction).
 * `reference` è un morph verso modelli di altri moduli (nessun vincolo).
 */
return new class extends XotBaseMigration
{
    protected ?string $model_class = RegisterEntry::class;

    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('register_id')->index();
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('number');
            $table->string('type', 16)->index()->comment('RegisterEntryTypeEnum');
            $table->string('cause', 32)->index()->comment('RegisterCauseEnum');
            $table->timestamp('recorded_at')->comment('Data di annotazione');
            $table->timestamp('operation_at')->index()->comment('Data della movimentazione');
            $table->unsignedBigInteger('ewc_code_id')->index();
            $table->string('physical_state', 32)->comment('PhysicalStateEnum');
            $table->json('hazard_properties')->comment('Lista HazardPropertyEnum');
            $table->decimal('quantity_kg', 14, 3);
            $table->string('operation', 4)->nullable()->comment('WasteOperationEnum');
            $table->unsignedBigInteger('fir_id')->nullable()->index();
            $table->unsignedBigInteger('counterpart_operator_id')->nullable()->index();
            $table->nullableMorphs('reference');
            $table->json('load_entry_ids')->comment('Carichi di riferimento degli scarichi');
            $table->unsignedBigInteger('rectifies_entry_id')->nullable()->index();
            $table->text('rectification_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('transmitted_at')->nullable()->index();
            $table->string('rentri_transmission_ref')->nullable();

            $table->unique(['register_id', 'year', 'number']);
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, true);
        });
    }

    /**
     * Mai `drop`: la tabella contiene scritture del registro di carico e scarico (art. 190 D.Lgs. 152/2006). Un `migrate:rollback` non deve
     * cancellare dati legali (audit database R15); si torna indietro solo con una migrazione in avanti.
     */
    public function down(): void
    {
        throw new \LogicException('Rollback vietato: '.$this->getTable().' contiene dati legali/di tracciabilità; usare una migrazione in avanti.');
    }
};
