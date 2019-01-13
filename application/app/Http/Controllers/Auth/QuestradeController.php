<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Http\Controllers\Controller;
use App\Services\QuestradeCredentialService;
use Illuminate\Support\Facades\Auth;

class QuestradeController extends Controller
{
    public $questrade_credential_service;

    public function __construct(QuestradeCredentialService $questrade_credential_service)
    {
        $this->questrade_credential_service = $questrade_credential_service;
    }

    /**
     * Redirect the user to the Questrade authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('questrade')->stateless()->redirect();
    }

    /**
     * Obtain the user information from Questrade.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {   
        $user = Socialite::driver('questrade')->stateless()->user();

        $data = array_only($user->accessTokenResponseBody, [
            'access_token',
            'api_server',
            'refresh_token',
            'token_type',
            'expires_in',
        ]);

        $data['user_id'] = Auth::user()->id;

        $questrade_credential = $this->questrade_credential_service->create($data);

        if ($questrade_credential) {
            return redirect('/home')->with('status', 'Questrade Connected!');
        }

        // error handling here
        dd($questrade_credential);

    }
}
