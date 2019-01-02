<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'first_name'       => $this->first_name,
            'last_name'        => $this->last_name,
            'email'            => $this->email,
            'email_verified_at'=> (string) $this->email_verified_at,
            'created_at'       => (string) $this->created_at,
            'updated_at'       => (string) $this->updated_at,
            'deleted_at'       => (string) $this->deleted_at,

            // 'profile' => new ProfileResource($this->whenLoaded('profile')),
        ];
    }
}
