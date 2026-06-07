<?php

namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationPolicy
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
        return $user->can('view_any_organisation');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Organisation $organisation)
    {
        return $user->can('view_organisation');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_organisation');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Organisation $organisation)
    {
        return $user->can('update_organisation');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Organisation $organisation)
    {
        return $user->can('delete_organisation');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Organisation $organisation)
    {
        return $user->can('restore_organisation');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Organisation $organisation)
    {
        return $user->can('force_delete_organisation');
    }
}
