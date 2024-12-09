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

class GoogleController extends Controller
{
    protected $emailHelper;

    public function __construct(EmailHelperGlobal $emailHelper)
    {
        $this->emailHelper = $emailHelper;
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    

    public function callback()
    {
        try {
            // Obtener los datos del usuario de Google
            $user_google = Socialite::driver('google')->user();
            

            $user = User::firstOrCreate(
                ['email' => $user_google->getEmail()],
                [
                    'name' => $user_google->getName(),
                    'avatar' => $user_google->getAvatar(), 
                    'google_id' => $user_google->getId(),
                ]
            );
    
            Auth::login($user, true);
    
            return redirect()->route('raffles.index');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Error al iniciar sesión con Google');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    } 

}