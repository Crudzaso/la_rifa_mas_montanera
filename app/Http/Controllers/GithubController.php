<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GoogleUser;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Helpers\EmailHelperGlobal;
use App\Service\DiscordWebhookService;
use App\Events\UserLogin;
use App\Events\UserCreated;

class GithubController extends Controller
{
    protected $emailHelper;

    public function __construct(EmailHelperGlobal $emailHelper)
    {
        $this->emailHelper = $emailHelper;
    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }


    

    public function callback()
    {
        try {
            $user_github = Socialite::driver('github')->user();
            

            $user = User::firstOrCreate(
                ['email' => $user_github->getEmail()],
                [
                    'name' => $user_github->getName(),
                    'avatar' => $user_github->getAvatar(), 
                    'github_id' => $user_github->getId(),
                ]
            );
    
            Auth::login($user, true);
    
            return redirect()->route('dashboard')->with('user_name', $user->name);
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Error al iniciar sesión con Github');
        }
    }

    public function showLoginForm()
    {
    return Inertia::render('Auth/Login', [
        'user_name' => Auth::check() ? Auth::user()->name : null,
    ]);
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    } 

}