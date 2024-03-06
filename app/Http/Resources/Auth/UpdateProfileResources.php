<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UpdateProfileResources extends JsonResource
{
    public function toArray($request): array
    {
        $dateOfBirth = $this->date_birth ? \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_birth)->format('d-m-Y') : null;
        $defaultImage = asset('Default/profile.jpeg');
        return [
            "name"  => $this->whenHas('name'),
            "date_birth" =>$dateOfBirth,
            'image'=>$this->getFirstMediaUrl('seeker_profile_image')?:$defaultImage,
        ];
    }
}
