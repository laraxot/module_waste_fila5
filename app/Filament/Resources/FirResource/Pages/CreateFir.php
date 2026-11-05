<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\FirResource\Pages;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Modules\Waste\Actions\CreateFirAction;
use Modules\Waste\Datas\FirData;
use Modules\Waste\Exceptions\WasteDomainException;
use Modules\Waste\Filament\Resources\FirResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

/**
 * La creazione passa da CreateFirAction: le regole di dominio (ruoli, coerenza EER/HP, stato iniziale)
 * valgono anche per i FIR inseriti dal pannello.
 */
class CreateFir extends XotBaseCreateRecord
{
    protected static string $resource = FirResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    #[\Override]
    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(CreateFirAction::class)->execute(FirData::from($data));
        } catch (WasteDomainException $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();
            $this->halt();

            throw $exception;
        }
    }
}
