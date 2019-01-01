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
            'uuid'     => $this->uuid,
            'email'    => $this->email,
            'mobile'   => $this->mobile,
            'verified' => $this->verified,
            'profile' => new ProfileResource($this->whenLoaded('profile')),
            
            // 'userSettings' => new UserSettingsResource($this->whenLoaded('userSettings')),
            // 'userPermissions' => new UserPermissionCollection($this->whenLoaded('userPermissions')),
            // 'teams' => new TeamCollection($this->whenLoaded('teams')),
            // 'mainTeams' => new TeamCollection($this->whenLoaded('mainTeams')),
            // 'actionPackUsers' => new ActionPackUserCollection($this->whenLoaded('actionPackUsers')),
            // 'lessonVisits' => new LessonVisitCollection($this->whenLoaded('lessonVisits')),
            // 'userDevices' => new UserDeviceCollection($this->whenLoaded('userDevices')),
            // 'contacts' => new ContactCollection($this->whenLoaded('contacts')),
            // 'resourceShares' => new ResourceShareCollection($this->whenLoaded('resourceShares')),
        ];
    }
}
