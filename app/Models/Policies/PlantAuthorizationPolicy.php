<?php

declare(strict_types=1);

namespace Modules\Waste\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Models\Policies\Concerns\DeniesUndeclaredFilamentAbilities;
use Modules\Xot\Models\Policies\Concerns\RequiresOperationalPermissions;

class PlantAuthorizationPolicy extends UserBasePolicy
{
    use DeniesUndeclaredFilamentAbilities;
    use RequiresOperationalPermissions;

    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('plant_authorization.viewAny');
    }

    public function view(UserContract $user): bool
    {
        return $user->hasPermissionTo('plant_authorization.view');
    }

    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('plant_authorization.create');
    }

    public function update(UserContract $user): bool
    {
        return $user->hasPermissionTo('plant_authorization.update');
    }

    public function delete(UserContract $user): bool
    {
        return $user->hasPermissionTo('plant_authorization.delete');
    }
}
