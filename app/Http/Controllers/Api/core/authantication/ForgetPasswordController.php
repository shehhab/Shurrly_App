<?php

namespace App\Http\Controllers\Api\core\authantication;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Emails\ForgetPasswordEmail;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Models\Seeker;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Response;

class ForgetPasswordController extends Controller
{


    public function __invoke(ForgetPasswordRequest $request)
    {
        // validate the data from request
        $validatedData = $request->validated();
        $seeker = Seeker::where('email', $validatedData['email'])->first();

        //creat a new OTP
        $otp = new Otp;
        $code = $otp->generate($seeker->email, 'numeric', 4, 300);


        //otp send email to user
        try {
        Mail::to($seeker->email)->send(new ForgetPasswordEmail($seeker, $code->token));
    } catch (\Throwable $th) {
        return $this->handleResponse(status:false,message:'OTP Mail Service Error',code:Response::HTTP_INTERNAL_SERVER_ERROR);
    }
        return $this->handleResponse(status:true ,message:'OTP Code Sent Successfully.');
    }
}
