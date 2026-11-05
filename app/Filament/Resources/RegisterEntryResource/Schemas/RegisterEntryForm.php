<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\RegisterEntryResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Modules\Waste\Enums\HazardPropertyEnum;
use Modules\Waste\Enums\PhysicalStateEnum;
use Modules\Waste\Enums\RegisterCauseEnum;
use Modules\Waste\Enums\RegisterEntryTypeEnum;
use Modules\Waste\Enums\WasteOperationEnum;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\Fir;
use Modules\Waste\Models\Operator;
use Modules\Waste\Models\RegisterEntry;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Campi di una scrittura del registro, usati dall'azione "rettifica" (RectifyEntryAction): il registro
 * non ha pagine di creazione/modifica. Registro e tipo sono quelli della scrittura rettificata.
 *
 * Le select usano `options()` e non `relationship()`: in una modale d'azione legata al record,
 * `relationship()` salverebbe la relazione sulla scrittura originale, che è immutabile.
 */
class RegisterEntryForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'cause' => Select::make('cause')
                ->options(static function (?RegisterEntry $record): array {
                    $options = [];
                    foreach (RegisterCauseEnum::cases() as $cause) {
                        if ($record === null || $cause->allowsType($record->type)) {
                            $options[$cause->value] = $cause->getLabel();
                        }
                    }

                    return $options;
                })
                ->live()
                ->required(),
            'ewc_code_id' => Select::make('ewc_code_id')
                ->options(static fn (): array => EwcCode::query()
                    ->where('is_active', true)
                    ->orderBy('code')
                    ->get()
                    ->mapWithKeys(static fn (EwcCode $ewc): array => [$ewc->id => $ewc->display_code.' – '.$ewc->description])
                    ->all())
                ->searchable()
                ->live()
                ->required(),
            'quantity_kg' => TextInput::make('quantity_kg')->numeric()->minValue(0.001)->suffix('kg')->required(),
            'physical_state' => Select::make('physical_state')->options(PhysicalStateEnum::class)->required(),
            'hazard_properties' => Select::make('hazard_properties')
                ->options(HazardPropertyEnum::class)
                ->multiple()
                ->required(static fn (Get $get): bool => self::isHazardousEwc($get('ewc_code_id')))
                ->visible(static fn (Get $get): bool => self::isHazardousEwc($get('ewc_code_id'))),
            'operation' => Select::make('operation')
                ->options(WasteOperationEnum::class)
                ->required(static fn (Get $get): bool => RegisterCauseEnum::tryFrom(self::stringOf($get('cause')))?->requiresOperation() ?? false),
            'operation_at' => DateTimePicker::make('operation_at')->required()->maxDate(now()),
            'fir_id' => Select::make('fir_id')
                ->options(static fn (Get $get): array => Fir::query()
                    ->where('ewc_code_id', is_numeric($get('ewc_code_id')) ? (int) $get('ewc_code_id') : 0)
                    ->orderByDesc('id')
                    ->limit(200)
                    ->get()
                    ->mapWithKeys(static fn (Fir $fir): array => [$fir->id => $fir->displayNumber()])
                    ->all())
                ->searchable(),
            'counterpart_operator_id' => Select::make('counterpart_operator_id')
                ->options(static fn (): array => Operator::query()
                    ->where('is_active', true)
                    ->orderBy('business_name')
                    ->pluck('business_name', 'id')
                    ->all())
                ->searchable()
                ->required(static fn (Get $get): bool => RegisterCauseEnum::tryFrom(self::stringOf($get('cause')))?->requiresCounterpart() ?? false),
            'load_entry_ids' => Select::make('load_entry_ids')
                ->options(static fn (?RegisterEntry $record, Get $get): array => $record === null ? [] : RegisterEntry::query()
                    ->effective()
                    ->where('register_id', $record->register_id)
                    ->where('type', RegisterEntryTypeEnum::LOAD->value)
                    ->where('ewc_code_id', is_numeric($get('ewc_code_id')) ? (int) $get('ewc_code_id') : 0)
                    ->orderByDesc('id')
                    ->limit(500)
                    ->get()
                    ->mapWithKeys(static fn (RegisterEntry $load): array => [$load->id => $load->displayNumber().' – '.$load->quantity_kg.' kg'])
                    ->all())
                ->multiple()
                ->searchable()
                ->visible(static fn (?RegisterEntry $record): bool => $record?->type === RegisterEntryTypeEnum::UNLOAD)
                ->required(static fn (?RegisterEntry $record): bool => $record?->type === RegisterEntryTypeEnum::UNLOAD),
            'notes' => Textarea::make('notes')->rows(2)->columnSpanFull(),
        ];
    }

    private static function isHazardousEwc(mixed $ewcCodeId): bool
    {
        if (! is_numeric($ewcCodeId)) {
            return false;
        }

        return EwcCode::query()->whereKey((int) $ewcCodeId)->where('is_hazardous', true)->exists();
    }

    private static function stringOf(mixed $value): string
    {
        if ($value instanceof \BackedEnum) {
            return (string) $value->value;
        }

        return is_string($value) ? $value : '';
    }
}
