<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\EwcCodeResource\Pages;

use Modules\Waste\Filament\Resources\EwcCodeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreateEwcCode extends XotBaseCreateRecord
{
    protected static string $resource = EwcCodeResource::class;
}
