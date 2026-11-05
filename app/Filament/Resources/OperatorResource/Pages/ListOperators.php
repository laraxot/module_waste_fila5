<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\OperatorResource\Pages;

use Modules\Waste\Filament\Resources\OperatorResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListOperators extends XotBaseListRecords
{
    protected static string $resource = OperatorResource::class;
}
