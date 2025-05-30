<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Costo;
use Illuminate\Auth\Access\HandlesAuthorization;

class CostoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_costo');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Costo $costo): bool
    {
        return $user->can('view_costo');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_costo');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Costo $costo): bool
    {
        return $user->can('update_costo');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Costo $costo): bool
    {
        return $user->can('delete_costo');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_costo');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Costo $costo): bool
    {
        return $user->can('force_delete_costo');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_costo');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Costo $costo): bool
    {
        return $user->can('restore_costo');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_costo');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Costo $costo): bool
    {
        return $user->can('replicate_costo');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_costo');
    }
}
