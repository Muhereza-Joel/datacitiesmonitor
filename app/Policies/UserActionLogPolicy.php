<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserActionLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserActionLogPolicy
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
        return $user->can('view_any_user_action_log');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserActionLog  $userActionLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, UserActionLog $userActionLog)
    {
        return $user->can('view_user_action_log');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_user_action_log');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserActionLog  $userActionLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserActionLog $userActionLog)
    {
        return $user->can('update_user_action_log');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserActionLog  $userActionLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserActionLog $userActionLog)
    {
        return $user->can('delete_user_action_log');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserActionLog  $userActionLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, UserActionLog $userActionLog)
    {
        return $user->can('restore_user_action_log');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserActionLog  $userActionLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, UserActionLog $userActionLog)
    {
        return $user->can('force_delete_user_action_log');
    }
}
