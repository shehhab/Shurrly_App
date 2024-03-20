<?php

namespace App\Http\Controllers\Api\Seeker\Auth;

use App\Http\Controllers\Controller;
use App\Models\Seeker;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Auth\LoginResource;
use Illuminate\Http\Request;

class SocialGoogle extends Controller
{
    public function socialLogin($provider,Request $request){
        switch($provider){
            case 'google':
                return $this->googleLogin($request);
            default:
                return $this->handleResponse(status:false,message:'not supported social login ');
        }
    }
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        $data = Socialite::driver('google')->redirect();
        if (request()->wantsJson()) return $this->handleResponse(data: ['url' => $data->getTargetUrl()]);
        return $data;
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function googleLogin()
    {
        try {
            // Retrieve the user's access token from the mobile app
            $accessToken = request()->input('access_token');

            // Authenticate using the social provider and exchange access token
            $socialUser = Socialite::driver('google')->stateless()->userFromToken($accessToken);
            $user = Seeker::where('provider_id', $socialUser->getId())->first();
            // dd($socialUser->getId());
            if (!$user) {
               $user =  Seeker::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'provider' => 'google',
                    'password' => Hash::make(md5(time().time())), // You can set a default password or leave it empty
                    'provider_id' => $socialUser->getId(),
                    'email_verified_at'=>now()
                ]);
            }

            // you can create a new user or log in an existing user.
            // Return a response to the mobile application
            return $this->handleResponse(data:new LoginResource($user));
        } catch (\Exception $e) {
            // Handle any errors that occur during the login process
            return $this->handleResponse(status:false,message:'Login Failed');
        }
    }
}
