<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\RegisterResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Modules\Waste\Models\OperatorSite;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class RegisterForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'operator_site_id' => Select::make('operator_site_id')
                ->relationship('site', 'name')
                ->getOptionLabelFromRecordUsing(static fn (OperatorSite $record): string => $record->name.' – '.$record->operator->business_name)
                ->searchable()
                ->preload()
                ->required()
                ->disabledOn('edit'),
            'name' => TextInput::make('name')->required()->maxLength(255),
            'rentri_identifier' => TextInput::make('rentri_identifier')->maxLength(255),
            'is_active' => Toggle::make('is_active')->default(true),
        ];
    }
}
