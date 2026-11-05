<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\OperatorResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Waste\Filament\Resources\OperatorSiteResource\Schemas\OperatorSiteForm;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

/**
 * Unità locali del soggetto. Il form è quello di OperatorSiteResource senza la scelta del soggetto
 * (lo fissa la relazione).
 */
class OperatorSitesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'sites';

    protected static ?string $recordTitleAttribute = 'name';

    /**
     * @return array<string, Component>
     */
    #[\Override]
    public function getFormSchema(): array
    {
        $schema = app(OperatorSiteForm::class)->getFormSchema();
        unset($schema['operator_id']);

        return $schema;
    }

    /**
     * @return array<string, Column>
     */
    #[\Override]
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')->searchable(),
            'rentri_site_number' => TextColumn::make('rentri_site_number'),
            'address' => TextColumn::make('address.full_address'),
            'is_plant' => IconColumn::make('is_plant')->boolean(),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, Action>
     */
    #[\Override]
    public function getTableHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Action>
     */
    #[\Override]
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
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
