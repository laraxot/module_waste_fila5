<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\EwcCodeResource\Pages;

use Modules\Waste\Filament\Resources\EwcCodeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditEwcCode extends XotBaseEditRecord
{
    protected static string $resource = EwcCodeResource::class;
}
