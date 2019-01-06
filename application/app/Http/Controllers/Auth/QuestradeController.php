<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestradeController extends Controller
{
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
    public function handleProviderCallback(Request $request)
    {
        dd($request->input('code'));
        
        $user = Socialite::driver('questrade')->stateless()->user();

        // $user->token;
        dd($user);
    }
}
