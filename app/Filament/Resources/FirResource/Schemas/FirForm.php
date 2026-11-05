<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\FirResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Builder;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\OperatorRoleEnum;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\QuantityUnitEnum;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Models\EwcCode;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Form del formulario. In creazione i dati passano da CreateFirAction (validazioni di dominio);
 * in modifica (solo bozza, vedi FirPolicy) lo stato non è modificabile: cambia con le azioni della pagina di dettaglio.
 */
class FirForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'identification' => Section::make('identification')
                ->schema([
                    'number' => TextInput::make('number')->maxLength(255),
                    'is_digital' => Toggle::make('is_digital'),
                    'status' => Select::make('status')
                        ->options(array_filter(
                            FirStatusEnum::toArray(),
                            static fn (int|string $value): bool => FirStatusEnum::from((string) $value)->isInitial(),
                            ARRAY_FILTER_USE_KEY,
                        ))
                        ->default(FirStatusEnum::DRAFT->value)
                        ->required()
                        ->visibleOn('create'),
                ])
                ->columns(3),
            'subjects' => Section::make('subjects')
                ->schema([
                    'producer_operator_id' => self::operatorSelect('producer_operator_id', 'producer', OperatorRoleEnum::PRODUCER, OperatorRoleEnum::HOLDER)
                        ->required(),
                    'producer_site_id' => self::siteSelect('producer_site_id', 'producerSite', 'producer_operator_id'),
                    'recipient_operator_id' => self::operatorSelect('recipient_operator_id', 'recipient', OperatorRoleEnum::RECIPIENT)
                        ->required(),
                    'recipient_site_id' => self::siteSelect('recipient_site_id', 'recipientSite', 'recipient_operator_id'),
                    'transporter_operator_id' => self::operatorSelect('transporter_operator_id', 'transporter', OperatorRoleEnum::TRANSPORTER),
                    'intermediary_operator_id' => self::operatorSelect('intermediary_operator_id', 'intermediary', OperatorRoleEnum::INTERMEDIARY, OperatorRoleEnum::DEALER),
                ])
                ->columns(2),
            'waste' => Section::make('waste')
                ->schema([
                    'ewc_code_id' => Select::make('ewc_code_id')
                        ->relationship('ewcCode', 'code', static fn (Builder $query): Builder => $query->where('is_active', true))
                        ->getOptionLabelFromRecordUsing(static fn (EwcCode $record): string => $record->display_code.' – '.$record->description)
                        ->searchable(['code', 'description'])
                        ->live()
                        ->required(),
                    'waste_description' => TextInput::make('waste_description')->maxLength(255),
                    'physical_state' => Select::make('physical_state')->options(PhysicalStateEnum::class)->required(),
                    'hazard_properties' => Select::make('hazard_properties')
                        ->options(HazardPropertyEnum::class)
                        ->multiple()
                        ->required(static fn (Get $get): bool => self::isHazardousEwc($get('ewc_code_id')))
                        ->visible(static fn (Get $get): bool => self::isHazardousEwc($get('ewc_code_id'))),
                    'operation' => Select::make('operation')->options(WasteOperationEnum::class)->required(),
                    'quantity' => TextInput::make('quantity')->numeric()->minValue(0.001)->required(),
                    'quantity_unit' => Select::make('quantity_unit')
                        ->options(QuantityUnitEnum::class)
                        ->default(QuantityUnitEnum::KG->value)
                        ->required(),
                    'is_quantity_estimated' => Toggle::make('is_quantity_estimated'),
                    'adr_un_number' => TextInput::make('adr_un_number')->maxLength(8),
                ])
                ->columns(2),
            'transport' => Section::make('transport')
                ->schema([
                    'vehicle_plate' => TextInput::make('vehicle_plate')->maxLength(16),
                    'trailer_plate' => TextInput::make('trailer_plate')->maxLength(16),
                    'driver_name' => TextInput::make('driver_name')->maxLength(255),
                    'transport_started_at' => DateTimePicker::make('transport_started_at'),
                ])
                ->columns(2),
            'notes' => Textarea::make('notes')->rows(3)->columnSpanFull(),
        ];
    }

    private static function isHazardousEwc(mixed $ewcCodeId): bool
    {
        if (! is_numeric($ewcCodeId)) {
            return false;
        }

        return EwcCode::query()->whereKey((int) $ewcCodeId)->where('is_hazardous', true)->exists();
    }

    private static function operatorSelect(string $name, string $relationship, OperatorRoleEnum ...$roles): Select
    {
        return Select::make($name)
            ->relationship(
                $relationship,
                'business_name',
                static function (Builder $query) use ($roles): Builder {
                    return $query
                        ->where('is_active', true)
                        ->where(static function (Builder $q) use ($roles): void {
                            foreach ($roles as $role) {
                                $q->orWhereJsonContains('roles', $role->value);
                            }
                        });
                },
            )
            ->searchable()
            ->preload()
            ->live();
    }

    private static function siteSelect(string $name, string $relationship, string $operatorField): Select
    {
        return Select::make($name)
            ->relationship(
                $relationship,
                'name',
                static fn (Builder $query, Get $get): Builder => $query->where('operator_id', $get($operatorField)),
            )
            ->searchable()
            ->preload();
    }
}
