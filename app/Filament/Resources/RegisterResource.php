<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Modules\Waste\Filament\Resources\RegisterResource\Pages\CreateRegister;
use Modules\Waste\Filament\Resources\RegisterResource\Pages\EditRegister;
use Modules\Waste\Filament\Resources\RegisterResource\Pages\ListRegisters;
use Modules\Waste\Filament\Resources\RegisterResource\Pages\ViewRegister;
use Modules\Waste\Filament\Resources\RegisterResource\RelationManagers\RegisterEntriesRelationManager;
use Modules\Waste\Models\Register;
use Modules\Xot\Filament\Resources\XotBaseResource;

class RegisterResource extends XotBaseResource
{
    protected static ?string $model = Register::class;

    /**
     * @return array<int, class-string<RelationManager>>
     */
    #[\Override]
    public static function getRelations(): array
    {
        return [
            RegisterEntriesRelationManager::class,
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListRegisters::route('/'),
            'create' => CreateRegister::route('/create'),
            'view' => ViewRegister::route('/{record}'),
            'edit' => EditRegister::route('/{record}/edit'),
        ];
    }
}
