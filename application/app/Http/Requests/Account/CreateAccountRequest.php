<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Account;

class CreateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Account::class);
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
