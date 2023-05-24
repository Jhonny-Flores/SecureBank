<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::view('/', 'login')->name('login');

Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/google-callback', function () {
    $user = Socialite::driver('google')->user();

    $userExists = User::where('external_id', $user->id)->where('external_auth', 'google')->first();

    if ($userExists) {
        Auth::login($userExists);
    } else {
        $userNew = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'external_id' => $user->id,
            'external_auth' => 'google',
        ]);

        Auth::login($userNew);
    }

    return redirect('/dashboard');
});
