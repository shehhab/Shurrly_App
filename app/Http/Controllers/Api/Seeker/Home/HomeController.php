<?php

namespace App\Http\Controllers\Api\Seeker\Home;

use App\Models\Skill;
use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __invoke()
    {

        $advisors = Advisor::with('seeker')
        ->where('approved', 1)
        ->get();

        $formattedAdvisors = $advisors->map(function ($advisor) {
            return [
                'id' => $advisor->id,
                'name' => $advisor->seeker->name, // Accessing the seeker's name through the relationship
                'photo_url' => $advisor->getFirstMediaUrl('advisor_profile_image'), // Change 'profile_photos' to your media collection name
                'skills' => $advisor->skills->pluck('name')->toArray(),

            ];
        });



        $skills = Skill::pluck('name', 'id');


        return $this->handleResponse(true, [
            'Skills' => $skills,
            'advisor' => $formattedAdvisors,
        ]);


    }
}
