<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $account = $this->route('account');

        return $account && $this->user()->can('update', $account);
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
			'questrade_user_id' =>  '', 
			'number' =>  '', 
			'type' =>  '', 
			'status' =>  '', 
			'is_primary' =>  '', 
			'is_billing' =>  '', 
			'client_account_type' =>  '', 
		];
    }
}
