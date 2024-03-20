<?php

namespace App\Http\Controllers\Api\Seeker\Home;

use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        // Check if the search text is empty
        $searchText = $request->input('search');
        if (empty($searchText)) {
            return $this->handleResponse(message: 'Please Enter Text');
        }

        $advisorsBySeekerName = DB::table('advisors')
            ->select('advisors.id', 'seekers.name', 'advisors.bio')
            ->join('seekers', 'advisors.seeker_id', '=', 'seekers.id')
            ->where('seekers.name', 'like', '%' . $searchText . '%');

        $advisorsBySkills = DB::table('advisors')
            ->select('advisors.id', 'seekers.name', 'advisors.bio')
            ->join('seekers', 'advisors.seeker_id', '=', 'seekers.id')
            ->whereIn('advisors.id', function($query) use ($searchText) {
                $query->select('advisor_id')
                    ->from('advisor_skill')
                    ->whereIn('skill_id', function($subquery) use ($searchText) {
                        $subquery->select('id')
                            ->from('skills')
                            ->where('name', 'like', '%' . $searchText . '%');
                    });
            });

        $advisors = $advisorsBySeekerName->union($advisorsBySkills)->get();

        // Check if the data is empty and return a message
        if ($advisors->isEmpty()) {
            return $this->handleResponse(message: 'Not Found Advisor And Skills');
        }

        $advisorsData = [];
        foreach ($advisors as $advisor) {
            // Assuming you have a 'profile_image' media in your media library
            $mediaUrl = Advisor::find($advisor->id)->getFirstMediaUrl('advisor_profile_image');

            // Retrieve the skills of the advisor
            $skills = DB::table('skills')
                ->select('skills.name')
                ->join('advisor_skill', 'skills.id', '=', 'advisor_skill.skill_id')
                ->where('advisor_skill.advisor_id', $advisor->id)
                ->pluck('name');

            // Create a new array with additional data including skills and media URL
            $advisorData = [
                'name' => $advisor->name,
                'bio' => $advisor->bio,
                'advisor_profile_image' => $mediaUrl,
                'skills' => $skills,
            ];
            $advisorsData[] = $advisorData;
        }

        // Return the modified data
        return $this->handleResponse(data: $advisorsData);
    }

}


