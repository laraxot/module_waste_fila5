<?php

declare(strict_types=1);

namespace Modules\Waste\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Models\Policies\Concerns\DeniesUndeclaredFilamentAbilities;

class RegisterPolicy extends UserBasePolicy
{
    use DeniesUndeclaredFilamentAbilities;

    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('register.viewAny');
    }

    public function view(UserContract $user): bool
    {
        return $user->hasPermissionTo('register.view');
    }

    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('register.create');
    }

    public function update(UserContract $user): bool
    {
        return $user->hasPermissionTo('register.update');
    }

    public function delete(UserContract $user): bool
    {
        return $user->hasPermissionTo('register.delete');
    }
}
