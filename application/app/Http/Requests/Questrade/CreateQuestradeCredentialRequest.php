<?php

namespace App\Http\Requests\Questrade;

use App\Models\Questrade\QuestradeCredential;
use Illuminate\Foundation\Http\FormRequest;

class CreateQuestradeCredentialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', QuestradeCredential::class);
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
