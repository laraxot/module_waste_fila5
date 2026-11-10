<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Waste\Models\Fir;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = Fir::class;

    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('number')->nullable()->unique()->comment('Numero del formulario');
            $table->boolean('is_digital')->default(false);
            $table->string('status', 32)->index()->comment('FirStatusEnum');
            $table->unsignedBigInteger('producer_operator_id')->index();
            $table->unsignedBigInteger('producer_site_id')->nullable()->index();
            $table->unsignedBigInteger('transporter_operator_id')->nullable()->index();
            $table->unsignedBigInteger('recipient_operator_id')->index();
            $table->unsignedBigInteger('recipient_site_id')->nullable()->index();
            $table->unsignedBigInteger('intermediary_operator_id')->nullable()->index();
            $table->unsignedBigInteger('ewc_code_id')->index();
            $table->text('waste_description')->nullable();
            $table->string('physical_state', 32)->comment('PhysicalStateEnum');
            $table->json('hazard_properties')->comment('Lista HazardPropertyEnum');
            $table->string('operation', 4)->comment('WasteOperationEnum');
            $table->decimal('quantity', 14, 3);
            $table->string('quantity_unit', 4)->comment('QuantityUnitEnum');
            $table->boolean('is_quantity_estimated')->default(false)->comment('Peso da verificarsi a destino');
            $table->decimal('accepted_quantity_kg', 14, 3)->nullable();
            $table->string('acceptance', 16)->nullable()->comment('AcceptanceEnum');
            $table->text('rejection_reason')->nullable();
            $table->string('vehicle_plate', 16)->nullable();
            $table->string('trailer_plate', 16)->nullable();
            $table->string('driver_name')->nullable();
            $table->string('adr_un_number', 8)->nullable();
            $table->timestamp('transport_started_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->text('notes')->nullable();
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, true);
        });
    }

    /**
     * Mai `drop`: la tabella contiene formulari di identificazione dei rifiuti (art. 193 D.Lgs. 152/2006). Un `migrate:rollback` non deve
     * cancellare dati legali (audit database R15); si torna indietro solo con una migrazione in avanti.
     */
    public function down(): void
    {
        throw new \LogicException('Rollback vietato: '.$this->getTable().' contiene dati legali/di tracciabilità; usare una migrazione in avanti.');
    }
};
