<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\PlantAuthorizationResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Models\EwcCode;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

/**
 * Codici EER autorizzati dal titolo, con operazioni R/D e limiti (kg).
 */
class AuthorizedWastesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'authorizedWastes';

    /**
     * @return array<string, Component>
     */
    #[\Override]
    public function getFormSchema(): array
    {
        return [
            'ewc_code_id' => Select::make('ewc_code_id')
                ->relationship('ewcCode', 'code')
                ->getOptionLabelFromRecordUsing(static fn (EwcCode $record): string => $record->display_code.' – '.$record->description)
                ->searchable(['code', 'description'])
                ->required(),
            'operations' => Select::make('operations')
                ->options(WasteOperationEnum::class)
                ->multiple()
                ->required(),
            'max_storage_kg' => TextInput::make('max_storage_kg')->numeric()->minValue(0)->suffix('kg'),
            'max_annual_kg' => TextInput::make('max_annual_kg')->numeric()->minValue(0)->suffix('kg'),
            'notes' => Textarea::make('notes')->rows(2)->columnSpanFull(),
        ];
    }

    /**
     * @return array<string, Column>
     */
    #[\Override]
    public function getTableColumns(): array
    {
        return [
            'ewc_code' => TextColumn::make('ewcCode.display_code')->searchable(['code']),
            'ewc_description' => TextColumn::make('ewcCode.description')->limit(60)->wrap(),
            'operations' => TextColumn::make('operations')->badge(),
            'max_storage_kg' => TextColumn::make('max_storage_kg')->numeric(decimalPlaces: 3)->suffix(' kg'),
            'max_annual_kg' => TextColumn::make('max_annual_kg')->numeric(decimalPlaces: 3)->suffix(' kg'),
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
            'delete' => DeleteAction::make(),
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
