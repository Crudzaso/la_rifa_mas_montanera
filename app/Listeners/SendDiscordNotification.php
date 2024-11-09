<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Events\UserDeleted;
use App\Events\UserRestore;
use App\Events\UserLogin;
use App\Service\DiscordWebhookService;

class SendDiscordNotification
{
    protected $discordWebhook;

    // Colores más suaves para cada tipo de acción
    private const COLOR_CREATED = 0x3cb371; 
    private const COLOR_UPDATED = 0xffb84d; 
    private const COLOR_DELETED = 0xff6f61;
    private const COLOR_RESTORED = 0xe27d60; 

    // URL del logo de tu proyecto
    private const PROJECT_LOGO_URL = 'https://media.istockphoto.com/id/1313644269/es/vector/plantilla-de-logotipo-de-estrella-de-c%C3%ADrculo-de-oro-y-plata.jpg?s=612x612&w=0&k=20&c=dNxY9Kn6HigtMX7DmKGlMES5FOseqxns0sFUosiyRuQ=';

    /**
     * Create the event listener.
     */
    public function __construct(DiscordWebhookService $discordWeebhookService)
    {
        $this->discordWebhook = $discordWeebhookService;
    }

    /**
     * Handle the event of create.
     */
    public function handleUserCreated(UserCreated $event): void
    {
        $this->sendNotification($event->user, 'ha sido creado 🎉', auth()->user(), self::COLOR_CREATED);
    }

    /**
     * Handle the event of update.
     */
    public function handleUserUpdated(UserUpdated $event): void
    {
        $this->sendNotification($event->user, 'ha sido actualizado ✏️ ', auth()->user(), self::COLOR_UPDATED);
    }

    /**
     * Handle the event of delete.
     */
    public function handleUserDeleted(UserDeleted $event): void
    {
        $this->sendNotification($event->user, 'ha sido eliminado ❌ ', auth()->user(), self::COLOR_DELETED);
    }

    /**
     * Handle the event of restore.
     */
    public function handleUserRestore(UserRestore $event): void
    {
        $this->sendNotification($event->user, 'ha sido restaurado 🔄', auth()->user(), self::COLOR_RESTORED);
    }

    /**
     * Handle the event of login.
     */
    public function handleUserLogin(UserLogin $event): void
    {
        $this->sendNotification($event->user, 'ha ingresado 👤', auth()->user(), self::COLOR_CREATED);
    }

    protected function sendNotification($user, $action, $actor, $color, )
    {
        try {
            $embed = [
                'title' => "La rifa más montañera ⛰️👨‍🌾 - Usuario{$action}",
                'color' => $color,
                'thumbnail' => [
                    'url' => self::PROJECT_LOGO_URL,
                ],
                'fields' => [
                    [
                        'name' => 'Id de usuario',
                        'value' => "{$user->id}",
                        'inline' => true,
                    ],
                    [
                        'name' => 'Nombre Completo',
                        'value' => "{$user->names} {$user->lastnames}",
                        'inline' => true,
                    ],
                    [
                        'name' => '  Correo Electrónico',
                        'value' => $user->email,
                        'inline' => false,
                    ],
                    [
                        'name' => 'Dirección',
                        'value' => $user->address ?? 'No proporcionado',
                        'inline' => false,
                    ],
                    [
                        'name' => 'Realizado por',
                        'value' => "{$actor->names} {$actor->lastnames} con el id {$actor->id}",
                        'inline' => false,
                    ],
                ],
                'footer' => [
                    'text' => implode(" | ", [
                        'Realizado en ⛰️🏆 La rifa más montañera 🏆👨‍🌾',
                        'Día 🗓️ :  ' . now()->format('d/m/y H:i')
                    ]),
                ],
            ];

            $this->discordWebhook->sendEmbed($embed);

        } catch (\Exception $e) {
            \Log::error("Error al enviar notificación a Discord: " . $e->getMessage());
        }
    }
}
