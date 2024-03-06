<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class SeekerResource extends JsonResource
{
    public function toArray($request): array
    {
        // Format date_birth using Carbon
        $formattedDateOfBirth = optional($this->date_birth)->format('d-m-Y');
        $defaultImage = asset('Default/profile.jpeg');
        return [
            "id"    => $this->whenHas('id'),
            "uuid"  => $this->whenHas('uuid'),
            'token' => $this->createToken('auth_token')->plainTextToken,
            "name"  => $this->whenHas('name'),
            "email" => $this->whenHas('email'),
            "date_birth" =>$formattedDateOfBirth,
            'image'=>$this->getFirstMediaUrl('seeker_profile_image')?:$defaultImage,
            "role" => $this->whenHas('role'),
        ];
    }
}
