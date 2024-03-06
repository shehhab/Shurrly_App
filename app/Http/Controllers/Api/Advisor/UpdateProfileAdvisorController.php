<?php

namespace App\Http\Controllers\Api\Advisor;

use Carbon\Carbon;
use App\Models\Skill;
use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Advisor\UpdateProfileResources;

class UpdateProfileAdvisorController extends Controller
{
    public function __invoke(Request $request)
    {
        // Retrieve the authenticated user
        $userId = Auth::id();
        $advisor = Advisor::find($userId);
        $seeker = Seeker::find($userId);

        //$advisor = Seeker::find($userId);


        // Validate the request data directly within the update call
        $validatedData = $request->validate([
            'image'=>['sometimes','image','mimes:jpg,jpeg,png,webp,gif','max:8000'],
            'expertise'=>['sometimes','max:255'],
            'date_birth' => ['sometimes', 'date_format:d/m/Y', 'before_or_equal:' . now()->subYears(16)->format('d/m/Y'), 'after_or_equal:' . now()->subYears(70)->format('d/m/Y')],
            'certificates'=>['sometimes', 'file', 'mimes:pdf', 'max:50480'],
            'Offere'=>['sometimes'],
            'bio' =>'sometimes|max:255|string',
            'video' => ['sometimes', 'file', 'mimes:mov,pdf,mp4,mp3', 'max:50480'],
        ]);


    // Convert date_birth format to 'Y-m-d'
    if (isset($validatedData['date_birth'])) {
        $validatedData['date_birth'] = Carbon::createFromFormat('d/m/Y', $validatedData['date_birth'])->format('Y-m-d');
    }

        $generatedSkills = [];
        $advisorId = $advisor->id;

        // Check for skills in the request
        if ($request->has('skills')) {
            foreach ($request->skills as $skill) {
                array_push($generatedSkills, Skill::firstOrCreate(['name' => $skill])->id);
            }

            // If there are skills, update them
            if (!empty($generatedSkills)) {
                //Completely replace skills with new skills
                $advisor = Advisor::where('id', $advisorId)->first();
                $advisor->skills()->sync($generatedSkills);
            }
        } else {
             //response is empty and nothing in skill list
            return $this->handleResponse(message: 'Skills required' ,status:false ,code : 406 );

        }

            // ! If you want to add only new skills without removing old ones
        // $advisor->skills()->syncWithoutDetaching($generatedSkills);

        $imageUrl = null;
        $certificatesUrl = null;

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            $advisor->clearMediaCollection('advisor_profile_image');

            // Store the new image
            $image = $advisor->addMediaFromRequest('image')->toMediaCollection('advisor_profile_image');
            $imageUrl = $image->getUrl();
        }

        if ($request->hasFile('certificates')) {
            // Delete the old certificates if they exist
            $advisor->clearMediaCollection('advisor_Certificates_PDF');

            // Store the new certificates
            $certificates = $advisor->addMediaFromRequest('certificates')->toMediaCollection('advisor_Certificates_PDF');
            $certificatesUrl = $certificates->getUrl();
        }

        // Update the seeker with validated data
        $advisor->update($validatedData);

        return $this->handleResponse(status:true, message:'Successfully updated profile for '. $seeker->email , data: new UpdateProfileResources($advisor) );

    }}
