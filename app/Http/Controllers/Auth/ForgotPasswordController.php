<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Helpers\EmailHelperGlobal;
use App\Service\DiscordWebhookService;

class ForgotPasswordController extends Controller
{
    protected $emailHelper;
    protected $discordWebhookService;

    public function __construct(EmailHelperGlobal $emailHelper, DiscordWebhookService $discordWebhookService)
    {
        $this->emailHelper = $emailHelper;
        $this->discordWebhookService = $discordWebhookService;
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email|exists:users,email']);

            $user = User::where('email', $request->email)->first();
            
            $token = Password::createToken($user);
            $resetLink = route('password.reset', ['token' => $token, 'email' => $user->email]);

            $this->emailHelper::sendPasswordResetEmail($user, $resetLink);

            app(EmailController::class)->sendPasswordResetEmail($user, $resetLink);

            return back()->with('status', 'Se ha enviado un enlace de restablecimiento a tu correo.');
        } catch (\Exception $e) {
            Log::error('Error al enviar el enlace de restablecimiento: ' . $e->getMessage(), [
                'email' => $request->input('email'),
                'stack' => $e->getTraceAsString(),
            ]);

            $this->discordWebhookService->sendErrorToDiscord("Error al enviar el enlace de restablecimiento: " . $e->getMessage());
            return back()->withErrors(['email' => 'Ocurrió un error al intentar enviar el enlace de restablecimiento.']);
        }
    }
}
