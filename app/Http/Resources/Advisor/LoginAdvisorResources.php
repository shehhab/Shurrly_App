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
        // Get the currently authenticated user
        $advisor = Auth::user();

        // Parse date of birth if available
        $dateOfBirth = $this->date_birth ? \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_birth)->format('d-m-Y') : null;

        // Get the ID of the authenticated user
        $userId =  Auth::id();

        // Find the user in the Advisor table by their ID
        $user = Advisor::find($userId);

        // If the user is not found in the Advisor table, try finding by seeker_id
        if (!$user) {
            $user = Advisor::where('seeker_id', $advisor->id)->first();
        }

        // Initialize variables for media, skills, and certificates
        $media = null;
        $skills = null;
        $certificates = null;

        // If an Advisor record is found
        if ($user) {
            // Retrieve the URL for the advisor's profile image
            $media = $user->getFirstMediaUrl('advisor_profile_image');

            // Retrieve the URL for the advisor's certificates PDF
            $certificates = $user->getFirstMediaUrl('advisor_Certificates_PDF');

            // Retrieve the advisor's skills and convert them to an array
            $skills = $user->skills->pluck('name')->toArray();
        }

        // Return the advisor data with appropriate formatting
        return [
            "id" => $this->whenHas('id'), // Return ID if exists
            "uuid" => $this->whenHas('uuid'), // Return UUID if exists
            'email_verified' => (bool) $this->email_verified_at ? true : false, // Check if email is verified
            'token' => $this->createToken('auth_token')->plainTextToken, // Generate and return authentication token
            "name" => (string) $this->whenHas('name'), // Return name if exists
            "email" => (string) $this->whenHas('email'), // Return email if exists
            "date_birth" => $dateOfBirth, // Return date of birth
            'image' => $media, // Return advisor profile image URL
            'certificates' => $certificates, // Return advisor certificates URL
            "role" => $this->when($this->hasRole('advisor'), 'advisor', 'advisor'), // Return role as 'advisor' if user has advisor role
            "skills" => $skills, // Return advisor skills
        ];
    }

}
