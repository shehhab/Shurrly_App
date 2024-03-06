<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //public function test(Request $request)
    //{
       // return $this->handleResponse(status: true, message: 'data', code: 200);
    //}
    public function searchForSkill(Request $request)
    {
        $skills = Skill::where('public',1)->where('name', 'LIKE', '%' . $request->search . '%')->get();
        return $this->handleResponse(data: $skills);
    }

    //public function store(Request $request)
    //{
        //$generatedSkills = [];
        //$advisorId = 4 ;
        //foreach ($request->skills as $skill) {
            //array_push($generatedSkills, Skill::firstOrCreate(['name' => $skill])->id);
        //}
        //$advisor = Advisor::where('id',$advisorId)->first();
        //return $this->handleResponse(data: $generatedSkills);
    //}

    // in search BAR To Get All Attributes From Attributes
    public function userSearchForSkill(Request $request){
        Seeker::role('advisor')->whereHas('skills',function($q) use($request){
            $q->where('name','LIKE','%'.$request->name.'%');
        });
    }



}
