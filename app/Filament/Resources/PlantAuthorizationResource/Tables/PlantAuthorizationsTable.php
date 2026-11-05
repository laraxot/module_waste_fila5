<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\PlantAuthorizationResource\Tables;

use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Waste\Enums\AuthorizationTypeEnum;
use Modules\Waste\Models\PlantAuthorization;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class PlantAuthorizationsTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'site' => TextColumn::make('site.name')->searchable()->sortable(),
            'type' => TextColumn::make('type')->badge(),
            'number' => TextColumn::make('number')->searchable(),
            'issuing_authority' => TextColumn::make('issuing_authority')->searchable()->toggleable(),
            'issued_at' => TextColumn::make('issued_at')->date()->sortable(),
            'expires_at' => TextColumn::make('expires_at')
                ->date()
                ->sortable()
                ->color(static fn (PlantAuthorization $record): string => $record->isValidOn(CarbonImmutable::today()) ? 'success' : 'danger'),
            'authorized_wastes_count' => TextColumn::make('authorized_wastes_count')->counts('authorizedWastes'),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'type' => SelectFilter::make('type')->options(AuthorizationTypeEnum::class),
            'is_active' => TernaryFilter::make('is_active'),
            'valid_today' => Filter::make('valid_today')
                ->query(static function (Builder $query): Builder {
                    /** @var Builder<PlantAuthorization> $query */
                    return (new PlantAuthorization)->scopeValidOn($query, CarbonImmutable::today());
                }),
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
