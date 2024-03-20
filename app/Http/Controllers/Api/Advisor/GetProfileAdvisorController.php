<?php

namespace App\Http\Controllers\Api\Advisor;

use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Advisor\DayResources;
use App\Http\Requests\Advisor\GetProfileAdvisorRequest;
use App\Http\Resources\Advisor\GetProfileAdvisorResources;

class GetProfileAdvisorController extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);

        $advisor = Advisor::find($validatedData['id']);

        if (!$advisor) {
            return  $this->handleResponse( status: false ,  message: 'Advisor not found', code : 404);
        }


        $data = [
            'message' => new GetProfileAdvisorResources($advisor),
            //'days' => new DayResources(['days' => $advisor->days]),
        ];

        return $this->handleResponse(
            message: 'Profile advisor',
            data : $data
        );
    }

}
