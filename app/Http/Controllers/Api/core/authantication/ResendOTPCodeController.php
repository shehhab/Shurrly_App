<?php

namespace App\Http\Controllers\Api\core\authantication;
use App\Models\Seeker;
use App\Emails\ResendOTPCodeEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendOTPCodeRequest;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Response;


class ResendOTPCodeController extends Controller
{

    public function __invoke(ResendOTPCodeRequest $request)
    {

        $validatedData = $request->validated();

        // resend otp logic
        $seeker = Seeker::where('email', $validatedData['email'])->first();

        $otp = new Otp;

        $code = $otp->generate($validatedData['email'],'numeric',4, 5);



        try {
            Mail::to($validatedData['email'])->send(new ResendOTPCodeEmail($seeker,$code->token));
        } catch (\Throwable $th) {
            return $this->handleResponse(status:false,message:'OTP Mail Service Error',code:Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->handleResponse(message: 'OTP Code Resent Successfully');


    }
}
