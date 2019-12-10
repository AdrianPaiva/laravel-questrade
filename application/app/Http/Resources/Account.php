<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Account extends JsonResource
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
			'questrade_user_id' => $this->questrade_user_id, 
			'number' => $this->number, 
			'type' => $this->type, 
			'status' => $this->status, 
			'is_primary' => $this->is_primary, 
			'is_billing' => $this->is_billing, 
			'client_account_type' => $this->client_account_type, 
			'created_at' => $this->created_at, 
			'updated_at' => $this->updated_at, 
		];
    }
}
