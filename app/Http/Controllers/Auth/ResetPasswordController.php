<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Services\DiscordWebhookService;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{
    protected $discordWebhookService;

    public function __construct(DiscordWebhookService $discordWebhookService)
    {
        $this->discordWebhookService = $discordWebhookService;
    }

    public function showResetForm(Request $request, $token = null)
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    public function reset(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|confirmed|min:8',
                'token' => 'required'
            ]);

            $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            });

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida. Ahora puedes iniciar sesión.');
            }

            return back()->withErrors(['email' => trans($status)]);
        } catch (\Exception $e) {
            $this->discordWebhookService->sendErrorToDiscord("Error al restablecer la contraseña: " . $e->getMessage());
            return back()->withErrors(['email' => 'Ocurrió un error al restablecer la contraseña.']);
        }
    }
}
