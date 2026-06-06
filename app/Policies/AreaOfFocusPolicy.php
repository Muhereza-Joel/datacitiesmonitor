<?php

namespace App\Policies;

use App\Models\AreaOfFocus;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AreaOfFocusPolicy
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
        return $user->can('view_any_area_of_focus');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AreaOfFocus  $areaOfFocus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AreaOfFocus $areaOfFocus)
    {
        return $user->can('view_area_of_focus');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_area_of_focus');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AreaOfFocus  $areaOfFocus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AreaOfFocus $areaOfFocus)
    {
        return $user->can('update_area_of_focus');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AreaOfFocus  $areaOfFocus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AreaOfFocus $areaOfFocus)
    {
        return $user->can('delete_area_of_focus');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AreaOfFocus  $areaOfFocus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AreaOfFocus $areaOfFocus)
    {
        return $user->can('restore_area_of_focus');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AreaOfFocus  $areaOfFocus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AreaOfFocus $areaOfFocus)
    {
        return $user->can('force_delete_area_of_focus');
    }
}
