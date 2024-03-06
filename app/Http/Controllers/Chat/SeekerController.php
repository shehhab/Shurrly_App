<?php

namespace App\Http\Controllers\Chat;

use App\Models\Seeker;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class SeekerController extends Controller
{
    /**
     * get users except yourself
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        $seeker =  Seeker::Where('id','!=', auth()->user()->id)->get() ;

        return $this->handleResponse(data:$seeker);
    }
}
