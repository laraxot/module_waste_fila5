<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Modules\Waste\Filament\Resources\EwcCodeResource\Pages\CreateEwcCode;
use Modules\Waste\Filament\Resources\EwcCodeResource\Pages\EditEwcCode;
use Modules\Waste\Filament\Resources\EwcCodeResource\Pages\ListEwcCodes;
use Modules\Waste\Models\EwcCode;
use Modules\Xot\Filament\Resources\XotBaseResource;

class EwcCodeResource extends XotBaseResource
{
    protected static ?string $model = EwcCode::class;

    /**
     * @return array<string, class-string<RelationManager>>
     */
    #[\Override]
    public static function getRelations(): array
    {
        return [];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListEwcCodes::route('/'),
            'create' => CreateEwcCode::route('/create'),
            'edit' => EditEwcCode::route('/{record}/edit'),
        ];
    }
}
