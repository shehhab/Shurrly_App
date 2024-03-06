<?php

namespace App\Http\Controllers\Admin;

use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserAdminController extends Controller
{
    public function index(){

        $data['users'] = Seeker::join('advisors', 'seekers.id', '=', 'advisors.seeker_id')
        ->select('seekers.*', 'advisors.*')->where('approved' , False)->paginate(10);
        $data['advisor'] = Advisor::first();
        return view('admin.users.index', $data);

    }

    public function approve(Advisor $advisor)
{
    $advisor->assignRole('advisor');
    $advisor->update(['approved' => 1]);
    return back();
}

}
