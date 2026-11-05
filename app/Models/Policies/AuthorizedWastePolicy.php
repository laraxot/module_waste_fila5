<?php

declare(strict_types=1);

namespace Modules\Waste\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;

class AuthorizedWastePolicy extends UserBasePolicy
{
    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('authorized_waste.viewAny');
    }

    public function view(UserContract $user): bool
    {
        return $user->hasPermissionTo('authorized_waste.view');
    }

    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('authorized_waste.create');
    }

    public function update(UserContract $user): bool
    {
        return $user->hasPermissionTo('authorized_waste.update');
    }

    public function delete(UserContract $user): bool
    {
        return $user->hasPermissionTo('authorized_waste.delete');
    }
}
