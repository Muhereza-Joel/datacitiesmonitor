<?php

namespace App\Policies;

use App\Models\Archive;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ArchivePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_archive');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Archive  $archive
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Archive $archive)
    {
        return $user->can('view_archive');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_archive');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Archive  $archive
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Archive $archive)
    {
        return $user->can('update_archive');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Archive  $archive
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Archive $archive)
    {
        return $user->can('delete_archive');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Archive  $archive
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Archive $archive)
    {
        return $user->can('restore_archive');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Archive  $archive
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Archive $archive)
    {
        return $user->can('force_delete_archive');
    }
}
