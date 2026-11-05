<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\RegisterResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Waste\Filament\Actions\RectifyEntryAction;
use Modules\Waste\Filament\Resources\RegisterEntryResource;
use Modules\Waste\Models\RegisterEntry;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

/**
 * Scritture del registro in sola lettura: si annotano con le Actions di dominio, si correggono con la rettifica.
 */
class RegisterEntriesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'entries';

    /**
     * @return array<string, Component>
     */
    #[\Override]
    public function getFormSchema(): array
    {
        return [];
    }

    /**
     * @return array<string, Column>
     */
    #[\Override]
    public function getTableColumns(): array
    {
        return [
            'year' => TextColumn::make('year')->sortable(),
            'number' => TextColumn::make('number')->sortable(),
            'type' => TextColumn::make('type')->badge(),
            'cause' => TextColumn::make('cause')->badge(),
            'operation_at' => TextColumn::make('operation_at')->dateTime()->sortable(),
            'ewc_code' => TextColumn::make('ewcCode.display_code'),
            'quantity_kg' => TextColumn::make('quantity_kg')->numeric(decimalPlaces: 3),
            'operation' => TextColumn::make('operation')->badge(),
            'transmitted_at' => TextColumn::make('transmitted_at')->dateTime()->toggleable(),
        ];
    }

    /**
     * @return array<string, Action>
     */
    #[\Override]
    public function getTableHeaderActions(): array
    {
        return [];
    }

    /**
     * @return array<string, Action>
     */
    #[\Override]
    public function getTableActions(): array
    {
        return [
            'view' => Action::make('view')
                ->icon('heroicon-o-eye')
                ->url(static fn (RegisterEntry $record): string => RegisterEntryResource::getUrl('view', ['record' => $record])),
            'rectify' => RectifyEntryAction::make('rectify'),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    #[\Override]
    public function getTableBulkActions(): array
    {
        return [];
    }
}
