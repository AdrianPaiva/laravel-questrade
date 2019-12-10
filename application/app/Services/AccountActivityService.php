<?php

namespace App\Services;

use App\Models\AccountActivity;
use Illuminate\Support\Facades\DB;

class AccountActivityService extends BaseService
{
    public function all(int $user_id, $with = [])
    {
        return AccountActivity::with($with)->where('user_id', $user_id)->get();
    }

    /**
     * Create a AccountActivity record
     * 
     * @param  array  $data
     * @return AccountActivity
     */
    public function create(array $data): AccountActivity
    {
        DB::beginTransaction();

        try {

            $account_activity = AccountActivity::create($data);

            DB::commit();

            return $account_activity;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update a AccountActivity
     * 
     * @param  AccountActivity $account_activity 
     * @param  array       $data       
     * @return AccountActivity
     */
    public function update(AccountActivity $account_activity, array $data): AccountActivity
    {
        DB::beginTransaction();

        try {

            $account_activity->fill($data);
            $account_activity->save();

            DB::commit();
            
            return $account_activity;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Delete a AccountActivity
     * 
     * @param  AccountActivity $account_activity 
     * @return bool|null whether deletion was successful
     */
    public function delete(AccountActivity $account_activity)
    {
        DB::beginTransaction();

        try {

            $deleted = $account_activity->delete();

            DB::commit();

            return $deleted;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
