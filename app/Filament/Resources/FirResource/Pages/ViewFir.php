<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\FirResource\Pages;

use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Modules\Waste\Actions\ChangeFirStatusAction;
use Modules\Waste\Actions\RecordFirAcceptanceAction;
use Modules\Waste\Enums\AcceptanceEnum;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Exceptions\WasteDomainException;
use Modules\Waste\Filament\Resources\FirResource;
use Modules\Waste\Models\Fir;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

/**
 * Le azioni della pagina chiamano le Actions di dominio: cambio stato (ChangeFirStatusAction)
 * e accettazione a destino (RecordFirAcceptanceAction).
 */
class ViewFir extends XotBaseViewRecord
{
    protected static string $resource = FirResource::class;

    /**
     * @return array<string, Action|ActionGroup>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            'edit' => EditAction::make(),
            'change_status' => Action::make('change_status')
                ->icon('heroicon-o-arrow-path')
                ->authorize('changeStatus')
                ->schema([
                    'status' => Select::make('status')
                        ->options($this->nextStatusOptions(...))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $status = FirStatusEnum::tryFrom(is_string($data['status'] ?? null) ? $data['status'] : '');
                    if ($status === null) {
                        return;
                    }

                    $this->runDomainAction(fn (Fir $fir): Fir => app(ChangeFirStatusAction::class)->execute($fir, $status));
                }),
            'record_acceptance' => Action::make('record_acceptance')
                ->icon('heroicon-o-check-circle')
                ->authorize('changeStatus')
                ->visible(fn (): bool => in_array($this->getFir()->status, [FirStatusEnum::ISSUED, FirStatusEnum::IN_TRANSIT, FirStatusEnum::DELIVERED], true))
                ->schema([
                    'acceptance' => Select::make('acceptance')
                        ->options(AcceptanceEnum::class)
                        ->live()
                        ->required(),
                    'accepted_kg' => TextInput::make('accepted_kg')->numeric()->minValue(0)->required(),
                    'arrived_at' => DateTimePicker::make('arrived_at')->default(now())->required(),
                    'reason' => Textarea::make('reason')
                        ->rows(2)
                        ->required(static fn (Get $get): bool => AcceptanceEnum::tryFrom(self::stringOf($get('acceptance')))?->requiresReason() ?? false),
                ])
                ->action(function (array $data): void {
                    $acceptance = AcceptanceEnum::tryFrom(self::stringOf($data['acceptance'] ?? null));
                    $acceptedKg = $data['accepted_kg'] ?? null;
                    $arrivedAt = $data['arrived_at'] ?? null;
                    if ($acceptance === null || ! is_numeric($acceptedKg) || ! is_string($arrivedAt)) {
                        return;
                    }
                    $reason = isset($data['reason']) && is_string($data['reason']) ? $data['reason'] : null;

                    $this->runDomainAction(fn (Fir $fir): Fir => app(RecordFirAcceptanceAction::class)->execute(
                        $fir,
                        $acceptance,
                        (float) $acceptedKg,
                        CarbonImmutable::parse($arrivedAt),
                        $reason,
                    ));
                }),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function nextStatusOptions(): array
    {
        $options = [];
        foreach ($this->getFir()->status->allowedTransitions() as $status) {
            if (! $status->isAcceptanceOutcome()) {
                $options[$status->value] = $status->getLabel();
            }
        }

        return $options;
    }

    /**
     * @param  \Closure(Fir): Fir  $callback
     */
    private function runDomainAction(\Closure $callback): void
    {
        try {
            $this->record = $callback($this->getFir());
            Notification::make()->success()->title(FirResource::trans('actions.done.success'))->send();
        } catch (WasteDomainException $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();
        }
    }

    private function getFir(): Fir
    {
        $record = $this->getRecord();
        if (! $record instanceof Fir) {
            throw new \LogicException('ViewFir record must be a Fir.');
        }

        return $record;
    }

    private static function stringOf(mixed $value): string
    {
        if ($value instanceof \BackedEnum) {
            return (string) $value->value;
        }

        return is_string($value) ? $value : '';
    }
}
