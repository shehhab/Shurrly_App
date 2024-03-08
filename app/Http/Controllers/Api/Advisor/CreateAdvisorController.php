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

        // Check if the user is authenticated
        if (!$seeker) {
            return $this->handleResponse(message: 'Unauthorized', status: false, code: 401);
        }

        // Check if advisor already updated data
        $existingAdvisor = Advisor::where('seeker_id', $seeker->id)->first();

        if ($existingAdvisor) {
            return $this->handleResponse(message: 'You are already registered as an advisor. Please go to Login.', status: false, code: 422);
        }

        if (!empty($request->skills) && !empty($dayRequest->days)) {
            $advisor = Advisor::create([
                'bio' => $validatedData['bio'],
                'expertise' => $validatedData['expertise'],
                'Offere' => $validatedData['Offere'],
                'seeker_id' =>  $seeker->id,
                'available' => $validatedData['available'],
                'approved' => false, // For Testing. Change to false after dashboard
            ]);

            // To add a new skill in the skill table and update advisor skills
            $generatedSkills = [];
            foreach ($request->skills as $skill) {
                array_push($generatedSkills, Skill::firstOrCreate(['name' => $skill])->id);
            }

            $advisor->skills()->attach($generatedSkills);

            // Upload image
            if ($request->hasFile('image')) {
                $advisor->addMediaFromRequest('image')->toMediaCollection('advisor_profile_image');
            }

            // Upload video
            if ($request->hasFile('video')) {
                $advisor->addMediaFromRequest('video')->toMediaCollection('advisor_Intro_video');
            }

            // Upload certificates
            if ($request->hasFile('certificates')) {
                $advisor->addMediaFromRequest('certificates')->toMediaCollection('advisor_Certificates_PDF');
            }

            // Process days
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

                // Create Day record
                $days = Day::create([
                    'day' => $day['day'],
                    'from' => $day['from'],
                    'to' => $day['to'],
                    'seeker_id' => $seeker->id,
                    'available' => true,
                    'total_time' => $totalTime, // Total available time in datetime
                    'break_from' => $day['break_from'] ?? null, // Store break_from
                    'break_to' => $day['break_to'] ?? null, // Store break_to
                    'total_break_time' => gmdate('H:i', $totalBreakTime), // Total break time in datetime
                ]);
            }

            $data = [
                'message' => new UploadResources($advisor),
                'days' => new DayResources(['days' => $seeker->days, 'offlineDays']),
            ];

            return $this->handleResponse(
                message: 'Successfully Create Advisor',
                data: $data
            );
        } else {
            return $this->handleResponse(message: 'Skills and Days are required', status: false, code: 406);
        }
    }
}
