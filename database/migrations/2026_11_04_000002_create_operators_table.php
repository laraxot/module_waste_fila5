<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Waste\Models\Operator;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = Operator::class;

    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('business_name');
            $table->string('fiscal_code', 16)->index();
            $table->string('vat_number', 20)->nullable()->index();
            $table->string('rentri_registration_number')->nullable()->index()->comment('Numero di iscrizione al RENTRI');
            $table->string('albo_registration_number')->nullable()->comment('Iscrizione Albo Nazionale Gestori Ambientali');
            $table->json('roles')->comment('Lista OperatorRoleEnum');
            $table->string('email')->nullable();
            $table->string('pec')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->string('tenant_id', 36)->nullable()->index()->comment('Cliente dell\'area clienti (tenant del modulo User)');
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('tenant_id')) {
                $table->string('tenant_id', 36)->nullable()->index()->comment('Cliente dell\'area clienti (tenant del modulo User)');
            }

            $this->updateTimestamps($table, true);
        });
    }
};
