<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\AccountActivity\CreateAccountActivityRequest;
use App\Http\Requests\AccountActivity\UpdateAccountActivityRequest;
use App\Http\Resources\AccountActivity as AccountActivityResource;
use App\Http\Resources\AccountActivityCollection;
use App\Services\AccountActivityService;
use App\Models\AccountActivity;

/**
 * @resource AccountActivity
 */
class AccountActivityController extends BaseApiController
{
    private $account_activity_service;

    public function __construct(AccountActivityService $account_activity_service)
    {
        $this->account_activity_service = $account_activity_service;
    }

    /**
     * Get all AccountActivitys for the current Domain
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account_activities = $this->account_activity_service->all(Auth::user()->id, $this->getEagerLoads());

        return $this->sendResponse(new AccountActivityCollection($account_activities));    
    }

    /**
     * Create a new AccountActivity 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAccountActivityRequest $request)
    {
        $data = $request->all();

        $account_activity = $this->account_activity_service->create($data);

        return $this->sendResponse(new AccountActivityResource($account_activity), 'AccountActivity created!', 201);    
    }

    /**
     * Display the specified AccountActivity
     *
     * @param  AccountActivity
     * @return \Illuminate\Http\Response
     */
    public function show(AccountActivity $account_activity)
    {
        $this->authorize('view', $account_activity);

        $account_activity->load($this->getEagerLoads());

        return $this->sendResponse(new AccountActivityResource($account_activity));    
    }

    /**
     * Update the specified AccountActivity
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  AccountActivity $account_activity
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountActivityRequest $request, AccountActivity $account_activity)
    {
        $data = $request->all();
        $account_activity = $this->account_activity_service->update($account_activity, $data);

        return $this->sendResponse(new AccountActivityResource($account_activity), 'AccountActivity updated!');    
    }

    /**
     * Remove the specified AccountActivity from storage.
     *
     * @param  AccountActivity $account_activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountActivity $account_activity)
    {
        $this->authorize('delete', $account_activity);

        if ($this->account_activity_service->delete($account_activity)) {
            return $this->sendResponse(null, "AccountActivity deleted successfully", 204);
        }
        
        return $this->sendError('Unable to delete this AccountActivity', [], 500);
    }
}
