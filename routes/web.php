<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Api\Auth\SocialGoogle;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Admin\HomeAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\LoginAdminController;
use App\Http\Controllers\Catogrtoy\Cats;
use App\Http\Controllers\Catogrtoy\ProductController;

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


        Route::get('/all_cats', [Cats::class,'index']);
        Route::get('/add_cats', [ProductController::class,'store'])->name('products');

        Route::POST('/add_cats', [ProductController::class,'index'])->name('products.index');
        Route::delete('/categories/{id}',  [Cats::class,'destroy'])->name('categories.destroy');
        Route::get('categories/{id}/edit', [Cats::class,'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [Cats::class, 'update'])->name('categories.update');


});
