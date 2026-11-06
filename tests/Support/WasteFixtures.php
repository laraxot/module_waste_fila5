<?php

declare(strict_types=1);

namespace Modules\Waste\Tests\Support;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Schema;
use Modules\Waste\Database\Factories\EwcCodeFactory;
use Modules\Waste\Database\Factories\OperatorFactory;
use Modules\Waste\Datas\RegisterEntryData;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\OperatorRoleEnum;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\RegisterCauseEnum;
use Modules\Waste\Enums\RegisterEntryTypeEnum;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\Operator;
use Modules\Waste\Models\Register;
use Modules\Xot\Database\Migrations\XotBaseMigration;

use function Safe\glob;

/**
 * Dati di prova comuni ai test del modulo.
 */
final class WasteFixtures
{
    /** Crea le tabelle del modulo sulla connessione `waste` se mancano (DB di test vergine o file dedicato). */
    public static function ensureSchema(): void
    {
        if (Schema::connection('waste')->hasTable('register_entries')) {
            return;
        }

        self::migrate();
    }

    public static function migrate(): void
    {
        $files = glob(dirname(__DIR__, 2).'/database/migrations/*.php');
        sort($files);

        foreach ($files as $file) {
            $migration = require $file;
            if ($migration instanceof XotBaseMigration && method_exists($migration, 'up')) {
                $migration->up();
            }
        }
    }

    public static function register(): Register
    {
        return Register::factory()->createOne();
    }

    public static function ewc(bool $hazardous = false): EwcCode
    {
        $factory = EwcCodeFactory::new();

        return ($hazardous ? $factory->hazardous() : $factory)->createOne();
    }

    public static function producer(): Operator
    {
        return OperatorFactory::new()->withRoles(OperatorRoleEnum::PRODUCER)->createOne();
    }

    /**
     * Carico ricevuto da terzi, oggi, 100 kg.
     *
     * @param  list<HazardPropertyEnum>  $hazardProperties
     */
    public static function loadData(
        Register $register,
        EwcCode $ewc,
        float $quantityKg = 100.0,
        ?Operator $counterpart = null,
        array $hazardProperties = [],
        ?CarbonImmutable $operationAt = null,
    ): RegisterEntryData {
        $counterpart ??= self::producer();

        return new RegisterEntryData(
            registerId: $register->id,
            type: RegisterEntryTypeEnum::LOAD,
            cause: RegisterCauseEnum::FROM_THIRD_PARTIES,
            ewcCodeId: $ewc->id,
            quantityKg: $quantityKg,
            physicalState: PhysicalStateEnum::SOLID_NON_POWDERY,
            operationAt: $operationAt ?? CarbonImmutable::now(),
            hazardProperties: $hazardProperties,
            operation: WasteOperationEnum::R13,
            counterpartOperatorId: $counterpart->id,
        );
    }

    /**
     * Avvio a trattamento interno di carichi già annotati.
     *
     * @param  list<int>  $loadEntryIds
     */
    public static function unloadData(Register $register, EwcCode $ewc, array $loadEntryIds, float $quantityKg = 50.0): RegisterEntryData
    {
        return new RegisterEntryData(
            registerId: $register->id,
            type: RegisterEntryTypeEnum::UNLOAD,
            cause: RegisterCauseEnum::INTERNAL_TREATMENT,
            ewcCodeId: $ewc->id,
            quantityKg: $quantityKg,
            physicalState: PhysicalStateEnum::SOLID_NON_POWDERY,
            operationAt: CarbonImmutable::now(),
            operation: WasteOperationEnum::R12,
            loadEntryIds: $loadEntryIds,
        );
    }
}
