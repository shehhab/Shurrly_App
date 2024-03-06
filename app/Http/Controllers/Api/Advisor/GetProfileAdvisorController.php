<?php

namespace App\Http\Controllers\Api\Advisor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Advisor\DayResources;
use App\Http\Resources\Advisor\LoginAdvisorResources;
use App\Http\Resources\Advisor\GetProfileAdvisorResources;

class GetProfileAdvisorController extends Controller
{
    public function __invoke(){
        $advisor = Auth::user();
        $data = [
            'message' => new GetProfileAdvisorResources($advisor),
            'days' => new DayResources(['days' => $advisor->days, 'offlineDays']),
        ];
        return $this->handleResponse(
            message: 'Profile advisor',
            data:$data

        );


    }}
