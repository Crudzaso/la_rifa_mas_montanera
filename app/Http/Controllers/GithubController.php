<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GithubUser;
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

    public function login()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $user_github = Socialite::driver('github')->user();
            $user = User::where('email', $user_github->email)->first();

            if ($user) {
                Auth::login($user);
                event(new UserLogin($user));

                $this->emailHelper::sendLoginNotification($user);

            } else {
                $user = User::create([
                    'names' => $user_github->name,
                    'email' => $user_github->email,
                ]);

                GithubUser::create([
                    'email' => $user_github->email,
                    'name' => $user_github->name,
                    'user_id' => $user->id,
                ]);

                Auth::login($user);
                
                $this->emailHelper::sendWelcomeEmail($user);
                event(new UserCreated($user));
            }
            return redirect()->route('usuarios.layouts')->with('success', 'Has iniciado sesión correctamente ');

        } catch (\Exception $e) {
            \Log::error('Github login error:', ['message' => $e->getMessage()]);
            return redirect()->route('auth.github')->with('error', 'Error al iniciar sesión con Github.');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }
}
