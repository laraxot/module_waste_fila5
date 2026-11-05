<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\RegisterEntryResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Modules\Waste\Filament\Resources\RegisterEntryResource;
use Modules\Xot\Filament\Actions\Header\ExportXlsAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

/**
 * Nessun "crea": le scritture si annotano con PostRegisterEntryAction. Export delle righe filtrate.
 */
class ListRegisterEntries extends XotBaseListRecords
{
    protected static string $resource = RegisterEntryResource::class;

    /**
     * @return array<string, Action|ActionGroup>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            'export_xls' => ExportXlsAction::make('export_xls'),
        ];
    }
}
