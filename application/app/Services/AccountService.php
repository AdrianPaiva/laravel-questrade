<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountService extends BaseService
{
    public function all(int $user_id, $with = [])
    {
        return Account::with($with)->where('user_id', $user_id)->get();
    }

    /**
     * Create a Account record
     * 
     * @param  array  $data
     * @return Account
     */
    public function create(array $data): Account
    {
        DB::beginTransaction();

        try {

            $account = Account::create($data);

            DB::commit();

            return $account;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update a Account
     * 
     * @param  Account $account 
     * @param  array       $data       
     * @return Account
     */
    public function update(Account $account, array $data): Account
    {
        DB::beginTransaction();

        try {

            $account->fill($data);
            $account->save();

            DB::commit();
            
            return $account;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Delete a Account
     * 
     * @param  Account $account 
     * @return bool|null whether deletion was successful
     */
    public function delete(Account $account)
    {
        DB::beginTransaction();

        try {

            $deleted = $account->delete();

            DB::commit();

            return $deleted;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
