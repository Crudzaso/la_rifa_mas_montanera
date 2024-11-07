<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailHelper
{
    public static function sendEmail($user)
    {
        $apiToken = env('MAIL_PASSWORD');
        $fromEmail = env('MAIL_FROM_ADDRESS');
        $fromName = env('MAIL_FROM_NAME');

        $response = Http::withToken($apiToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.mailersend.com/v1/email', [
                'from' => [
                    'email' => $fromEmail,
                    'name' => $fromName,
                ],
                'to' => [
                    [
                        'email' => $user->email,
                        'name' => $user->names,
                    ],
                ],
                'subject' => 'Confirmación de Registro - La Rifa Más Montañera',
                'text' => 'Nos complace informarle que su registro ha sido completado exitosamente en La Rifa Más Montañera.',
                'html' => '
                    <div style="background-color: #f7f7f7; padding: 30px; font-family: Arial, sans-serif;">
                        <div style="background-color: #ffffff; padding: 40px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <h2 style="color: #2d6a4f; text-align: center; font-size: 24px;">Estimado/a ' . $user->names . ',</h2>
                            <p style="color: #333333; font-size: 16px; line-height: 1.5; text-align: center;">
                                Nos complace informarle que su registro ha sido completado exitosamente en <strong>La Rifa Más Montañera</strong>. Agradecemos su interés y estamos seguros de que disfrutará de nuestros servicios exclusivos.
                            </p>
                            <p style="color: #333333; font-size: 16px; line-height: 1.5; text-align: center;">
                                Si tiene alguna pregunta o necesita asistencia, no dude en ponerse en contacto con nosotros.
                            </p>
                            <div style="text-align: center; margin-top: 30px;">
                                <p style="color: #555555; font-size: 14px; text-align: center;">Este es un mensaje automático. Por favor, no responda a este correo electrónico.</p>
                                <p style="color: #555555; font-size: 14px; text-align: center;">Atentamente,<br><strong>El equipo de La Rifa Más Montañera</strong></p>
                            </div>
                            <div style="border-top: 2px solid #e2e8f0; margin-top: 20px; padding-top: 20px; text-align: center;">
                                <a href="#" style="background-color: #2d6a4f; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Visite nuestro sitio web</a>
                            </div>
                        </div>
                    </div>
                ',
            ]);

        // Maneja errores
        if (!$response->successful()) {
            Log::error('Error al enviar correo:', $response->json());
        }
    }
}
