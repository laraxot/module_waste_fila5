<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Waste\Models\AuthorizedWaste;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = AuthorizedWaste::class;

    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('plant_authorization_id')->index();
            $table->unsignedBigInteger('ewc_code_id')->index();
            $table->json('operations')->comment('Lista WasteOperationEnum');
            $table->decimal('max_storage_kg', 14, 3)->nullable()->comment('Limite di stoccaggio istantaneo');
            $table->decimal('max_annual_kg', 14, 3)->nullable()->comment('Limite annuo');
            $table->text('notes')->nullable();
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, true);
        });
    }
};
