<?php

namespace App\Http\Controllers\Api\core\authantication;

use Ichtrojan\Otp\Otp;
use App\Traits\AuthResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Seeker;
use App\Http\Requests\Auth\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    use AuthResponse;
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp;
    }
    public function __invoke(ResetPasswordRequest $request)
    {

        $validatedData = $request->validated();

        // reset password logic
        $otp2 = $this->otp->validate($validatedData['email'], $validatedData['otp']);
        if (!$otp2->status) {
            return $this->OTP_Error_Response();
        }
        $seeker = Seeker::where('email', $validatedData['email'])->first();
        $seeker->update(['password' => Hash::make($validatedData['password'])]);
        $seeker->tokens()->delete();

        return $this->OKResponse('Password Reset Successfully');
    }
}
