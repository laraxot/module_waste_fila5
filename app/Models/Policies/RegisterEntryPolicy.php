<?php

declare(strict_types=1);

namespace Modules\Waste\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Waste\Models\RegisterEntry;
use Modules\Xot\Contracts\UserContract;

/**
 * Il registro è un log cronologico: le scritture si creano solo con le Actions di dominio,
 * non si modificano né si cancellano dall'interfaccia. Le correzioni passano dalla rettifica.
 */
class RegisterEntryPolicy extends UserBasePolicy
{
    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('register_entry.viewAny');
    }

    public function view(UserContract $user): bool
    {
        return $user->hasPermissionTo('register_entry.view');
    }

    public function create(UserContract $user): bool
    {
        return false;
    }

    public function update(UserContract $user): bool
    {
        return false;
    }

    public function delete(UserContract $user): bool
    {
        return false;
    }

    public function rectify(UserContract $user, RegisterEntry $entry): bool
    {
        return ! $entry->isRectified() && $user->hasPermissionTo('register_entry.rectify');
    }
}
