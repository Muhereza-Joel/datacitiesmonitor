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
        return in_array($user->role, ['admin', 'user', 'viewer']);
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
        return in_array($user->role, ['admin', 'user', 'viewer']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'user']);
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
        return in_array($user->role, ['admin', 'user']);
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
        return in_array($user->role, ['admin']);
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
        return in_array($user->role, ['admin']);
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
        //
    }
}
