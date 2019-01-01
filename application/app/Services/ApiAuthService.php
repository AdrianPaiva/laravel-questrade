<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
* Login proxy for our Oauth API
*/
class ApiAuthService extends BaseService
{
    /**
     * Attempt to create an access token using user credentials
     *
     * @param string $email
     * @param string $password
     * @return array 
     */
    public function attemptLogin(string $email, string $password)
    {
        // $user = User::where('email', $email)->firstOrFail();

        return $this->proxy('password', [
            'username' => $email,
            'password' => $password,
            'scope'    => '*',
        ]);
    }

    /**
     * Attempt to refresh the access token using a refresh token
     * @param string $refresh_token
     * @return array 
     */
    public function attemptRefresh(string $refresh_token): array
    {
        return $this->proxy('refresh_token', [
            'refresh_token' => $refresh_token
        ]);
    }

    /**
     * Proxy a request to the OAuth server.
     * 
     * @param string $grant_type what type of grant type should be proxied
     * @param array $data the data to send to the server
     * @return  array 
     */ 
    public function proxy(string $grant_type, array $data = [])
    {
        $data = array_merge($data, [
            'client_id'     => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
            'grant_type'    => $grant_type,
        ]);

        $request = Request::create('/oauth/token', 'POST', $data);
        $response = app()->handle($request);

        return json_decode((string) $response->getContent(), true);
    }

    /**
     * Logs out the user. We revoke access token and refresh token. 
     */
    public function logout()
    {
        $access_token = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $access_token->id)
            ->update(['revoked' => true]);

        $access_token->revoke();
    }
}
