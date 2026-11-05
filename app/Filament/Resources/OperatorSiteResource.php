<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Modules\Waste\Filament\Resources\OperatorSiteResource\Pages\CreateOperatorSite;
use Modules\Waste\Filament\Resources\OperatorSiteResource\Pages\EditOperatorSite;
use Modules\Waste\Filament\Resources\OperatorSiteResource\Pages\ListOperatorSites;
use Modules\Waste\Filament\Resources\OperatorSiteResource\Pages\ViewOperatorSite;
use Modules\Waste\Models\OperatorSite;
use Modules\Xot\Filament\Resources\XotBaseResource;

class OperatorSiteResource extends XotBaseResource
{
    protected static ?string $model = OperatorSite::class;

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
            'index' => ListOperatorSites::route('/'),
            'create' => CreateOperatorSite::route('/create'),
            'view' => ViewOperatorSite::route('/{record}'),
            'edit' => EditOperatorSite::route('/{record}/edit'),
        ];
    }
}
