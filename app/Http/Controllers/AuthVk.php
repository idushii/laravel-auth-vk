<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthVk extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback()
    {
        $user = Socialite::driver('vkontakte')->user();
        $userL = Auth::attempt(['vk_id' => $user->id, 'password' => 'rand(0, 1000)']);
        //var_dump($user);
        //var_dump($userL);
        if ($userL) {
            return redirect()->to('/home');
        } else {
            $params = [
                'vk_id' => $user->id, 'vk_token' => $user->token,
                'name'  => $user->name,
                'email' => $user->email, 'password' => Hash::make('rand(0, 1000)')
            ];
            User::create($params);
            Auth::attempt(['vk_id' => $user->id]);
            return redirect()->to('/home');
        }
    }
}
