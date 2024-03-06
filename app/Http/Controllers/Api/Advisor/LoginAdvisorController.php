<?php

namespace App\Http\Controllers\Api\Advisor;

use Exception;
use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Resources\Advisor\DayResources;
use App\Http\Resources\Advisor\UploadResources;
use App\Http\Resources\Advisor\LoginAdvisorResources;

class LoginAdvisorController extends Controller
{
    public function __invoke(LoginRequest $request)
    {

        $validatedData = $request->validated();


        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {

            $advisor = Auth::user();
            $existingAdvisor = Advisor::where('seeker_id', $advisor->id)->first();


            // to retuen message to send many requests login email
            if (RateLimiter::tooManyAttempts('send-message:'.auth()->user(), $perMinute = 3)) {
                $seconds = RateLimiter::availableIn('send-message:'.auth()->user());


                return $this->handleResponse(status:false,message:'too Many Attempts , You may try again in '.$seconds.' seconds.' , code:429);


            }

            RateLimiter::hit('send-message:'.auth()->user());
            //check advisor create account
                if (!$existingAdvisor) {
                    if (RateLimiter::tooManyAttempts('send-message:'.auth()->user(), $perMinute = 3)) {
                        $seconds = RateLimiter::availableIn('send-message:'.auth()->user());

                        return $this->handleResponse(status:false,message:'too Many Attempts , You may try again in '.$seconds.' seconds.' , code:429);

                    }
                            return $this->handleResponse(status: false, message: 'Must be Create Account Advisor Go to Register.' ,code: 422 );

                }
                        //check advisor create account
                if ($existingAdvisor) {
                        // check account approved
                    if ($existingAdvisor->approved === 0) {
                        //  return respose if approved equel false
                        return $this->handleResponse(status: false, message: 'Waiting for approval');
                    }
                    if($existingAdvisor->approved === 1) {
                        //  return respose if approved equel true


                        $data = [
                            'message' => new LoginAdvisorResources($advisor),
                            'days' => new DayResources(['days' => $advisor->days, 'offlineDays']),
                        ];
                        return $this->handleResponse(
                            message: 'Welcome Back Advisor',
                            data:$data

                        );




                        //return $this->handleResponse(status: true, message: 'Welcome Back Advisor ' . $advisor->name, data: new LoginAdvisorResources($advisor));
                    }
                }

        }
        // to retuen message to send many requests when wrong password

        if (RateLimiter::tooManyAttempts('send-message:'.auth()->user(), $perMinute = 3)) {
            $seconds = RateLimiter::availableIn('send-message:'.auth()->user());


            return $this->handleResponse(status:false,message:'too Many Attempts , You may try again in '.$seconds.' seconds.' , code:429);


        }

        RateLimiter::hit('send-message:'.auth()->user());


        return $this->handleResponse(status:false,message:'Wrong Email Or Password!');


    }
}
