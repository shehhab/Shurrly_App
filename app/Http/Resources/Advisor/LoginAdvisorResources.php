<?php

namespace App\Http\Resources\Advisor;

use App\Models\Advisor;
use App\Models\Seeker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;


class LoginAdvisorResources extends JsonResource
{

        public function toArray($request)
        {
            $advisor = Auth::user();
            $dateOfBirth = $this->date_birth ? \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_birth)->format('d-m-Y') : null;
            $userId =  Auth::id();
            $user = Advisor::find($userId);

            if (!$user) {
                $user = Advisor::where('seeker_id', $advisor->id)->first();
            }

            $media = null;
            $skills = null;
            $certificates = null;
            $certificatesPages = null;

            if ($user) {
                $media = $user->getFirstMediaUrl('advisor_profile_image');
                $certificatesPath = $user->getFirstMediaPath('advisor_Certificates_PDF');
                $certificatesPages = $user->getFirstMediaPath('advisor_Certificates_PDF');

                $skills = $user->skills->pluck('name')->toArray();
            }

            return [
                "id" => $this->whenHas('id'),
                "uuid" => $this->whenHas('uuid'),
                'email_verified' => (bool) $this->email_verified_at ? true : false,
                'token' => $this->createToken('auth_token')->plainTextToken,
                "name" => (string) $this->whenHas('name'),
                "email" => (string) $this->whenHas('email'),
                "date_birth" => $dateOfBirth,
                'image' => $media,
                'certificates' => $certificates,
                "certificates_pages" => $certificatesPages,
                "role" => $this->when($this->hasRole('advisor'), 'advisor', 'advisor'),
                "skills" => $skills,
            ];
        }


    }
