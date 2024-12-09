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

class DiscordController extends Controller
{
    protected $emailHelper;

    public function __construct(EmailHelperGlobal $emailHelper)
    {
        $this->emailHelper = $emailHelper;
    }

    public function redirectToDiscord()
    {
        return Socialite::driver('discord')->redirect();
    }


    

    public function callback()
    {
        try {
            $user_discord = Socialite::driver('discord')->user();
            

            $user = User::firstOrCreate(
                ['email' => $user_discord->getEmail()],
                [
                    'name' => $user_discord->getName(),
                    'avatar' => $user_discord->getAvatar(), 
                    'google_id' => $user_discord->getId(),
                ]
            );
    
            Auth::login($user, true);
    
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Error al iniciar sesión con Discord');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    } 

}