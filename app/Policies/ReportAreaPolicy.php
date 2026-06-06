<?php

namespace App\Policies;

use App\Models\ReportArea;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportAreaPolicy
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
        return $user->can('view_any_report_area');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReportArea  $reportArea
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ReportArea $reportArea)
    {
        return $user->can('view_report_area');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_report_area');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReportArea  $reportArea
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ReportArea $reportArea)
    {
        return $user->can('update_report_area');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReportArea  $reportArea
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ReportArea $reportArea)
    {
        return $user->can('delete_report_area');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReportArea  $reportArea
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ReportArea $reportArea)
    {
        return $user->can('restore_report_area');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReportArea  $reportArea
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ReportArea $reportArea)
    {
        return $user->can('force_delete_report_area');
    }
}
