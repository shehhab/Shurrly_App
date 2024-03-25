<?php

namespace App\Http\Controllers\Api\Advisor;

use Exception;
use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Advisor\DayResources;
use App\Http\Resources\Advisor\LoginAdvisorResources;

class LoginAdvisorController extends Controller
{
    public function __invoke(LoginRequest $request , Exception $e)
    {
        $validatedData = $request->validated();

        // Check for the existence of the Seeker
        $seeker = Seeker::where('email', $validatedData['email'])->first();

        if ($seeker) {
            // Check for the existence of an Advisor associated with the Seeker
            $advisor = Advisor::where('seeker_id', $seeker->id)->first();

            if ($advisor) {
                // If the user is associated with an Advisor and approval is granted, proceed with authentication
                if ($advisor->approved == 1 && Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
                    // Successful authentication, return the appropriate response
                // Get the available days for the advisor
                $availableDays = $advisor->Day;

                // Successful authentication, return the appropriate response
                return response()->json([
                    'status' => true,
                    'message' => 'Login successful',
                    'data' => [
                        'user' => new LoginAdvisorResources(Auth::user()),
                        'available_days' => new DayResources($availableDays),
                    ]
                ]);

            } elseif ($advisor->approved == 0) {
                    // If the account is not approved yet, return a message indicating to wait for approval
                    return $this->handleResponse(status:false, code:403 ,message:'Your account is pending approval. Please wait for confirmation.', data: []);
                } else {
                    // If the password doesn't match, return the appropriate error message
                    return $this->handleResponse(status:false, code:401 ,message:'Wrong Email Or Password!', data: []);
                }
            } else {
                // If the user is not associated with an Advisor, deny access
                return $this->handleResponse(status:false, code:403 ,message:'Access Denied! You are not an advisor. Please create an Advisor account.', data: []);
            }
        } else {
            // If the Seeker is not found, return the appropriate error message
            return $this->handleResponse(status:false, code:404 ,message:'Account not found.', data: []);
        }
    }


}
