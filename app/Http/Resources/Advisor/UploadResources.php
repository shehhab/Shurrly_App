<?php

namespace App\Http\Resources\Advisor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'bio' => $this->bio,
            'expertise' => $this->expertise,
            'Offere' => $this->Offere,
            'seeker_id' => $this->seeker_id,
            'image' => $this->getFirstMediaUrl('advisor_profile_image'),
            'video' => $this->getFirstMediaUrl('advisor_Intro_video'),
            'certificates' => $this->getFirstMediaUrl('advisor_Certificates_PDF'),
            'skills' => $this->skills->pluck('name'), // Assuming skills is a relationship in Advisor model



        ];
    }
}
