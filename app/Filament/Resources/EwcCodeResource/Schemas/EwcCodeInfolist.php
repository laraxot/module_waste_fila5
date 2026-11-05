<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\EwcCodeResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class EwcCodeInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'display_code' => TextEntry::make('display_code'),
            'chapter' => TextEntry::make('chapter'),
            'sub_chapter' => TextEntry::make('sub_chapter'),
            'description' => TextEntry::make('description')->columnSpanFull(),
            'is_hazardous' => IconEntry::make('is_hazardous')->boolean(),
            'is_mirror' => IconEntry::make('is_mirror')->boolean(),
            'is_active' => IconEntry::make('is_active')->boolean(),
        ];
    }
}
