<?php
namespace App\Http\Controllers\Api\Auth;
use App\Models\Seeker;
use Ichtrojan\Otp\Otp;
use App\Traits\AuthResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailRequest;
use Illuminate\Support\Facades\Validator;

class VerifyEmailController extends Controller
{
    use AuthResponse;

    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function __invoke(VerifyEmailRequest $request)
    {
        // Get authenticated user
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return $this->UnauthenticatedResponse();
        }

        // Check if user has email
        $email = $user->email;
        if (!$email) {
            return $this->MissingEmailResponse();
        }

        // Validate request
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return $this->ValidationFailureResponse($validator->errors());
        }

        // Verify OTP
        $otpStatus = $this->otp->validate($email, $request->otp);
        if (!$otpStatus->status) {
            return $this->OTP_Error_Response();
        }

        // Update email_verified_at to current time
        $seeker = Seeker::where('email', $email)->first();
        if (!$seeker) {
            return $this->UserNotFoundResponse();
        }

        $seeker->email_verified_at = now();
        $seeker->save();

        // Redirect to success page or return success response
        return $this->EmailVerifiedResponse($email);
    }
}
