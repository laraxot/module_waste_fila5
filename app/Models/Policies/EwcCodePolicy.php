<?php

declare(strict_types=1);

namespace Modules\Waste\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Models\Policies\Concerns\DeniesUndeclaredFilamentAbilities;
use Modules\Xot\Models\Policies\Concerns\RequiresOperationalPermissions;

class EwcCodePolicy extends UserBasePolicy
{
    use DeniesUndeclaredFilamentAbilities;
    use RequiresOperationalPermissions;

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

    /** Import del catalogo EER da CSV (crea e aggiorna codici, compreso `is_hazardous`). */
    public function import(UserContract $user): bool
    {
        return $user->hasPermissionTo('ewc_code.import');
    }

    public function delete(UserContract $user): bool
    {
        return $user->hasPermissionTo('ewc_code.delete');
    }
}
