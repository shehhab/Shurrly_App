<?php

namespace App\Http\Controllers\Api\core\authantication;

use Ichtrojan\Otp\Otp;
use App\Traits\AuthResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ValidOTPRequest;
use App\Models\Seeker;
use App\Models\User; // Add this line if not already imported

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

        // Check if the user's email exists
        $user = Seeker::where('email', $validatedData['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => false,
                'code' => 404
            ], 404);
        }

        // Check if OTP is valid
        $otp = DB::table('otps')
            ->where('identifier', $validatedData['email'])
            ->latest()
            ->first();

        if ($otp && $otp->token == $validatedData['otp']) {
            // Update user's email verification status
            $user->update(['email_verified_at' => now()]);
            return response()->json([
                'message' => 'OTP correct',
                'status' => true,
                'code' => 200,
            ], 200);
        } else {
            return response()->json([
                'message' => 'OTP incorrect or expired',
                'status' => false,
                'code' => 404
            ], 404);
        }
    }
}
