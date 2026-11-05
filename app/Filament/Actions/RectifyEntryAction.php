<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Actions;

use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Modules\Waste\Actions\RectifyRegisterEntryAction;
use Modules\Waste\Datas\RegisterEntryData;
use Modules\Waste\Exceptions\WasteDomainException;
use Modules\Waste\Filament\Resources\RegisterEntryResource;
use Modules\Waste\Filament\Resources\RegisterEntryResource\Schemas\RegisterEntryForm;
use Modules\Waste\Models\RegisterEntry;
use Modules\Xot\Filament\Actions\XotBaseAction;

/**
 * Bottone "rettifica" di una scrittura del registro (tabella, relation manager, pagina di dettaglio).
 * Precompila i dati della scrittura, chiede la motivazione e chiama RectifyRegisterEntryAction;
 * a rettifica avvenuta apre la nuova scrittura.
 */
class RectifyEntryAction extends XotBaseAction
{
    public static function getDefaultName(): ?string
    {
        return 'rectify';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->icon('heroicon-o-pencil-square')
            ->color('warning')
            ->authorize('rectify')
            ->fillForm(static fn (RegisterEntry $record): array => [
                'cause' => $record->cause->value,
                'ewc_code_id' => $record->ewc_code_id,
                'quantity_kg' => $record->quantity_kg,
                'physical_state' => $record->physical_state->value,
                'hazard_properties' => $record->hazard_properties->map(static fn (\BackedEnum $hp): string => (string) $hp->value)->all(),
                'operation' => $record->operation?->value,
                'operation_at' => $record->operation_at->format('Y-m-d H:i:s'),
                'fir_id' => $record->fir_id,
                'counterpart_operator_id' => $record->counterpart_operator_id,
                'load_entry_ids' => $record->load_entry_ids,
                'notes' => $record->notes,
            ])
            ->schema(static fn (): array => [
                ...app(RegisterEntryForm::class)->getFormSchema(),
                'reason' => Textarea::make('reason')->required()->rows(3)->columnSpanFull(),
            ])
            ->action(static function (RegisterEntry $record, array $data, RectifyEntryAction $action): void {
                $reason = is_string($data['reason'] ?? null) ? $data['reason'] : '';
                $loadIds = is_array($data['load_entry_ids'] ?? null) ? $data['load_entry_ids'] : [];

                try {
                    $corrected = RegisterEntryData::from([
                        'register_id' => $record->register_id,
                        'type' => $record->type,
                        'cause' => $data['cause'] ?? null,
                        'ewc_code_id' => $data['ewc_code_id'] ?? null,
                        'quantity_kg' => $data['quantity_kg'] ?? null,
                        'physical_state' => $data['physical_state'] ?? null,
                        'operation_at' => $data['operation_at'] ?? null,
                        'hazard_properties' => $data['hazard_properties'] ?? [],
                        'operation' => $data['operation'] ?? null,
                        'fir_id' => $data['fir_id'] ?? null,
                        'counterpart_operator_id' => $data['counterpart_operator_id'] ?? null,
                        'reference' => $record->reference_type !== null ? $record->reference : null,
                        'load_entry_ids' => array_values(array_map(static fn (mixed $id): int => is_numeric($id) ? (int) $id : 0, $loadIds)),
                        'notes' => $data['notes'] ?? null,
                    ]);

                    $rectification = app(RectifyRegisterEntryAction::class)->execute($record, $corrected, $reason);
                } catch (WasteDomainException $exception) {
                    Notification::make()->danger()->title($exception->getMessage())->send();
                    $action->halt();

                    return;
                }

                Notification::make()
                    ->success()
                    ->title(RegisterEntryResource::trans('actions.rectify.success', false, ['number' => $rectification->displayNumber()]))
                    ->send();
                $action->redirect(RegisterEntryResource::getUrl('view', ['record' => $rectification]));
            });
    }
}
