<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Alimento;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlimentoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_alimento');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Alimento $alimento): bool
    {
        return $user->can('view_alimento');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_alimento');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Alimento $alimento): bool
    {
        return $user->can('update_alimento');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Alimento $alimento): bool
    {
        return $user->can('delete_alimento');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_alimento');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Alimento $alimento): bool
    {
        return $user->can('force_delete_alimento');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_alimento');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Alimento $alimento): bool
    {
        return $user->can('restore_alimento');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_alimento');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Alimento $alimento): bool
    {
        return $user->can('replicate_alimento');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_alimento');
    }
}
