<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Modules\Waste\Filament\Resources\OperatorResource\Pages\CreateOperator;
use Modules\Waste\Filament\Resources\OperatorResource\Pages\EditOperator;
use Modules\Waste\Filament\Resources\OperatorResource\Pages\ListOperators;
use Modules\Waste\Filament\Resources\OperatorResource\Pages\ViewOperator;
use Modules\Waste\Filament\Resources\OperatorResource\RelationManagers\OperatorSitesRelationManager;
use Modules\Waste\Models\Operator;
use Modules\Xot\Filament\Resources\XotBaseResource;

class OperatorResource extends XotBaseResource
{
    protected static ?string $model = Operator::class;

    /**
     * @return array<int, class-string<RelationManager>>
     */
    #[\Override]
    public static function getRelations(): array
    {
        return [
            OperatorSitesRelationManager::class,
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListOperators::route('/'),
            'create' => CreateOperator::route('/create'),
            'view' => ViewOperator::route('/{record}'),
            'edit' => EditOperator::route('/{record}/edit'),
        ];
    }
}
