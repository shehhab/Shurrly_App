<?php

namespace App\Http\Controllers\Api\core\authantication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteAccountController extends Controller
{

    public function __invoke(Request $request)
    {
        auth()->user()->tokens()->delete();
        auth()->user()->delete();
        return $this->handleResponse(message:'Account deleted successfully.');
    }
}
