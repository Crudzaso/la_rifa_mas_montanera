<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordResetEmail;
use App\Mail\RegistroExitoso;

class EmailHelperGlobal
{
    protected static function getEmailConfig()
    {
        return [
            'fromEmail' => env('MAIL_FROM_ADDRESS'),
            'fromName' => env('MAIL_FROM_NAME'),
        ];
    }

    protected static function sendEmailRequest($toEmail, $toName, $subject, $htmlContent)
    {
        try {
            $data = [
                'subject' => $subject,
                'htmlContent' => $htmlContent,
                'toEmail' => $toEmail,
                'toName' => $toName,
            ];

            Mail::send([], [], function ($message) use ($data) {
                $message->to($data['toEmail'], $data['toName'])
                        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                        ->subject($data['subject'])
                        ->html($data['htmlContent']);
            });

        } catch (\Exception $e) {
            Log::error('Error al enviar el correo:', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    // Generación del contenido del mensaje HTML
    public static function generateMessage($userName, $messageContent, $footer = null)
{
    $imageUrl = 'https://i.postimg.cc/DwX5NCg5/La-Rifa-Monta-era-Photoroom.png';

    return "
        <div style='background-color: #f9fafc; padding: 20px; text-align: center;'>
            <div style='background-color: #ffffff; padding: 30px; border-radius: 8px; display: inline-block; text-align: left;'>
                <img src='{$imageUrl}' alt='Logo' style='width: 100%; max-width: 600px; border-radius: 8px;'>
                <h2 style='color: #333333;'>¡Hola, {$userName}!</h2>
                <p style='color: #555555;'>
                    {$messageContent}
                </p>
                " . ($footer ? "<p style='color: #555555;'>$footer</p>" : "") . "
                <p style='color: #555555; margin-top: 20px;'>Saludos,<br><strong>La Rifa Mas Montañera</strong></p>
            </div>
        </div>
    ";
}

    // Enviar correo de bienvenida
    public static function sendWelcomeEmail($user)
    {
        $subject = '¡Bienvenido a La Rifa Mas Montañera!';

        $messageContent = 'Gracias por registrarte en <strong>La Rifa Mas Montañera</strong>. Estamos emocionados de tenerte con nosotros y esperamos que disfrutes de todos nuestros servicios.';

        $footer = 'Este es un mensaje automático, por favor no respondas.';

        $htmlContent = self::generateMessage($user->name, $messageContent, $footer);

        self::sendEmailRequest($user->email, $user->name, $subject, $htmlContent);
    }

    // Enviar notificación de inicio de sesión
    public static function sendLoginNotification($user)
    {
        $subject = 'Notificación de Inicio de Sesión - La Rifa Mas Montañera';

        $messageContent = "Has ingresado en nuestra página, <strong>Bienvenido</strong>. Si no fuiste tú, por favor cambia tu contraseña inmediatamente.";

        $footer = 'Si no reconoces este inicio de sesión, considera revisar tu actividad reciente.';

        $htmlContent = self::generateMessage($user->name, $messageContent, $footer);

        self::sendEmailRequest($user->email, $user->name, $subject, $htmlContent);
    }

    // Enviar correo de restablecimiento de contraseña
    public static function sendPasswordResetEmail($user, $resetLink)
    {
        $subject = 'Restablecimiento de Contraseña - La Rifa Mas Montañera';

        $messageContent = 'Has recibido este mensaje porque se solicitó un restablecimiento de contraseña para tu cuenta en La Rifa Mas Montañera.';

        // Botón para restablecer la contraseña
        $buttonHtml = "<div style='margin-top: 20px;'><a href='{$resetLink}' style='display: inline-block; padding: 12px 24px; background-color: #2d3748; color: #ffffff; border-radius: 4px; text-decoration: none;'>Restablecer contraseña</a></div>";

        $footer = 'Este enlace de restablecimiento de contraseña expirará en 60 minutos. Si no has solicitado esta acción, simplemente ignora este mensaje.';

        $htmlContent = self::generateMessage($user->name, $messageContent . $buttonHtml, $footer);

        self::sendEmailRequest($user->email, $user->name, $subject, $htmlContent);
    }

    public static function sendPaymentConfirmationEmail($user, $paymentDetails)
    {
        $subject = 'Confirmación de Pago - La Rifa Mas Montañera';

        $messageContent = "
            Gracias por realizar tu compra en <strong>La Rifa Mas Montañera</strong>.
            Hemos recibido tu pago exitosamente. Aquí están los detalles de tu transacción:<br><br>
            <ul>
                <li><strong>ID de Transacción:</strong> {$paymentDetails['transaction_id']}</li>
                <li><strong>Producto:</strong> {$paymentDetails['product_name']}</li>
                <li><strong>Monto Pagado:</strong> {$paymentDetails['amount']} {$paymentDetails['currency']}</li>
            </ul>
            <br>Si tienes alguna duda, no dudes en contactarnos.
        ";

        $footer = 'Este es un mensaje automático, por favor no respondas.';

        $htmlContent = self::generateMessage($user->name, $messageContent, $footer);

        self::sendEmailRequest($user->email, $user->name, $subject, $htmlContent);
    }
    public static function sendWinnerEmail($user, $raffle, $prize)
    {
        $subject = '🎉 ¡Felicidades, eres nuestro ganador! - La Rifa Mas Montañera';

        $messageContent = "
            ¡Hola, <strong>{$user->name}</strong>!<br><br>
            Nos complace anunciarte que has sido el ganador de nuestra rifa <strong>{$raffle->name}</strong>.<br><br>
            Tu premio es: <strong>{$prize}</strong> 🎁<br><br>
            Estamos muy emocionados por ti y nos pondremos en contacto contigo pronto para coordinar la entrega de tu premio.<br><br>
            Gracias por participar y por ser parte de <strong>La Rifa Mas Montañera</strong>. ¡Esperamos verte en futuras rifas!
        ";

        $footer = 'Este es un mensaje automático, por favor no respondas.';

        $htmlContent = self::generateMessage($user->name, $messageContent, $footer);

        self::sendEmailRequest($user->email, $user->name, $subject, $htmlContent);
    }
    
    public static function sendTicketPurchaseEmail($user, $raffle, $ticketNumbers)
    {
        $subject = '¡Gracias por tu compra en La Rifa Mas Montañera!';
    
        $messageContent = "
            ¡Hola, <strong>{$user->name}</strong>!<br><br>
            Gracias por participar en nuestra rifa <strong>{$raffle->name}</strong>. Tu compra ha sido confirmada con éxito.<br><br>
            Aquí están los números de tus boletos:<br>
            <ul>";
    
        foreach ($ticketNumbers as $number) {
            $messageContent .= "<li><strong>{$number}</strong></li>";
        }
    
        $messageContent .= "</ul><br>
            Te deseamos mucha suerte y esperamos que seas nuestro próximo ganador.<br><br>
            Para más información sobre esta rifa, visita nuestra página web.<br><br>
            ";
    
        $footer = 'Este es un mensaje automático, por favor no respondas.';
    
        $htmlContent = self::generateMessage($user->name, $messageContent, $footer);
    
        self::sendEmailRequest($user->email, $user->name, $subject, $htmlContent);
    }
}
