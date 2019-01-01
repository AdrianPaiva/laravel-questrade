<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource as UserResource;
use App\Services\UserService;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UploadUsersRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Users
 */
class UserController extends BaseApiController
{
    private $user_service;

    public function __construct(UserService $user_service) 
    {
        $this->user_service = $user_service;
    }

    /**
     * Get all Users in the current Domain
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user_service->all($this->getEagerLoads());

        return $this->sendResponse(new UserCollection($users));
    }

    /**
     * Create a new User
     *
     * @param  \Illuminate\Http\CreateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->except(['password', 'remember_token']);

        $user = $this->user_service->create($data);

        return $this->sendResponse(new UserResource($user), 'User Created!', 201);
    }

    /**
     * Display the specified User
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load($this->getEagerLoads());

        return $this->sendResponse(new UserResource($user));
    }

    /**
     * Update the specified User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->except(['password']);

        $user = $this->user_service->update($user, $data);

        return $this->sendResponse(new UserResource($user), 'User updated!', 200);
    }

    /**
     * Remove the specified User
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($this->user_service->delete($user)) {
            return $this->sendResponse(null, "User deleted successfully", 204);
        }
        
        return $this->sendError('Unable to delete this User', [], 500);
    }

    /**
     * Retrieve the Current User
     * 
     * @param  Request $request
     */
    public function currentUser(Request $request)
    {
        $user = $request->user();
        $user->load($this->getEagerLoads());

        return $this->sendResponse(new UserResource($user), 'Current User');
    }
}
