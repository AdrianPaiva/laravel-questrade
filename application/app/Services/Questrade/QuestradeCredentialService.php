<?php

namespace App\Services\Questrade;

use App\Models\Questrade\QuestradeCredential;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuestradeCredentialService extends BaseService
{
    /**
     * Get all QuestradeCredentials for this domain
     *
     * @param  int    $domain_id
     * @return Collection
     */
    public function all($with = [])
    {
        return QuestradeCredential::with($with)->get();
    }

    /**
     * find creds for the current user
     *
     * @return QuestradeCredential|null
     */
    public function getCurrent()
    {
        return QuestradeCredential::where('user_id', Auth::id())->latest('updated_at')->firstOrFail();
    }

    /**
     * Create a QuestradeCredential record
     *
     * @param  array  $data
     * @return QuestradeCredential
     */
    public function create(array $data): QuestradeCredential
    {
        DB::beginTransaction();

        try {
            $questrade_credential = QuestradeCredential::updateOrCreate(
                ['user_id' => $data['user_id']],
                $data
            );

            DB::commit();

            return $questrade_credential;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update a QuestradeCredential
     *
     * @param  QuestradeCredential $questrade_credential
     * @param  array       $data
     * @return QuestradeCredential
     */
    public function update(QuestradeCredential $questrade_credential, array $data): QuestradeCredential
    {
        DB::beginTransaction();

        try {
            $questrade_credential->fill($data);
            $questrade_credential->save();

            DB::commit();
            
            return $questrade_credential;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Delete a QuestradeCredential
     *
     * @param  QuestradeCredential $questrade_credential
     * @return bool|null whether deletion was successful
     */
    public function delete(QuestradeCredential $questrade_credential)
    {
        DB::beginTransaction();

        try {
            $deleted = $questrade_credential->delete();

            DB::commit();

            return $deleted;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
