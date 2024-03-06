<?php

namespace App\Http\Controllers\Api\Advisor;

use App\Models\Skill;
use App\Models\Advisor;
use App\Models\Day;
use App\Http\Controllers\Controller;
use App\Http\Resources\Advisor\UploadResources;
use App\Http\Requests\Advisor\CreateAdvisorRequest;
use App\Http\Requests\Advisor\DayRequest;
use App\Http\Resources\Advisor\DayResources;

class CreateAdvisorController extends Controller
{
    public function __invoke(CreateAdvisorRequest $request, DayRequest $dayRequest)
    {
        $validatedData = $request->validated();
        $seeker = request()->user();
    // Check if advisor already update data
    $existingAdvisor = Advisor::where('seeker_id', $seeker->id)->first();

    if ($existingAdvisor) {
        return $this->handleResponse(message: 'You are already registered as an advisor Go to Login.', status: false ,code:422);
    }

        if (!empty($request->skills) && !empty($dayRequest->days)) {
            $advisor = Advisor::create([
                'bio' => $validatedData['bio'],
                'expertise' => $validatedData['expertise'],
                'Offere' => $validatedData['Offere'],
                'seeker_id' =>  $seeker->id,
                'available' => $validatedData['available'],
                'approved' => false, // ! For Testing change to false after dashboard
            ]);




            // To add a new skill in table skill and update advisor skills
            $generatedSkills = [];
            $advisorId = $seeker->id ;

            foreach ($request->skills as $skill) {
                array_push($generatedSkills, Skill::firstOrCreate(['name' => $skill])->id);
            }

            //FOR ADDING TO EDIT skill TO EDIT
            $advisor = Advisor::where('id',$advisorId)->first();
            $advisor->skills()->attach($generatedSkills);
                 // upload image

        if ($request->hasFile('image')) {

            $advisor->addMediaFromRequest('image')->toMediaCollection('advisor_profile_image');
        }
        // upload video

        if ($request->hasFile('video')) {

            $advisor->addMediaFromRequest('video')->toMediaCollection('advisor_Intro_video');
        }

        if ($request->hasFile('certificates')) {

            $advisor->addMediaFromRequest('certificates')->toMediaCollection('advisor_Certificates_PDF');
        }


            // Process days
            $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $seeker->days()->delete();
            foreach ($dayRequest['days'] as $day) {
                $from = strtotime($day['from']);
                $to = strtotime($day['to']);
                $totalTime = gmdate('H:i', abs($to - $from)); // to calculate total time in day
                $breakFrom = isset($day['break_from']) ? strtotime($day['break_from']) : null;
                $breakTo = isset($day['break_to']) ? strtotime($day['break_to']) : null;

                // Check if break times are within the range of 'from' and 'to'
                if (($breakFrom !== null && ($breakFrom <= $from || $breakFrom >= $to)) || ($breakTo !== null && ($breakTo <= $from || $breakTo >= $to))) {
                    return $this->handleResponse(message: 'Invalid break time', code: 400, status: false);
                }

                // Calculate total break time
                $totalBreakTime = 0;
                if ($breakFrom !== null && $breakTo !== null) {
                    $totalBreakTime = abs($breakTo - $breakFrom);
                }

                // Calculate total time in hours
                $totalHours = floor($to - $from) / 3600;

                // Set break time based on total time
                if ($totalHours >= 2 && $totalHours <= 4) {
                    $maxBreakTime = 1800; // 30 minutes
                } elseif ($totalHours > 4 && $totalHours <= 8) {
                    $maxBreakTime = 3600; // 1 hour
                } elseif ($totalHours > 8 && $totalHours <= 12) {
                    $maxBreakTime = 9000; // 2.5 hours
                } else {
                    return $this->handleResponse(message: 'total time must be Two Hours or More', code: 400, status: false);
                }

                // Ensure break time does not exceed the maximum allowed
                if ($totalBreakTime > $maxBreakTime) {
                    return $this->handleResponse(message: 'Break time exceeds maximum allowed', code: 400, status: false);
                }

                // Create Day record
                foreach ($request['days'] as $day) {
                    $days = Day::create([
                        'day' => $day['day'],
                        'from' => $day['from'],
                        'to' => $day['to'],
                        'seeker_id' => $seeker->id,
                        'available' => true,
                        'total_time' =>  $totalTime,                // Total available time in datetime
                        'break_from' => $day['break_from'] ?? null, //      store break_from
                        'break_to' => $day['break_to'] ?? null,     //      store break_to
                        'total_break_time' => gmdate('H:i', $totalBreakTime), // Total break time in datetime

                    ]);
                    unset($allDays[array_search($day['day'], $allDays)]);
                }

                $data = [
                    'message' => new UploadResources($advisor),
                    'days' => new DayResources(['days' => $seeker->days, 'offlineDays']),
                ];
                return $this->handleResponse(
                    message: 'Successfully Create Advisor',
                    data:$data

                );
                    }
    }

        else {
            return $this->handleResponse(message: 'Skills and Days are required', status: false, code: 406);
        }
    }
}


