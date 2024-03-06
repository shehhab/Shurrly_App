<?php

namespace App\Http\Resources\Advisor;

use App\Models\Advisor;
use App\Models\Seeker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;


class LoginAdvisorResources extends JsonResource
{
    public function toArray($request): array
    {
        $this->tokens()->delete();
        $advisor = Auth::user();

        $dateOfBirth = $this->date_birth ? \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_birth)->format('d-m-Y') : null;

        $user =  Auth::id();
        $user = Advisor::find($user);
        $existingAdvisor = Advisor::where('seeker_id', $advisor->id)->first();

        $media = null;
         if ($existingAdvisor) {
            $media = $user->getFirstMediaUrl('advisor_profile_image');
            $Certificates = $user->getFirstMediaUrl('advisor_Certificates_PDF');
        }

        return [
            "id" => $this->whenHas('id'),
            "uuid" => $this->whenHas('uuid'),
            'email_verfied'=>(bool) $this->email_verified_at ? true:false,
            'token' => $this->createToken('auth_token')->plainTextToken,
            "name" =>(string) $this->whenHas('name'),
            "email" => (string) $this->whenHas('email'),
            "date_birth" => $dateOfBirth,
            'image'=> $media,
            'Certificates' => $Certificates ,
            "role" => $this->when($this->hasRole('advisor'), 'advisor', 'advisor'),


        ];
    }
}
