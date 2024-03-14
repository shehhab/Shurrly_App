<?php

namespace App\Http\Controllers\Api\home;

use App\Models\Cat;
use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __invoke(request $request){

        $cats = Cat::all();
        $advisors = Advisor::all();

        // تأكد من استخدام الدالة handleResponse بشكل صحيح
        return $this->handleResponse(status: true, message: 'Successfully Get Page Homne', data: [
            'cats' => $cats,
            'advisors' => $advisors
        ]);

    }

}
