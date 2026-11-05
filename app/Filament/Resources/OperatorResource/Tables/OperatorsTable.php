<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\OperatorResource\Tables;

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
use Illuminate\Database\Eloquent\Builder;
use Modules\Waste\Enums\OperatorRoleEnum;
use Modules\Waste\Models\Operator;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class OperatorsTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'business_name' => TextColumn::make('business_name')->searchable()->sortable(),
            'fiscal_code' => TextColumn::make('fiscal_code')->searchable(),
            'vat_number' => TextColumn::make('vat_number')->searchable()->toggleable(),
            'roles' => TextColumn::make('roles')->badge(),
            'rentri_registration_number' => TextColumn::make('rentri_registration_number')->toggleable(isToggledHiddenByDefault: true),
            'pec' => TextColumn::make('pec')->toggleable(isToggledHiddenByDefault: true),
            'sites_count' => TextColumn::make('sites_count')->counts('sites'),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'role' => SelectFilter::make('role')
                ->options(OperatorRoleEnum::class)
                ->query(static function (Builder $query, array $data): Builder {
                    $value = $data['value'] ?? null;
                    if (! is_string($value) || $value === '') {
                        return $query;
                    }

                    /** @var Builder<Operator> $query */
                    return $query->whereJsonContains('roles', $value);
                }),
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
