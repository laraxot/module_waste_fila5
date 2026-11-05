<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\OperatorSiteResource\Pages;

use Modules\Waste\Filament\Resources\OperatorSiteResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListOperatorSites extends XotBaseListRecords
{
    protected static string $resource = OperatorSiteResource::class;
}
