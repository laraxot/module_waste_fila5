<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\RegisterResource\Pages;

use Modules\Waste\Filament\Resources\RegisterResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditRegister extends XotBaseEditRecord
{
    protected static string $resource = RegisterResource::class;
}
