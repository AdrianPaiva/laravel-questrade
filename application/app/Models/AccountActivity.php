<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $account_id
 * @property string $trade_date
 * @property string $transaction_date
 * @property string $settlement_date
 * @property string $action
 * @property string $symbol
 * @property string $symbol_id
 * @property string $description
 * @property string $currency
 * @property float $quantity
 * @property float $price
 * @property float $gross_amount
 * @property float $commission
 * @property float $net_amount
 * @property string $type
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 * @property User $user
 */
class AccountActivity extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'account_id', 'trade_date', 'transaction_date', 'settlement_date', 'action', 'symbol', 'symbol_id', 'description', 'currency', 'quantity', 'price', 'gross_amount', 'commission', 'net_amount', 'type', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
