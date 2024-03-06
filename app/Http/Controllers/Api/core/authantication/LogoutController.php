<?php

namespace App\Http\Controllers\Api\core\authantication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        // Delete user tokens
        $user->tokens()->delete();

        // Return the response
        return $this->handleResponse(message: 'Logged out successfully');
    }
}
