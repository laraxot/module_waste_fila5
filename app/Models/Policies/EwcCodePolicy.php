<?php

declare(strict_types=1);

namespace Modules\Waste\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;

class EwcCodePolicy extends UserBasePolicy
{
    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('ewc_code.viewAny');
    }

    public function view(UserContract $user): bool
    {
        return $user->hasPermissionTo('ewc_code.view');
    }

    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('ewc_code.create');
    }

    public function update(UserContract $user): bool
    {
        return $user->hasPermissionTo('ewc_code.update');
    }

    public function delete(UserContract $user): bool
    {
        return $user->hasPermissionTo('ewc_code.delete');
    }
}
