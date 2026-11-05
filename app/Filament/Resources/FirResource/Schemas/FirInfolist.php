<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\FirResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class FirInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'identification' => Section::make('identification')
                ->schema([
                    'number' => TextEntry::make('number'),
                    'status' => TextEntry::make('status')->badge(),
                    'is_digital' => IconEntry::make('is_digital')->boolean(),
                ])
                ->columns(3),
            'subjects' => Section::make('subjects')
                ->schema([
                    'producer' => TextEntry::make('producer.business_name'),
                    'producer_site' => TextEntry::make('producerSite.name'),
                    'recipient' => TextEntry::make('recipient.business_name'),
                    'recipient_site' => TextEntry::make('recipientSite.name'),
                    'transporter' => TextEntry::make('transporter.business_name'),
                    'intermediary' => TextEntry::make('intermediary.business_name'),
                ])
                ->columns(2),
            'waste' => Section::make('waste')
                ->schema([
                    'ewc_code' => TextEntry::make('ewcCode.display_code'),
                    'ewc_description' => TextEntry::make('ewcCode.description'),
                    'waste_description' => TextEntry::make('waste_description'),
                    'physical_state' => TextEntry::make('physical_state')->badge(),
                    'hazard_properties' => TextEntry::make('hazard_properties')->badge(),
                    'operation' => TextEntry::make('operation')->badge(),
                    'quantity' => TextEntry::make('quantity')->numeric(decimalPlaces: 3),
                    'quantity_unit' => TextEntry::make('quantity_unit'),
                    'is_quantity_estimated' => IconEntry::make('is_quantity_estimated')->boolean(),
                    'adr_un_number' => TextEntry::make('adr_un_number'),
                ])
                ->columns(2),
            'transport' => Section::make('transport')
                ->schema([
                    'vehicle_plate' => TextEntry::make('vehicle_plate'),
                    'trailer_plate' => TextEntry::make('trailer_plate'),
                    'driver_name' => TextEntry::make('driver_name'),
                    'transport_started_at' => TextEntry::make('transport_started_at')->dateTime(),
                ])
                ->columns(2),
            'acceptance_section' => Section::make('acceptance_section')
                ->schema([
                    'arrived_at' => TextEntry::make('arrived_at')->dateTime(),
                    'acceptance' => TextEntry::make('acceptance')->badge(),
                    'accepted_quantity_kg' => TextEntry::make('accepted_quantity_kg')->numeric(decimalPlaces: 3),
                    'rejection_reason' => TextEntry::make('rejection_reason'),
                ])
                ->columns(2),
            'notes' => TextEntry::make('notes')->columnSpanFull(),
        ];
    }
}
