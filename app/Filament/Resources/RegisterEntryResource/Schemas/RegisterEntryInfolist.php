<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\RegisterEntryResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Waste\Models\RegisterEntry;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class RegisterEntryInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'registration' => Section::make('registration')
                ->schema([
                    'register' => TextEntry::make('register.name'),
                    'display_number' => TextEntry::make('number')
                        ->formatStateUsing(static fn (mixed $state, RegisterEntry $record): string => $record->displayNumber()),
                    'type' => TextEntry::make('type')->badge(),
                    'cause' => TextEntry::make('cause')->badge(),
                    'recorded_at' => TextEntry::make('recorded_at')->dateTime(),
                    'operation_at' => TextEntry::make('operation_at')->dateTime(),
                ])
                ->columns(3),
            'waste' => Section::make('waste')
                ->schema([
                    'ewc_code' => TextEntry::make('ewcCode.display_code'),
                    'ewc_description' => TextEntry::make('ewcCode.description'),
                    'physical_state' => TextEntry::make('physical_state')->badge(),
                    'hazard_properties' => TextEntry::make('hazard_properties')->badge(),
                    'quantity_kg' => TextEntry::make('quantity_kg')->numeric(decimalPlaces: 3),
                    'operation' => TextEntry::make('operation')->badge(),
                ])
                ->columns(3),
            'references' => Section::make('references')
                ->schema([
                    'fir' => TextEntry::make('fir.number'),
                    'counterpart' => TextEntry::make('counterpart.business_name'),
                    'load_entry_ids' => TextEntry::make('load_entry_ids')
                        ->formatStateUsing(static fn (mixed $state, RegisterEntry $record): string => RegisterEntry::query()
                            ->whereKey($record->load_entry_ids)
                            ->get()
                            ->map(static fn (RegisterEntry $load): string => $load->displayNumber())
                            ->implode(', ')),
                    'reference_type' => TextEntry::make('reference_type')
                        ->formatStateUsing(static fn (?string $state, RegisterEntry $record): string => $state === null ? '' : class_basename($state).' #'.$record->reference_id),
                    'rectified_entry' => TextEntry::make('rectifiedEntry.number')
                        ->formatStateUsing(static fn (mixed $state, RegisterEntry $record): string => $record->rectifiedEntry?->displayNumber() ?? ''),
                    'rectification_reason' => TextEntry::make('rectification_reason'),
                    'notes' => TextEntry::make('notes')->columnSpanFull(),
                ])
                ->columns(2),
            'transmission' => Section::make('transmission')
                ->schema([
                    'transmitted_at' => TextEntry::make('transmitted_at')->dateTime(),
                    'rentri_transmission_ref' => TextEntry::make('rentri_transmission_ref'),
                ])
                ->columns(2),
        ];
    }
}
