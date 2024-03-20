<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\Catogrtoy\Add_Skills;
use App\Http\Controllers\Admin\LoginAdminController;
use App\Http\Controllers\Api\Seeker\Auth\SocialGoogle;
use App\Http\Controllers\Admin\Catogrtoy\SkillsController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('login/google', [SocialGoogle::class,'redirectToGoogle']);
Route::post('login/google/callback', [SocialGoogle::class,'handleGoogleCallback']);

Route::post('/login', [LoginAdminController::class,'']);


Route::prefix('dashboard')->group(function(){
    Route::get('/', [HomeController::class,'index']);
        Route::get('/all_user', [UserAdminController::class,'index']);
        Route::put('/admin/users/{advisor}/approve', [UserAdminController::class, 'approve'])->name('admin.users.approve');
        Route::delete('/admin/user/{id}',  [UserAdminController::class,'destroy'])->name('admin.users.destroy');

    //To Add Skill From Dashboard
        Route::get('/all_Skills', [SkillsController::class,'index']);
        Route::get('/add_Skills', [Add_Skills::class,'store'])->name('products');

        Route::POST('/add_Skills', [Add_Skills::class,'index'])->name('products.index');
        Route::delete('/categories/{id}',  [SkillsController::class,'destroy'])->name('categories.destroy');
        Route::get('categories/{id}/edit', [SkillsController::class,'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [SkillsController::class, 'update'])->name('categories.update');


});
