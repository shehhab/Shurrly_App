<?php

namespace App\Http\Controllers\Api\core\authantication;
use Ichtrojan\Otp\Otp;
use App\Traits\AuthResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ValidOTPRequest;

class ValidOTPController extends Controller
{
    use AuthResponse;
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp;
    }
    public function __invoke(ValidOTPRequest $request)
    {
        $validatedData = $request->validated();

        // Check if OTP is valid
        $otp = DB::table('otps')
            ->where('identifier', auth()->user()->email)
            ->latest()
            ->first();
        if ($otp->token == $validatedData['otp']) {
            auth()->user()->update(['email_verified_at'=>now()]);
            return response()->json([
                'message' => 'otp correct',
                'status' => true,
                'code' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'otp not correct',
                'status' => false,
                'code' => 404
            ], 404);
        }
    }
}
