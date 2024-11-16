<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Helpers\EmailHelperGlobal;

class ForgotPasswordController extends Controller
{
    protected $emailHelper;

    public function __construct(EmailHelperGlobal $emailHelper)
    {
        $this->emailHelper = $emailHelper;
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validar que el email sea requerido y esté en el formato correcto
        $request->validate(['email' => 'required|email|exists:users,email']);

        try {
            // Obtener el usuario
            $user = User::where('email', $request->email)->first();
            
            // Crear token y enlace de restablecimiento
            $token = Password::createToken($user);
            $resetLink = route('password.reset', ['token' => $token, 'email' => $user->email]);

            // Enviar el correo de restablecimiento utilizando el helper
            $this->emailHelper::sendPasswordResetEmail($user, $resetLink);

            return back()->with('status', 'Se ha enviado un enlace de restablecimiento a tu correo.');
        } catch (\Exception $e) {
            Log::error('Error al enviar el enlace de restablecimiento: ' . $e->getMessage(), [
                'email' => $request->input('email'),
                'stack' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['email' => 'Ocurrió un error al intentar enviar el enlace de restablecimiento.']);
        }
    }
}
