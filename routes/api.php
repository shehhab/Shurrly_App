<?php

use App\Http\Controllers\Api\Advisor\Chat\ChatAdvisorController;
use Pusher\Pusher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Api routes for controllers Auth
use App\Http\Controllers\Api\Seeker\Auth\SocialGoogle;


// Api routes for controllers advisor
use App\Http\Controllers\Api\Seeker\Home\HomeController;
use App\Http\Controllers\Api\Seeker\Auth\LoginController;
use App\Http\Controllers\Api\Seeker\Home\SearchController;
use App\Http\Controllers\Api\Advisor\LoginAdvisorController;


// Api routes for controllers seeker

use App\Http\Controllers\Api\Seeker\Auth\RegisterController;
use App\Http\Controllers\Api\Advisor\CreateAdvisorController;
use App\Http\Controllers\Api\Advisor\GetDataSkillController;
use App\Http\Controllers\Api\Seeker\Explore\ExploreController;
use App\Http\Controllers\Api\Seeker\Auth\VerifyEmailController;
use App\Http\Controllers\Api\Advisor\GetProfileAdvisorController;
use App\Http\Controllers\Api\Seeker\Profile\GetProfileController;
use App\Http\Controllers\Api\Advisor\Product\AddProductController;
use App\Http\Controllers\Api\core\authantication\LogoutController;

// Api routes for controllers core

use App\Http\Controllers\Api\Seeker\Explore\PageProductController;
use App\Http\Controllers\Api\Advisor\UpdateProfileAdvisorController;
use App\Http\Controllers\Api\core\authantication\ValidOTPController;
use App\Http\Controllers\Api\Seeker\Profile\UpdateProfileController;
use App\Http\Controllers\Api\core\authantication\DeleteAccountController;
use App\Http\Controllers\Api\core\authantication\ResendOTPCodeController;
use App\Http\Controllers\Api\core\authantication\ResetPasswordController;

use App\Http\Controllers\Api\core\authantication\ChangePasswordController;
use App\Http\Controllers\Api\core\authantication\ForgetPasswordController;
use App\Http\Controllers\Api\Seeker\Chat\ChatController as ChatChatController;
use App\Http\Controllers\Api\Seeker\Explore\UnSave_SaveProductController;
use App\Http\Controllers\Api\Seeker\Explore\ViewProductSavedController;
// use App\Http\Controllers\Chat\{ChatController, ChatMessageController, SeekerController};
use App\Http\Controllers\Api\core\Chat\ChatController;
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
    Route::post('/broadcasting/auth', function (Request $request) {
        $user = auth()->user(); // Get the authenticated user

        // $channelName = 'presence-chat.{1}.1.2';
        $channelName = $request->channelName;
        $socketId = $request->input('socket_id');

        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'encrypted' => true
            ]
        );

        $presenceData = [
            'user_id' => $user->id,
            'user_info' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ];

        $auth = $pusher->presence_auth($channelName, $socketId, $user->id, $presenceData);

        return response($auth);
    });
    Route::get('chats',[ChatController::class,'index']);
    Route::post('chat-send-message', [ChatController::class, 'sendMessage']);
    // Route::post('seeker-send-message', [ChatChatController::class, 'sendMessage']);
    // Route::post('advisor-send-message', [ChatAdvisorController::class, 'sendMessage']);
    Route::get('seeker', fn (Request $request) => $request->user())->name('seeker');;
});




//------------------------------------------Seeker-------------------------------------------------

//  API  routes seeker/auth
Route::group(['prefix' => 'v1/seeker/auth'], function () {
    //Route::post('/test',[TestController::class,'store']);   // !  my test PRIVATE
    //Route::get('/test',[TestController::class,'test']);     // ! my test PRIVATE
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    Route::post('login/{provider}', [SocialGoogle::class, 'socialLogin']);


    // API routes for middleware seeker token authentication
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('chat', ChatController::class)->only('index', 'store', 'show');

        Route::post('/verify_email', VerifyEmailController::class);
        Route::post('/update/profile', UpdateProfileController::class);
        Route::get('/getprofile', GetProfileController::class);

        Route::get('/saved_products', ViewProductSavedController::class);
    });
});


//---------------------------------------------Advisor----------------------------------------------


//  API  routes advisor/auth
Route::group(['prefix' => 'v1/advisor/auth'], function () {
    Route::post('/login_advisor', LoginAdvisorController::class);
    Route::get('/get_profile_advisor', GetProfileAdvisorController::class);
    Route::get('/get_skill', GetDataSkillController::class);


    // API routes for middleware advisor token authentication
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/create_advisor', CreateAdvisorController::class);
        Route::post('/update_profile_advisor', UpdateProfileAdvisorController::class);
        Route::post('/add_products', AddProductController::class);
    });
});

//-----------------------------------------------Core--------------------------------------------

//  API  routes core/auth
Route::group(['prefix' => 'v1/core/auth'], function () {
    Route::post('/forget_password', ForgetPasswordController::class);
    Route::post('/resend_otp', ResendOTPCodeController::class);
    Route::post('/reset_password', ResetPasswordController::class);
    Route::post('/check_otp', ValidOTPController::class);

    // API routes for middleware core token authentication
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/change_password', ChangePasswordController::class);
        Route::post('/delete_account', DeleteAccountController::class);
        Route::post('/logout', LogoutController::class);
    });
});




Route::group(['prefix' => 'v1/home'], function () {

    Route::get('', HomeController::class);
    Route::get('/search', SearchController::class);

    Route::get('/Explore', ExploreController::class);
    Route::post('/product_page', PageProductController::class);

    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::post('/save-or-unsave-product', UnSave_SaveProductController::class);
    });
});
