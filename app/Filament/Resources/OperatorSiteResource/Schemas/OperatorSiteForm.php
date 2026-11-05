<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\OperatorSiteResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Geo\Filament\Resources\AddressResource\Schemas\AddressForm;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * L'indirizzo è l'Address di Geo (relazione `address` di HasAddress): si riusa il form di Geo
 * (regione → provincia → comune → CAP), senza nome e flag "principale", che qui è sempre vero.
 */
class OperatorSiteForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        $addressSchema = app(AddressForm::class)->getFormSchema();
        unset($addressSchema['name'], $addressSchema['is_primary']);

        return [
            'operator_id' => Select::make('operator_id')
                ->relationship('operator', 'business_name')
                ->searchable()
                ->preload()
                ->required(),
            'name' => TextInput::make('name')->required()->maxLength(255),
            'rentri_site_number' => TextInput::make('rentri_site_number')->maxLength(255),
            'is_plant' => Toggle::make('is_plant'),
            'is_active' => Toggle::make('is_active')->default(true),
            'address' => Section::make('address')
                ->relationship('address')
                ->mutateRelationshipDataBeforeCreateUsing(static fn (array $data): array => [...$data, 'is_primary' => true])
                ->schema($addressSchema)
                ->columns(2)
                ->columnSpanFull(),
        ];
    }
}
