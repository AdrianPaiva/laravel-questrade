<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ApiAuthService;

/**
 * @resource Authentication
 */
class LoginController extends BaseApiController
{
    private $auth_service;

    public function __construct(ApiAuthService $auth_service)
    {
        $this->auth_service = $auth_service;
    }

    /**
     * Api Login
     * 
     * @param  LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $login_response = $this->auth_service->attemptLogin($email, $password);

        if (!empty($login_response['error'])) {
            return $this->sendError($login_response['message'], $login_response['error'], 422);
        }

        return $this->sendResponse($login_response, 'Login successful');
    }

    /**
     * Refresh the access token using the refresh token
     * 
     * @param  Request $request
     * @param  refresh_token
     */
    public function refresh(Request $request)
    {
        $refresh_token = $request->get('refresh_token');

        $refresh_response = $this->auth_service->attemptRefresh($refresh_token);

        if (!empty($refresh_response['error'])) {
            return $this->sendError($refresh_response['message'], $refresh_response['error'], 422);
        }

        return $this->sendResponse($refresh_response, 'token refreshed');
    }

    /**
     * Api Logout - revokes tokens
     */
    public function logout()
    {
        $this->auth_service->logout();

        return $this->sendResponse(null, 'Logout Successful', 204);
    }
}
