<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Model;
use Modules\Waste\Enums\FirStatusEnum;
>>>>>>> laraxot/dev
use Modules\Waste\Filament\Resources\FirResource\Pages\CreateFir;
use Modules\Waste\Filament\Resources\FirResource\Pages\EditFir;
use Modules\Waste\Filament\Resources\FirResource\Pages\ListFirs;
use Modules\Waste\Filament\Resources\FirResource\Pages\ViewFir;
use Modules\Waste\Models\Fir;
use Modules\Xot\Filament\Resources\XotBaseResource;

<<<<<<< HEAD
=======
/**
 * Un FIR si modifica a mano solo in bozza, anche per il super-admin (che salta le policy):
 * la pagina di modifica e il bottone "modifica" spariscono dopo l'emissione. La stessa regola è
 * garantita dal modello (Fir::booted()).
 */
>>>>>>> laraxot/dev
class FirResource extends XotBaseResource
{
    protected static ?string $model = Fir::class;

<<<<<<< HEAD
=======
    #[\Override]
    public static function canEdit(Model $record): bool
    {
        return $record instanceof Fir
            && $record->status === FirStatusEnum::DRAFT
            && parent::canEdit($record);
    }

>>>>>>> laraxot/dev
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
