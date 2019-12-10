<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Account;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the account.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Account  $account
     * @return mixed
     */
    public function view(User $user, Account $account)
    {
        return $user->domain_id == $account->domain_id && $user->hasPermission('account.view');
    }

    /**
     * Determine whether the user can create accounts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('account.create');
    }

    /**
     * Determine whether the user can update the account.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Account  $account
     * @return mixed
     */
    public function update(User $user, Account $account)
    {
        return $user->domain_id == $account->domain_id && $user->hasPermission('account.update');
    }

    /**
     * Determine whether the user can delete the account.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Account  $account
     * @return mixed
     */
    public function delete(User $user, Account $account)
    {
        return $user->domain_id == $account->domain_id && $user->hasPermission('account.delete');
    }
}
