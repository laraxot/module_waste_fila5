<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\PlantAuthorizationResource\Pages;

use Modules\Waste\Filament\Resources\PlantAuthorizationResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListPlantAuthorizations extends XotBaseListRecords
{
    protected static string $resource = PlantAuthorizationResource::class;
}
