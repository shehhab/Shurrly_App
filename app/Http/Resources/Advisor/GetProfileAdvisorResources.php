<?php

namespace App\Http\Resources\Advisor;

use App\Models\Advisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class GetProfileAdvisorResources extends JsonResource
{
    public function toArray($request): array
    {
        $dateOfBirth = $this->date_birth ? \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_birth)->format('d-m-Y') : null;

        $advisor =  Auth::id();
        $advisor = Advisor::find($advisor);
        $skills = null; // Add this line to store skills data
        $skills = $advisor->skills->pluck('name')->toArray(); // Get skills names and convert to array

       $media = $advisor->getFirstMediaUrl('advisor_profile_image');
       $certificates = $advisor->getFirstMediaUrl('advisor_Certificates_PDF');


        return [
            "id" => $this->whenHas('id'),
            "uuid" => $this->whenHas('uuid'),
            'email_verfied'=>(bool) $this->email_verified_at ? true:false,
            "name" =>(string) $this->whenHas('name'),
            "email" => (string) $this->whenHas('email'),
            "date_birth" => $dateOfBirth,
            'image'=>$media,
            'certificates' => $certificates,
            'Offere' => $advisor->Offere,
            'expertise' => $advisor->expertise ,
            'bio' => $advisor->bio,

            "role" => $this->hasRole('advisor') ? 'advisor' : ($this->hasRole('advisor') ? 'advisor' : 'advisor'),
            "skills" => $skills, // Add skills to the returned data

        ];


    }
}
