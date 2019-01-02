<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class UserService extends BaseService
{
    /**
     * Get all users
     *
     * @param  array|string $with related models to include
     *
     * @return Collection
     */
    public function all($with = []): Collection
    {
        return User::with($with)->get();
    }
    
    /**
     * Create a User record
     *
     * @param  array $data
     *
     * @return User
     * @throws \Throwable
     */
    public function create(array $data): User
    {
        DB::beginTransaction();
        
        try {
            $attributes = [
                'email' => $data['email'],
            ];

            $user = User::firstOrCreate($attributes, $data);
            
            DB::commit();
            
            return $user;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
    
    /**
     * Update  a User record
     *
     * @param  User $user
     * @param  array $data
     *
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();
        
        return $user;
    }
    
    /**
     * Delete a User
     *
     * @param  User $user
     *
     * @return bool | null whether deletion was successful
     * @throws \Throwable
     */
    public function delete(User $user)
    {
        DB::beginTransaction();
        
        try {
            $deleted = $user->delete();
            
            DB::commit();
            
            return $deleted;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
