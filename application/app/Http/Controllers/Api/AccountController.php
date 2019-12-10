<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Account\CreateAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Resources\Account as AccountResource;
use App\Http\Resources\AccountCollection;
use App\Services\AccountService;
use App\Models\Account;

/**
 * @resource Account
 */
class AccountController extends BaseApiController
{
    private $account_service;

    public function __construct(AccountService $account_service)
    {
        $this->account_service = $account_service;
    }

    /**
     * Get all Accounts for the current Domain
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domain_id = Auth::user()->domain_id;

        $accounts = $this->account_service->all($domain_id, $this->getEagerLoads());

        return $this->sendResponse(new AccountCollection($accounts));    
    }

    /**
     * Create a new Account 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAccountRequest $request)
    {
        $data = $request->all();

        $account = $this->account_service->create($data);

        return $this->sendResponse(new AccountResource($account), 'Account created!', 201);    
    }

    /**
     * Display the specified Account
     *
     * @param  Account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        $this->authorize('view', $account);

        $account->load($this->getEagerLoads());

        return $this->sendResponse(new AccountResource($account));    
    }

    /**
     * Update the specified Account
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Account $account
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $data = $request->all();
        $account = $this->account_service->update($account, $data);

        return $this->sendResponse(new AccountResource($account), 'Account updated!');    
    }

    /**
     * Remove the specified Account from storage.
     *
     * @param  Account $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        if ($this->account_service->delete($account)) {
            return $this->sendResponse(null, "Account deleted successfully", 204);
        }
        
        return $this->sendError('Unable to delete this Account', [], 500);
    }
}
