<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AccountActivity;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the account_activity.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountActivity  $account_activity
     * @return mixed
     */
    public function view(User $user, AccountActivity $account_activity)
    {
        return $user->domain_id == $account_activity->domain_id && $user->hasPermission('account_activity.view');
    }

    /**
     * Determine whether the user can create account_activitys.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('account_activity.create');
    }

    /**
     * Determine whether the user can update the account_activity.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountActivity  $account_activity
     * @return mixed
     */
    public function update(User $user, AccountActivity $account_activity)
    {
        return $user->domain_id == $account_activity->domain_id && $user->hasPermission('account_activity.update');
    }

    /**
     * Determine whether the user can delete the account_activity.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountActivity  $account_activity
     * @return mixed
     */
    public function delete(User $user, AccountActivity $account_activity)
    {
        return $user->domain_id == $account_activity->domain_id && $user->hasPermission('account_activity.delete');
    }
}
