<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Api routes for controllers Auth
use App\Http\Controllers\TestController;
use App\Http\Controllers\Api\Auth\SocialGoogle;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Advisor\DayController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\GetProfileController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\Auth\UpdateProfileController;

// Api routes for controllers advisor

use App\Http\Controllers\Api\Advisor\LoginAdvisorController;
use App\Http\Controllers\Api\Advisor\CreateAdvisorController;
use App\Http\Controllers\Api\Advisor\GetProfileAdvisorController;

// Api routes for controllers core

use App\Http\Controllers\Api\core\authantication\LogoutController;
use App\Http\Controllers\Api\Advisor\UpdateProfileAdvisorController;
use App\Http\Controllers\Api\core\authantication\ValidOTPController;
use App\Http\Controllers\Api\core\authantication\DeleteAccountController;
use App\Http\Controllers\Api\core\authantication\ResendOTPCodeController;
use App\Http\Controllers\Api\core\authantication\ResetPasswordController;
use App\Http\Controllers\Api\core\authantication\ChangePasswordController;
use App\Http\Controllers\Api\core\authantication\ForgetPasswordController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\ChatMessageController;
use App\Http\Controllers\Chat\SeekerController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/
//Route::post('/test',[TestController::class,'store']);

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('seeker', fn (Request $request) => $request->user())->name('seeker');
;

});




//------------------------------------------Seeker-------------------------------------------------

    //  API  routes seeker/auth
    Route::group(['prefix'=>'v1/seeker/auth' ],function(){
            //Route::post('/test',[TestController::class,'store']);   // !  my test PRIVATE
            //Route::get('/test',[TestController::class,'test']);     // ! my test PRIVATE
            Route::post('/register', RegisterController::class);
            Route::post('/login', LoginController::class);
            Route::post('login/{provider}', [SocialGoogle::class,'socialLogin']);


            // API routes for middleware seeker token authentication
        Route::group(['middleware'=>'auth:sanctum'],function(){
            Route::apiResource('chat', ChatController::class )->only('index','store','show');

            Route::apiResource('chat_message', ChatMessageController::class )->only('index','store');
            Route::apiResource('user', SeekerController::class )->only('index');


            Route::post('/verify_email', VerifyEmailController::class);
                Route::post('/update/profile', UpdateProfileController::class);
                Route::get('/getprofile', GetProfileController::class);
            });
        });


//---------------------------------------------Advisor----------------------------------------------


    //  API  routes advisor/auth
    Route::group(['prefix'=>'v1/advisor/auth' ],function(){
            Route::post('/login_advisor', LoginAdvisorController::class);
    // API routes for middleware advisor token authentication
        Route::group(['middleware'=>'auth:sanctum'],function(){
            Route::post('/create_advisor', CreateAdvisorController::class);
            Route::post('/update_profile_advisor', UpdateProfileAdvisorController::class);
            Route::get('/get_profile_advisor', GetProfileAdvisorController::class);

        });

    });

//-----------------------------------------------Core--------------------------------------------

    //  API  routes core/auth
        Route::group(['prefix'=>'v1/core/auth' ],function(){
            Route::post('/forget_password', ForgetPasswordController::class);
            Route::post('/reset_password', ResetPasswordController::class);
            Route::post('/resend_otp', ResendOTPCodeController::class);

    // API routes for middleware core token authentication
            Route::group(['middleware'=>'auth:sanctum'],function(){
                Route::post('/change_password', ChangePasswordController::class);
                Route::post('/delete_account', DeleteAccountController::class);
                Route::post('/logout', LogoutController::class);
                Route::post('/check_otp', ValidOTPController::class);
            });
        });
