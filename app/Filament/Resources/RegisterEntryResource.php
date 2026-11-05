<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Modules\Waste\Filament\Resources\RegisterEntryResource\Pages\ListRegisterEntries;
use Modules\Waste\Filament\Resources\RegisterEntryResource\Pages\ViewRegisterEntry;
use Modules\Waste\Models\RegisterEntry;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Registro di carico e scarico: solo elenco e dettaglio. Nessuna creazione né modifica dal pannello
 * (le scritture nascono dalle Actions di dominio), correzioni con l'azione "rettifica".
 */
class RegisterEntryResource extends XotBaseResource
{
    protected static ?string $model = RegisterEntry::class;

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
            'index' => ListRegisterEntries::route('/'),
            'view' => ViewRegisterEntry::route('/{record}'),
        ];
    }

    /**
     * Contratto di ExportXlsAction (Xot): percorso `data_get` => intestazione colonna.
     *
     * @param  array<array-key, mixed>  $data  filtri della tabella
     * @return array<int|string, string>
     */
    public static function getXlsFields(array $data): array
    {
        $fields = [
            'register.name' => 'register_id',
            'year' => 'year',
            'number' => 'number',
            'type' => 'type',
            'cause' => 'cause',
            'recorded_at' => 'recorded_at',
            'operation_at' => 'operation_at',
            'ewcCode.display_code' => 'ewc_code_id',
            'ewcCode.description' => 'ewc_description',
            'physical_state' => 'physical_state',
            'hazard_codes' => 'hazard_properties',
            'quantity_kg' => 'quantity_kg',
            'operation' => 'operation',
            'fir.number' => 'fir_id',
            'counterpart.business_name' => 'counterpart_operator_id',
            'counterpart.fiscal_code' => 'counterpart_fiscal_code',
            'rectifiedEntry.number' => 'rectifies_entry_id',
            'rectification_reason' => 'rectification_reason',
            'notes' => 'notes',
            'transmitted_at' => 'transmitted_at',
            'rentri_transmission_ref' => 'rentri_transmission_ref',
        ];

        return array_map(static fn (string $field): string => static::trans('fields.'.$field.'.label'), $fields);
    }
}
