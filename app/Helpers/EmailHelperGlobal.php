<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordResetEmail;
use App\Mail\RegistroExitoso; 

class EmailHelperGlobal
{
    // Configuración predeterminada desde .env
    protected static function getEmailConfig()
    {
        return [
            'fromEmail' => env('MAIL_FROM_ADDRESS'),
            'fromName' => env('MAIL_FROM_NAME'),
            'apiToken' => env('MAIL_PASSWORD'),
        ];
    }

    protected static function sendEmailRequest($toEmail, $toName, $subject, $htmlContent)
    {
        $config = self::getEmailConfig();

        $response = Http::withToken($config['apiToken'])
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post('https://api.mailersend.com/v1/email', [
                'from' => [
                    'email' => $config['fromEmail'],
                    'name' => $config['fromName'],
                ],
                'to' => [[
                    'email' => $toEmail,
                    'name' => $toName,
                ]],
                'subject' => $subject,
                'html' => $htmlContent,
            ]);

        if (!$response->successful()) {
            Log::error('Error al enviar correo:', [
                'response' => $response->json(),
                'status' => $response->status(),
                'request_data' => [
                    'to' => $toEmail,
                    'subject' => $subject,
                ],
            ]);
        }
    }

    public static function generateMessage($userName, $messageContent, $footer = null)
    {
        return "
            <div style='background-color: #f4f7fa; padding: 30px; text-align: center; font-family: Arial, sans-serif;'>
                <div style='background-color: #ffffff; padding: 40px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                    <h2 style='color: #2d6a4f; text-align: center; font-size: 24px;'>Estimado/a {$userName},</h2>
                    <p style='color: #333333; font-size: 16px; line-height: 1.5; text-align: center;'>
                        {$messageContent}
                    </p>
                    " . ($footer ? "<p style='color: #555555; font-size: 14px; text-align: center;'>$footer</p>" : "") . "
                    <p style='color: #555555; font-size: 14px; text-align: center;'>Atentamente,<br><strong>El equipo de La Rifa Más Montañera</strong></p>
                </div>
            </div>
        ";
    }

    // Mensaje de registro
    public static function sendWelcomeEmail($user)
    {
        $subject = 'Confirmación de Registro - La Rifa Más Montañera';

        $messageContent = 'Nos complace informarle que su registro en <strong>La Rifa Más Montañera</strong> ha sido exitoso. Agradecemos su interés y estamos seguros de que disfrutará de nuestros servicios exclusivos.';

        $footer = 'Este es un mensaje automático. Por favor, no responda a este correo electrónico.';

        $htmlContent = self::generateMessage($user->names, $messageContent, $footer);

        self::sendEmailRequest($user->email, $user->names, $subject, $htmlContent);
    }

    // Mensaje de inicio de sesión
    public static function sendLoginNotification($user)
    {
        $subject = 'Notificación de Inicio de Sesión - La Rifa Más Montañera';

        $messageContent = "Se ha registrado un inicio de sesión en su cuenta. Si no ha sido usted, le recomendamos cambiar su contraseña de inmediato para garantizar la seguridad de su cuenta.";

        $footer = 'Si no reconoce este inicio de sesión, por favor revise su actividad y tome las acciones correspondientes.';

        $htmlContent = self::generateMessage($user->names, $messageContent, $footer);

        self::sendEmailRequest($user->email, $user->names, $subject, $htmlContent);
    }

    // Mensaje de restablecimiento de contraseña
    public static function sendPasswordResetEmail($user, $resetLink)
    {
        $subject = 'Solicitud de Restablecimiento de Contraseña - La Rifa Más Montañera';
        
        $messageContent = 'Hemos recibido una solicitud para restablecer la contraseña de su cuenta en <strong>La Rifa Más Montañera</strong>. Si no ha realizado esta solicitud, le recomendamos que ignore este correo.';

        $buttonHtml = "<div style='margin-top: 20px;'><a href='{$resetLink}' style='display: inline-block; padding: 12px 24px; background-color: #2d6a4f; color: #ffffff; border-radius: 4px; text-decoration: none;'>Restablecer Contraseña</a></div>";

        $footer = 'Este enlace para restablecer la contraseña expirará en 60 minutos. Si no solicitó esta acción, por favor ignore este mensaje.';

        $htmlContent = self::generateMessage($user->names, $messageContent . $buttonHtml, $footer);

        self::sendEmailRequest($user->email, $user->names, $subject, $htmlContent);
    }
}
