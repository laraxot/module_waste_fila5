<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\OperatorResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class OperatorInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'business_name' => TextEntry::make('business_name'),
            'fiscal_code' => TextEntry::make('fiscal_code')->copyable(),
            'vat_number' => TextEntry::make('vat_number'),
            'roles' => TextEntry::make('roles')->badge(),
            'rentri_registration_number' => TextEntry::make('rentri_registration_number'),
            'albo_registration_number' => TextEntry::make('albo_registration_number'),
            'email' => TextEntry::make('email'),
            'pec' => TextEntry::make('pec'),
            'phone' => TextEntry::make('phone'),
            'is_active' => IconEntry::make('is_active')->boolean(),
        ];
    }
}
