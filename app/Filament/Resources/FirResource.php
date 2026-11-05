<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Modules\Waste\Filament\Resources\FirResource\Pages\CreateFir;
use Modules\Waste\Filament\Resources\FirResource\Pages\EditFir;
use Modules\Waste\Filament\Resources\FirResource\Pages\ListFirs;
use Modules\Waste\Filament\Resources\FirResource\Pages\ViewFir;
use Modules\Waste\Models\Fir;
use Modules\Xot\Filament\Resources\XotBaseResource;

class FirResource extends XotBaseResource
{
    protected static ?string $model = Fir::class;

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
            'index' => ListFirs::route('/'),
            'create' => CreateFir::route('/create'),
            'view' => ViewFir::route('/{record}'),
            'edit' => EditFir::route('/{record}/edit'),
        ];
    }
}
