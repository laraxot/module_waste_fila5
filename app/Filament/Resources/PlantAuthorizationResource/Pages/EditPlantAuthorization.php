<?php

declare(strict_types=1);

namespace Modules\Waste\Filament\Resources\PlantAuthorizationResource\Pages;

use Modules\Waste\Filament\Resources\PlantAuthorizationResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditPlantAuthorization extends XotBaseEditRecord
{
    protected static string $resource = PlantAuthorizationResource::class;
}
