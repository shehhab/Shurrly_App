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

        $advisors = Advisor::where('approved', false)->paginate(10);
        return view('admin.users.index', compact('advisors'));

    }

    public function approve(Advisor $advisor)
{
    $advisor->assignRole('advisor');
    $advisor->update(['approved' => 1]);
    return back();
}

}
