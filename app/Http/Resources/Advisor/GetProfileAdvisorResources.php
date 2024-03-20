<?php

namespace App\Http\Resources\Advisor;

use Carbon\Carbon;
use App\Models\Seeker;

use App\Models\Advisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class GetProfileAdvisorResources extends JsonResource
{
    public function toArray($request)
    {
        $advisorId = Auth::id();
        $advisor = Advisor::with('skills')->find($this->id);

        if ($advisor) {
            $skills = $advisor->skills ? $advisor->skills->pluck('name')->toArray() : [];

            $media = $advisor->getFirstMediaUrl('advisor_profile_image');
            $certificates = $advisor->getFirstMediaUrl('advisor_Certificates_PDF');
            $seeker =$advisor->seeker ;
            $days = $advisor->seeker->days;



            $dateOfBirth = $seeker->date_birth ? Carbon::createFromFormat('Y-m-d', $seeker->date_birth)->format('d-m-Y') : null;


            return [
                "id" => $advisor->id,
                "uuid" => $seeker->uuid,
                'email_verfied' => (bool) $this->email_verified_at,
                "name" => $seeker->name,
                "email" => $seeker->email,
                'bio' => $advisor->bio,
                "seeker_id" => $advisor->seeker_id,
                "date_birth" => $dateOfBirth,
                'image' => $media,
                'certificates' => $certificates,
                'offere' => $advisor->offere,
                "role" => $this->hasRole('advisor') ? 'advisor' : ($this->hasRole('advisor') ? 'advisor' : 'advisor'),
                "skills" => $skills,
                "days" => $days
            ];
        } else {
            return [];
        }
    }
}
