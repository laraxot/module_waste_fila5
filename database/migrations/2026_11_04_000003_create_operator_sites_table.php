<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Waste\Models\OperatorSite;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * L'indirizzo dell'unità locale sta nella tabella `addresses` di Geo (morph `model`), non qui.
 */
return new class extends XotBaseMigration
{
    protected ?string $model_class = OperatorSite::class;

    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('operator_id')->index();
            $table->string('name');
            $table->string('rentri_site_number')->nullable()->index()->comment('num_iscr_sito RENTRI');
            $table->boolean('is_plant')->default(false)->index()->comment('Impianto gestito da noi');
            $table->boolean('is_active')->default(true)->index();
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, true);
        });
    }
};
