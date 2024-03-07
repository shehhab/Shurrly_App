<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\GetProfileResource;

class GetProfileController extends Controller
{

        public function __invoke(){
            $seeker = Auth::user();


            return $this->handleResponse(status:true, message:'Profile '. $seeker->name , data: new GetProfileResource($seeker) );

        }
}


