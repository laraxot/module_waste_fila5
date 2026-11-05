<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\PlantAuthorizationResource\Schemas;

use Carbon\CarbonImmutable;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Waste\Models\PlantAuthorization;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class PlantAuthorizationInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'site' => TextEntry::make('site.name'),
            'type' => TextEntry::make('type')->badge(),
            'number' => TextEntry::make('number'),
            'issuing_authority' => TextEntry::make('issuing_authority'),
            'issued_at' => TextEntry::make('issued_at')->date(),
            'expires_at' => TextEntry::make('expires_at')
                ->date()
                ->color(static fn (PlantAuthorization $record): string => $record->isValidOn(CarbonImmutable::today()) ? 'success' : 'danger'),
            'is_active' => IconEntry::make('is_active')->boolean(),
            'notes' => TextEntry::make('notes')->columnSpanFull(),
        ];
    }
}
