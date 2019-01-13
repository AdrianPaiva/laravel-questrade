<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $access_token
 * @property string $refresh_token
 * @property string $api_server
 * @property string $token_type
 * @property int $expires_in
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 */
class QuestradeCredential extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'access_token', 'refresh_token', 'api_server', 'token_type', 'expires_in', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function isExpired()
    {
        return true;
        if ((strtotime(now()) - strtotime($this->update_date)) >= $this->expires_in) {
            return true;
        }

        return false;
    }
}
