<?php

namespace App\Policies\Questrade;

use App\Models\Questrade\QuestradeCredential;
use App\Models\User;
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
        return $user->id == $questrade_credential->user_id;
    }

    /**
     * Determine whether the user can create questrade_credentials.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
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
        return $user->id == $questrade_credential->user_id;
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
        return $user->id == $questrade_credential->user_id;
    }
}
