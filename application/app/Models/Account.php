<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $questrade_user_id
 * @property string $number
 * @property string $type
 * @property string $status
 * @property boolean $is_primary
 * @property boolean $is_billing
 * @property string $client_account_type
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property AccountActivity[] $accountActivities
 */
class Account extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'questrade_user_id', 'number', 'type', 'status', 'is_primary', 'is_billing', 'client_account_type', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountActivities()
    {
        return $this->hasMany('App\Models\AccountActivity');
    }
}
