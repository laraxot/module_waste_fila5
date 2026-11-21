<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\FirResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Filament\Resources\FirResource;
use Modules\Waste\Models\Fir;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Nessuna creazione in modale né cancellazione: un FIR si crea dalla pagina dedicata (CreateFirAction)
 * e si annulla, non si elimina.
 */
class FirsTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'number' => TextColumn::make('number')->searchable()->sortable(),
            'status' => TextColumn::make('status')->badge()->sortable(),
            'is_digital' => IconColumn::make('is_digital')->boolean()->toggleable(),
            'producer' => TextColumn::make('producer.business_name')->searchable(),
            'recipient' => TextColumn::make('recipient.business_name')->searchable()->toggleable(),
            'ewc_code' => TextColumn::make('ewcCode.display_code')->searchable(['code']),
            'operation' => TextColumn::make('operation')->badge(),
            'quantity' => TextColumn::make('quantity')->numeric(decimalPlaces: 3),
            'quantity_unit' => TextColumn::make('quantity_unit'),
            'transport_started_at' => TextColumn::make('transport_started_at')->dateTime()->sortable()->toggleable(),
            'arrived_at' => TextColumn::make('arrived_at')->dateTime()->sortable()->toggleable(),
            'accepted_quantity_kg' => TextColumn::make('accepted_quantity_kg')->numeric(decimalPlaces: 3)->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'status' => SelectFilter::make('status')->options(FirStatusEnum::class)->multiple(),
            'ewc_code_id' => SelectFilter::make('ewc_code_id')->relationship('ewcCode', 'code')->searchable(),
            'is_digital' => TernaryFilter::make('is_digital'),
        ];
    }

    /**
     * @return array<int|string, Action|ActionGroup>
     */
    public function getTableHeaderActions(): array
    {
        return [];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            // Solo le bozze sono modificabili (anche per il super-admin, che la policy lascerebbe passare):
            // il pulsante non deve portare a una pagina 403.
            'edit' => EditAction::make()->visible(static fn (Fir $record): bool => FirResource::canEdit($record)),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [];
    }
}
