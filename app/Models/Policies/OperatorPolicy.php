<?php

declare(strict_types=1);

namespace Modules\Waste\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;

class OperatorPolicy extends UserBasePolicy
{
    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('operator.viewAny');
    }

    public function view(UserContract $user): bool
    {
        return $user->hasPermissionTo('operator.view');
    }

    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('operator.create');
    }

    public function update(UserContract $user): bool
    {
        return $user->hasPermissionTo('operator.update');
    }

    public function delete(UserContract $user): bool
    {
        return $user->hasPermissionTo('operator.delete');
    }
}
