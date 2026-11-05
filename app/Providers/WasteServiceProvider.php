<?php

declare(strict_types=1);

namespace Modules\Waste\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;
use Override;

/**
 * Service provider del modulo Waste.
 *
 * Nota: XotBaseServiceProvider legge `$module_dir` / `$module_ns` (snake_case) per migrazioni,
 * RouteServiceProvider ed EventServiceProvider del modulo: vanno ridefiniti qui con questi nomi.
 */
class WasteServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Waste';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    #[Override]
    public function boot(): void
    {
        parent::boot();

        $this->registerConfig();
    }

    /**
     * La config del modulo è esposta come `config('waste.*')` (stesso schema di Activity).
     */
    #[Override]
    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->name, 'config/config.php') => config_path('waste.php'),
        ], 'config');

        $this->mergeConfigFrom(module_path($this->name, 'config/config.php'), 'waste');
    }
}
