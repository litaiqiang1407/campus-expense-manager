<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $authUser = User::where('facebook_id', $user->getId())->first();
        
        if ($authUser) {
            Auth::login($authUser, true);
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'facebook_id' => $user->getId(),
                'avatar' => $user->getAvatar(),
                'password' => encrypt('123456dummy')
            ]);
            Auth::login($newUser, true);
        }

        return redirect()->intended('/');
    }
}
