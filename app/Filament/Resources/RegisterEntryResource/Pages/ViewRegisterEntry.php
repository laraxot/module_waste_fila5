<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\RegisterEntryResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Modules\Waste\Filament\Actions\RectifyEntryAction;
use Modules\Waste\Filament\Resources\RegisterEntryResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewRegisterEntry extends XotBaseViewRecord
{
    protected static string $resource = RegisterEntryResource::class;

    /**
     * @return array<string, Action|ActionGroup>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            'rectify' => RectifyEntryAction::make('rectify'),
        ];
    }
}
