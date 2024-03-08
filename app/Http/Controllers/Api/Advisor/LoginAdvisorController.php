<?php

namespace App\Http\Controllers\Api\Advisor;

use Exception;
use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Advisor\DayResources;
use App\Http\Resources\Advisor\LoginAdvisorResources;

class LoginAdvisorController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validated();

        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            $advisor = Auth::user();
            $existingAdvisor = Advisor::where('seeker_id', $advisor->id)->first();

            // Check if the advisor account exists
            if (!$existingAdvisor) {
                return $this->handleResponse(status: false, message: 'Must create an advisor account.', code: 422);
            }

            // Check if the advisor account is approved
            if ($existingAdvisor->approved === 0) {
                return $this->handleResponse(status: false, message: 'Waiting for approval.');
            }

            // Prepare the response data
            $data = [
                'message' => new LoginAdvisorResources($advisor),
                'days' => new DayResources(['days' => $advisor->days, 'offlineDays']),
            ];

            return $this->handleResponse(message: 'Welcome Back Advisor', data: $data);
        }

        return $this->handleResponse(status: false, message: 'Wrong Email Or Password!');
    }

}
