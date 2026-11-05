<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\RegisterEntryResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Modules\Waste\Enums\RegisterCauseEnum;
use Modules\Waste\Enums\RegisterEntryTypeEnum;
use Modules\Waste\Filament\Actions\RectifyEntryAction;
use Modules\Waste\Models\RegisterEntry;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Registro in sola lettura: niente creazione in modale, modifica o cancellazione.
 */
class RegisterEntriesTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'register' => TextColumn::make('register.name')->toggleable(),
            'year' => TextColumn::make('year')->sortable(),
            'number' => TextColumn::make('number')->sortable(),
            'type' => TextColumn::make('type')->badge(),
            'cause' => TextColumn::make('cause')->badge(),
            'operation_at' => TextColumn::make('operation_at')->dateTime()->sortable(),
            'ewc_code' => TextColumn::make('ewcCode.display_code')->searchable(['code']),
            'quantity_kg' => TextColumn::make('quantity_kg')->numeric(decimalPlaces: 3)->sortable(),
            'physical_state' => TextColumn::make('physical_state')->toggleable(isToggledHiddenByDefault: true),
            'hazard_properties' => TextColumn::make('hazard_properties')->badge()->toggleable(),
            'operation' => TextColumn::make('operation')->badge(),
            'fir' => TextColumn::make('fir.number')->searchable()->toggleable(),
            'counterpart' => TextColumn::make('counterpart.business_name')->searchable()->toggleable(),
            'rectified_entry' => TextColumn::make('rectifiedEntry.number')
                ->formatStateUsing(static fn (mixed $state, RegisterEntry $record): string => $record->rectifiedEntry?->displayNumber() ?? '')
                ->toggleable(),
            'transmitted_at' => TextColumn::make('transmitted_at')->dateTime()->sortable()->toggleable(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'register_id' => SelectFilter::make('register_id')->relationship('register', 'name'),
            'year' => SelectFilter::make('year')
                ->options(static fn (): array => RegisterEntry::query()
                    ->distinct()
                    ->orderByDesc('year')
                    ->pluck('year', 'year')
                    ->all()),
            'type' => SelectFilter::make('type')->options(RegisterEntryTypeEnum::class),
            'cause' => SelectFilter::make('cause')->options(RegisterCauseEnum::class),
            'ewc_code_id' => SelectFilter::make('ewc_code_id')->relationship('ewcCode', 'code')->searchable(),
            'transmitted_at' => TernaryFilter::make('transmitted_at')->nullable(),
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
            'rectify' => RectifyEntryAction::make('rectify'),
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
