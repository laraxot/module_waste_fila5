<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\EwcCodeResource\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Modules\Waste\Actions\NormalizeEwcCodeAction;
use Modules\Waste\Exceptions\InvalidEwcCodeException;
use Modules\Waste\Models\EwcCode;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Il codice si scrive in qualunque formato accettato (191204, 19.12.04, 19 12 04*): viene normalizzato
 * e verificato qui e nel modello. Capitolo e sottocapitolo sono derivati, non si inseriscono.
 */
class EwcCodeForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'code' => TextInput::make('code')
                ->required()
                ->maxLength(10)
                ->rule(static fn (?EwcCode $record): \Closure => static function (string $attribute, mixed $value, \Closure $fail) use ($record): void {
                    try {
                        $code = app(NormalizeEwcCodeAction::class)->execute(is_string($value) ? $value : '');
                    } catch (InvalidEwcCodeException $exception) {
                        $fail($exception->getMessage());

                        return;
                    }

                    $duplicate = EwcCode::query()
                        ->where('code', $code)
                        ->when($record instanceof EwcCode, static fn ($query) => $query->whereKeyNot($record?->id))
                        ->exists();
                    if ($duplicate) {
                        $fail(static::trans('validation.duplicate_code', ['code' => $code]));
                    }
                }),
            'description' => Textarea::make('description')
                ->required()
                ->rows(3)
                ->columnSpanFull(),
            'is_hazardous' => Toggle::make('is_hazardous'),
            'is_mirror' => Toggle::make('is_mirror'),
            'is_active' => Toggle::make('is_active')->default(true),
        ];
    }
}
