<?php

declare(strict_types=1);

namespace Modules\Waste\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;

class OperatorSitePolicy extends UserBasePolicy
{
    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('operator_site.viewAny');
    }

    public function view(UserContract $user): bool
    {
        return $user->hasPermissionTo('operator_site.view');
    }

    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('operator_site.create');
    }

    public function update(UserContract $user): bool
    {
        return $user->hasPermissionTo('operator_site.update');
    }

    public function delete(UserContract $user): bool
    {
        return $user->hasPermissionTo('operator_site.delete');
    }
}
