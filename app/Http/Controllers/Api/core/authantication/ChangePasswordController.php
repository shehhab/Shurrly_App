<?php

namespace App\Http\Controllers\Api\core\authantication;

use App\Models\Seeker;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use App\Http\Requests\Auth\changePasswordRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class ChangePasswordController extends Controller
{
    public function __invoke(request $request)
    {
     // Validate the request data
         $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => ['required', new StrongPassword],
            'confirm_password' => 'required|same:new_password',
         ]);

        // Check for validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Validation error',
                'data' => $validator->errors()->toArray()
            ], 422);
        }

    // Get the authenticated user
    $user = Auth::user();

    // Check if the current password matches the one in the database
    if (!Hash::check($request->current_password, $user->password)) {
        return $this->handleResponse(message:'Current password is incorrect', code:401 );

    }

    // Hash the new password
    $newPassword = Hash::make($request->new_password);

    // Update the user's password
    $user->password = $newPassword;
    $user->save();

    //updated successfully
    return $this->handleResponse(message:'Password updated successfully');

}
}
