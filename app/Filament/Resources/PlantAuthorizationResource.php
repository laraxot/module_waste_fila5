<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Modules\Waste\Filament\Resources\PlantAuthorizationResource\Pages\CreatePlantAuthorization;
use Modules\Waste\Filament\Resources\PlantAuthorizationResource\Pages\EditPlantAuthorization;
use Modules\Waste\Filament\Resources\PlantAuthorizationResource\Pages\ListPlantAuthorizations;
use Modules\Waste\Filament\Resources\PlantAuthorizationResource\Pages\ViewPlantAuthorization;
use Modules\Waste\Filament\Resources\PlantAuthorizationResource\RelationManagers\AuthorizedWastesRelationManager;
use Modules\Waste\Models\PlantAuthorization;
use Modules\Xot\Filament\Resources\XotBaseResource;

class PlantAuthorizationResource extends XotBaseResource
{
    protected static ?string $model = PlantAuthorization::class;

    /**
     * @return array<int, class-string<RelationManager>>
     */
    #[\Override]
    public static function getRelations(): array
    {
        return [
            AuthorizedWastesRelationManager::class,
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListPlantAuthorizations::route('/'),
            'create' => CreatePlantAuthorization::route('/create'),
            'view' => ViewPlantAuthorization::route('/{record}'),
            'edit' => EditPlantAuthorization::route('/{record}/edit'),
        ];
    }
}
