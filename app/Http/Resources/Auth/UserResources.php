<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $formattedDateOfBirth = optional($this->date_birth)->format('d-m-Y');
        $defaultImage = asset('Default/profile.jpeg');
        return [
            "id" => $this->whenHas('id'),
            "uuid" => $this->whenHas('uuid'),
            'token' => $this->whenHas('token'),
            "name" => $this->whenHas('name'),
            "email" => $this->whenHas('email'),
            "date_birth" =>$formattedDateOfBirth,
            'image'=>$this->getFirstMediaUrl('user_profile_image')?:$defaultImage,
            "role" => $this->whenHas('role'),

        ];
    }
}
