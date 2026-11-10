<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\EwcCodeResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Modules\Waste\Actions\ImportEwcCodesFromCsvAction;
use Modules\Waste\Exceptions\WasteDomainException;
use Modules\Waste\Filament\Resources\EwcCodeResource;
use Modules\Waste\Models\EwcCode;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListEwcCodes extends XotBaseListRecords
{
    protected static string $resource = EwcCodeResource::class;

    /**
     * @return array<string, Action|ActionGroup>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        $disk = Config::string('waste.ewc_import.disk', 'local');
        $directory = Config::string('waste.ewc_import.directory', 'waste/imports');

        return [
            ...parent::getHeaderActions(),
            'import_csv' => Action::make('import_csv')
                ->icon('heroicon-o-arrow-up-tray')
                ->authorize('import')
                ->schema([
                    'file' => FileUpload::make('file')
                        ->required()
                        ->disk($disk)
                        ->directory($directory)
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel']),
                ])
                ->action(static function (array $data) use ($disk): void {
                    Gate::authorize('import', EwcCode::class);
                    $file = $data['file'] ?? null;
                    if (! is_string($file)) {
                        return;
                    }

                    try {
                        $count = app(ImportEwcCodesFromCsvAction::class)->execute(Storage::disk($disk)->path($file));
                        Notification::make()
                            ->success()
                            ->title(static::trans('actions.import_csv.success', ['count' => $count]))
                            ->send();
                    } catch (WasteDomainException $exception) {
                        Notification::make()
                            ->danger()
                            ->title(static::trans('actions.import_csv.error'))
                            ->body($exception->getMessage())
                            ->send();
                    } finally {
                        Storage::disk($disk)->delete($file);
                    }
                }),
        ];
    }
}
