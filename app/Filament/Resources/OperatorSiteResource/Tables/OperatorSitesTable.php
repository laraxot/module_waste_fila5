<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\OperatorSiteResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class OperatorSitesTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')->searchable()->sortable(),
            'operator' => TextColumn::make('operator.business_name')->searchable()->sortable(),
            'rentri_site_number' => TextColumn::make('rentri_site_number')->searchable(),
            'address' => TextColumn::make('address.full_address')->wrap(),
            'is_plant' => IconColumn::make('is_plant')->boolean(),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'operator_id' => SelectFilter::make('operator_id')->relationship('operator', 'business_name')->searchable(),
            'is_plant' => TernaryFilter::make('is_plant'),
            'is_active' => TernaryFilter::make('is_active'),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
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
