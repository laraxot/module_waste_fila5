<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\RegisterResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class RegisterInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'site' => TextEntry::make('site.name'),
            'name' => TextEntry::make('name'),
            'rentri_identifier' => TextEntry::make('rentri_identifier'),
            'is_active' => IconEntry::make('is_active')->boolean(),
        ];
    }
}
