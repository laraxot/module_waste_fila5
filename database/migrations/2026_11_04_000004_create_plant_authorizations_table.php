<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Waste\Models\PlantAuthorization;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * L'atto autorizzativo è nella media collection `document` (tabella media del modulo Media).
 */
return new class extends XotBaseMigration
{
    protected ?string $model_class = PlantAuthorization::class;

    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('operator_site_id')->index();
            $table->string('type', 32)->index()->comment('AuthorizationTypeEnum');
            $table->string('number');
            $table->string('issuing_authority');
            $table->date('issued_at');
            $table->date('expires_at')->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->text('notes')->nullable();
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, true);
        });
    }
};
