<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Crea i permessi controllati dalle policy del modulo Waste (`{modello}.{abilità}`), guard `web`.
 *
 * Idempotente (findOrCreate). Senza questi permessi `hasPermissionTo()` lancia `PermissionDoesNotExist`
 * per ogni utente che non sia super-admin (audit sicurezza M4). I ruoli operativi che li ricevono sono
 * del prodotto (in Roosko: RooskoPermissionSeeder). Il test `PermissionSeederTest` verifica che l'elenco
 * copra ogni `hasPermissionTo()` delle policy.
 */
class WastePermissionSeeder extends Seeder
{
    private const string GUARD = 'web';

    /** @var array<string, list<string>> modello => abilità */
    public const array PERMISSIONS = [
        'authorized_waste' => ['viewAny', 'view', 'create', 'update', 'delete'],
        'ewc_code' => ['viewAny', 'view', 'create', 'update', 'delete', 'import'],
        'fir' => ['viewAny', 'view', 'create', 'update', 'change_status', 'record_acceptance'],
        'operator' => ['viewAny', 'view', 'create', 'update', 'delete'],
        'operator_site' => ['viewAny', 'view', 'create', 'update', 'delete'],
        'plant_authorization' => ['viewAny', 'view', 'create', 'update', 'delete'],
        'register' => ['viewAny', 'view', 'create', 'update', 'delete'],
        'register_entry' => ['viewAny', 'view', 'rectify'],
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::names() as $name) {
            Permission::findOrCreate($name, self::GUARD);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @return list<string>
     */
    public static function names(): array
    {
        $names = [];
        foreach (self::PERMISSIONS as $model => $abilities) {
            foreach ($abilities as $ability) {
                $names[] = $model.'.'.$ability;
            }
        }

        return $names;
    }
}
