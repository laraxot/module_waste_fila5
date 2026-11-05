<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\EwcCodeResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Modules\Waste\Models\EwcCode;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * I codici EER non si cancellano (sono referenziati da FIR, registro, autorizzazioni): si disattivano.
 */
class EwcCodesTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')
                ->formatStateUsing(static fn (string $state, EwcCode $record): string => $record->display_code)
                ->searchable()
                ->sortable(),
            'description' => TextColumn::make('description')->searchable()->limit(80)->wrap(),
            'chapter' => TextColumn::make('chapter')->sortable()->toggleable(isToggledHiddenByDefault: true),
            'is_hazardous' => IconColumn::make('is_hazardous')->boolean()->sortable(),
            'is_mirror' => IconColumn::make('is_mirror')->boolean(),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'chapter' => SelectFilter::make('chapter')
                ->options(static fn (): array => EwcCode::query()
                    ->distinct()
                    ->orderBy('chapter')
                    ->pluck('chapter', 'chapter')
                    ->all()),
            'is_hazardous' => TernaryFilter::make('is_hazardous'),
            'is_mirror' => TernaryFilter::make('is_mirror'),
            'is_active' => TernaryFilter::make('is_active'),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
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
