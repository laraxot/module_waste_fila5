<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Waste\Models\EwcCode;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = EwcCode::class;

    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('code', 8)->unique()->comment('Codice EER normalizzato, es. 19 12 04 (senza asterisco)');
            $table->string('chapter', 2)->index();
            $table->string('sub_chapter', 5)->index();
            $table->text('description');
            $table->boolean('is_hazardous')->default(false)->index()->comment('Voce con asterisco');
            $table->boolean('is_mirror')->default(false)->comment('Voce a specchio');
            $table->boolean('is_active')->default(true)->index();
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, true);
        });
    }
};
