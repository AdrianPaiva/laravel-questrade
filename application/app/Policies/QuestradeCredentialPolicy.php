<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuestradeCredential;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestradeCredentialPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the questrade_credential.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuestradeCredential  $questrade_credential
     * @return mixed
     */
    public function view(User $user, QuestradeCredential $questrade_credential)
    {
        return $user->domain_id == $questrade_credential->domain_id && $user->hasPermission('questrade_credential.view');
    }

    /**
     * Determine whether the user can create questrade_credentials.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('questrade_credential.create');
    }

    /**
     * Determine whether the user can update the questrade_credential.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuestradeCredential  $questrade_credential
     * @return mixed
     */
    public function update(User $user, QuestradeCredential $questrade_credential)
    {
        return $user->domain_id == $questrade_credential->domain_id && $user->hasPermission('questrade_credential.update');
    }

    /**
     * Determine whether the user can delete the questrade_credential.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuestradeCredential  $questrade_credential
     * @return mixed
     */
    public function delete(User $user, QuestradeCredential $questrade_credential)
    {
        return $user->domain_id == $questrade_credential->domain_id && $user->hasPermission('questrade_credential.delete');
    }
}
