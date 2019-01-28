<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource as UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Registration
 */
class RegisterController extends BaseApiController
{
    private $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    /**
     * Registration
     *
     * @param  RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->user_service->create($request->except(['password_confirmation']));

        if ($user) {
            Auth::login($user);

            return $this->sendResponse(new UserResource($user), 'Registration successful');
        }
        
        return $this->sendError('Registration failed', [], 422);
    }

    /**
     * Verify that a user exists for the selected domain
     *
     * if the user exists a partial user object is returned with their verified status
     * if not a blank array is returned
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function verifyUserExists(string $email)
    {
        $user = User::where(['email' => $email])->first();

        if ($user) {
            return $this->sendResponse([
                'id' => $user->id,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ], 'User Exists');
        }

        return $this->sendResponse([], 'User Not found');
    }
}
