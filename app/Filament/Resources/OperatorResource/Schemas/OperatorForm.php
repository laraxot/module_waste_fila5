<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\OperatorResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Modules\Waste\Enums\OperatorRoleEnum;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class OperatorForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'business_name' => TextInput::make('business_name')->required()->maxLength(255),
            'fiscal_code' => TextInput::make('fiscal_code')->required()->maxLength(16),
            'vat_number' => TextInput::make('vat_number')->maxLength(20),
            'roles' => Select::make('roles')
                ->options(OperatorRoleEnum::class)
                ->multiple()
                ->required(),
            'rentri_registration_number' => TextInput::make('rentri_registration_number')->maxLength(255),
            'albo_registration_number' => TextInput::make('albo_registration_number')->maxLength(255),
            'email' => TextInput::make('email')->email()->maxLength(255),
            'pec' => TextInput::make('pec')->email()->maxLength(255),
            'phone' => TextInput::make('phone')->tel()->maxLength(255),
            'is_active' => Toggle::make('is_active')->default(true),
        ];
    }
}
