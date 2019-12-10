<?php

namespace App\Http\Requests\AccountActivity;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $account_activity = $this->route('account_activity');

        return $account_activity && $this->user()->can('update', $account_activity);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'user_id' =>  '', 
			'account_id' =>  '', 
			'trade_date' =>  '', 
			'transaction_date' =>  '', 
			'settlement_date' =>  '', 
			'action' =>  '', 
			'symbol' =>  '', 
			'symbol_id' =>  '', 
			'description' =>  '', 
			'currency' =>  '', 
			'quantity' =>  '', 
			'price' =>  '', 
			'gross_amount' =>  '', 
			'commission' =>  '', 
			'net_amount' =>  '', 
			'type' =>  '', 
		];
    }
}
