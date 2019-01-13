<?php

namespace App\Http\Requests\QuestradeCredential;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestradeCredentialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $questrade_credential = $this->route('questrade_credential');

        return $questrade_credential && $this->user()->can('update', $questrade_credential);
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
			'access_token' =>  '', 
			'refresh_token' =>  '', 
			'api_server' =>  '', 
			'token_type' =>  '', 
			'expires_in' =>  '', 
			'deleted_at' =>  '', 
		];
    }
}
