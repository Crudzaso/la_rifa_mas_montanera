<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
});
 
Route::get('http://127.0.0.1:8000/auth/google/callback', function () {
    $user = Socialite::driver('google')->user();

    $userExist = User::where('external_id', $user->id)->where('external_auth', 'google')->exists();

    if($userExist){
        Auth::login($userExist);
    }else{
        $userNew = User::create([
            'name'=> $user->name,
            'email'=> $user->email,
            'avatar'=> $user->avatar,
            'external_id'=> $user->id,
            'external_auth'=> 'google',
        ]);

        Auth::login($userNew);
    }

    return redirect('/dashboard');

});
