<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\OperatorSiteResource\Pages;

use Modules\Waste\Filament\Resources\OperatorSiteResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreateOperatorSite extends XotBaseCreateRecord
{
    protected static string $resource = OperatorSiteResource::class;
}
