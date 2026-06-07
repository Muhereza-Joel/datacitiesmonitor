<?php

namespace App\Policies;

use App\Models\TheoryOfChange;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TheoriesOfChangePolicy
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
        return $user->can('view_any_theory_of_change');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TheoryOfChange  $theoryOfChange
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TheoryOfChange $theoryOfChange)
    {
        return $user->can('view_theory_of_change');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_theory_of_change');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TheoryOfChange  $theoryOfChange
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TheoryOfChange $theoryOfChange)
    {
        return $user->can('update_theory_of_change');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TheoryOfChange  $theoryOfChange
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TheoryOfChange $theoryOfChange)
    {
        return $user->can('delete_theory_of_change');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TheoryOfChange  $theoryOfChange
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TheoryOfChange $theoryOfChange)
    {
        return $user->can('restore_theory_of_change');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TheoryOfChange  $theoryOfChange
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TheoryOfChange $theoryOfChange)
    {
        return $user->can('force_delete_theory_of_change');
    }
}
