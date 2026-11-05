<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\OperatorSiteResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class OperatorSiteInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'operator' => TextEntry::make('operator.business_name'),
            'name' => TextEntry::make('name'),
            'rentri_site_number' => TextEntry::make('rentri_site_number'),
            'address' => TextEntry::make('address.full_address'),
            'is_plant' => IconEntry::make('is_plant')->boolean(),
            'is_active' => IconEntry::make('is_active')->boolean(),
        ];
    }
}
