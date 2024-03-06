<?php
namespace App\Http\Controllers\Api\Auth;

use App\Models\Seeker;
use Ichtrojan\Otp\Otp;
use App\Emails\EmailVerification;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\SeekerResource;
use Illuminate\Support\Facades\RateLimiter;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request) {

        $validatedData = $request->validated();

        $validatedData['date_birth'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validatedData['date_birth']);
        // register logic

        $seeker = Seeker::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'date_birth' => $validatedData['date_birth'],
        ]);
        $seeker->assignRole('seeker');

        RateLimiter::hit('send-message:'.auth()->user());

        if ($request->hasFile('image')) {
            $seeker->addMediaFromRequest('image')->toMediaCollection('seeker_profile_image');
        }

        // after register operation
        // 1- send email verification notification contains OTP
        $otp = new Otp;
        $code = $otp->generate($validatedData['email'],'numeric',4, 5);

        Mail::to($validatedData['email'])->send(new EmailVerification($seeker,$code->token));

        // 2- send api response to front
        return $this->handleResponse(message:'Successfully Created Account , Verify Your Email please',data:new SeekerResource($seeker));


    }


}
