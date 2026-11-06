<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\PlantAuthorizationResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Modules\Waste\Enums\AuthorizationTypeEnum;
use Modules\Waste\Models\OperatorSite;
use Modules\Waste\Models\PlantAuthorization;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class PlantAuthorizationForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        /** @var list<string> $mimeTypes */
        $mimeTypes = array_values(array_filter(Config::array('waste.authorization_document.mime_types', []), is_string(...)));

        return [
            'operator_site_id' => Select::make('operator_site_id')
                ->relationship(
                    'site',
                    'name',
                    static fn (Builder $query): Builder => $query->where('is_plant', true),
                )
                ->getOptionLabelFromRecordUsing(static fn (OperatorSite $record): string => $record->name.' – '.$record->operator->business_name)
                ->searchable()
                ->preload()
                ->required(),
            'type' => Select::make('type')->options(AuthorizationTypeEnum::class)->required(),
            'number' => TextInput::make('number')->required()->maxLength(255),
            'issuing_authority' => TextInput::make('issuing_authority')->required()->maxLength(255),
            'issued_at' => DatePicker::make('issued_at')->required(),
            'expires_at' => DatePicker::make('expires_at')->after('issued_at'),
            'is_active' => Toggle::make('is_active')->default(true),
            'notes' => Textarea::make('notes')->rows(3)->columnSpanFull(),
            'document' => SpatieMediaLibraryFileUpload::make('document')
                ->collection(PlantAuthorization::DOCUMENT_COLLECTION)
                ->disk(PlantAuthorization::documentDisk())
                ->visibility('private')
                ->multiple()
                ->acceptedFileTypes($mimeTypes)
                ->downloadable()
                ->openable()
                ->columnSpanFull(),
        ];
    }
}
