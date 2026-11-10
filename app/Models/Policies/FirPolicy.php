<?php

declare(strict_types=1);

namespace Modules\Waste\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Waste\Enums\FirStatusEnum;
use Modules\Waste\Models\Fir;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Models\Policies\Concerns\DeniesUndeclaredFilamentAbilities;

/**
 * Un FIR si modifica a mano solo in bozza: dopo l'emissione cambiano solo stato e accettazione,
 * tramite le Actions di dominio (permesso `fir.change_status`). Un FIR non si cancella: si annulla.
 */
class FirPolicy extends UserBasePolicy
{
    use DeniesUndeclaredFilamentAbilities;

    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('fir.viewAny');
    }

    public function view(UserContract $user): bool
    {
        return $user->hasPermissionTo('fir.view');
    }

    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('fir.create');
    }

    public function update(UserContract $user, Fir $fir): bool
    {
        return $fir->status === FirStatusEnum::DRAFT && $user->hasPermissionTo('fir.update');
    }

    public function changeStatus(UserContract $user, Fir $fir): bool
    {
        return ! $fir->status->isFinal() && $user->hasPermissionTo('fir.change_status');
    }

    /** Registrazione dell'esito dell'accettazione a destino (quantità accettata, motivazione). */
    public function recordAcceptance(UserContract $user, Fir $fir): bool
    {
        return \in_array($fir->status, [FirStatusEnum::ISSUED, FirStatusEnum::IN_TRANSIT, FirStatusEnum::DELIVERED], true)
            && $user->hasPermissionTo('fir.record_acceptance');
    }

    public function delete(UserContract $user): bool
    {
        return false;
    }
}
