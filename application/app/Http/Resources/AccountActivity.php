<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountActivity extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
			'id' => $this->id, 
			'user_id' => $this->user_id, 
			'account_id' => $this->account_id, 
			'trade_date' => $this->trade_date, 
			'transaction_date' => $this->transaction_date, 
			'settlement_date' => $this->settlement_date, 
			'action' => $this->action, 
			'symbol' => $this->symbol, 
			'symbol_id' => $this->symbol_id, 
			'description' => $this->description, 
			'currency' => $this->currency, 
			'quantity' => $this->quantity, 
			'price' => $this->price, 
			'gross_amount' => $this->gross_amount, 
			'commission' => $this->commission, 
			'net_amount' => $this->net_amount, 
			'type' => $this->type, 
			'created_at' => $this->created_at, 
			'updated_at' => $this->updated_at, 
		];
    }
}
