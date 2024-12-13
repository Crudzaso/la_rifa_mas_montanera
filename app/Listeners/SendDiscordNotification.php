<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Events\UserDeleted;
use App\Events\UserRestore;
use App\Events\UserLogin;
use App\Events\ErrorOccurred;
use App\Events\UserWinner;
use App\Events\BuyTicket;
use App\Services\DiscordWebhookService;

class SendDiscordNotification
{
    protected $discordWebhook;

    private const COLOR_CREATED = 0x36ff00;
    private const COLOR_UPDATED = 0xfff700;
    private const COLOR_DELETED = 0xff2d00;
    private const COLOR_RESTORED = 0xde5e00;
    private const COLOR_WINNER = 0x00c9ff;
    private const COLOR_TICKET = 0x8e44ad;
    private const ERROR_COLOR = 0xff0000;

    public function __construct(DiscordWebhookService $discordWeebhookService)
    {
        $this->discordWebhook = $discordWeebhookService;
    }

    public function handleUserCreated(UserCreated $event): void
    {
        $this->sendNotification($event->user, 'creado', auth()->user(), self::COLOR_CREATED);
    }

    public function handleUserUpdated(UserUpdated $event): void
    {
        $this->sendNotification($event->user, 'actualizado', auth()->user(), self::COLOR_UPDATED);
    }

    public function handleUserDeleted(UserDeleted $event): void
    {
        $this->sendNotification($event->user, 'eliminado', auth()->user(), self::COLOR_DELETED);
    }

    public function handleUserRestore(UserRestore $event): void
    {
        $this->sendNotification($event->user, 'restaurado', auth()->user(), self::COLOR_RESTORED);
    }

    public function handleUserLogin(UserLogin $event): void
    {
        $this->sendNotification($event->user, 'ingreso', auth()->user(), self::COLOR_CREATED);
    }

    public function handleUserWinner(UserWinner $event): void
    {
        $user = $event->user;
        $embed = [
            'title' => "✨ Usuario ganador de la rifa ✨",
            'color' => self::COLOR_WINNER,
            'thumbnail' => [
                'url' => "https://i.postimg.cc/DwX5NCg5/La-Rifa-Monta-era-Photoroom.png",
            ],
            'fields' => [
                [
                    'name' => '⚪ Nombre Completo',
                    'value' => $user->name,
                    'inline' => true,
                ],
                [
                    'name' => '✉ Correo Electrónico',
                    'value' => $user->email,
                    'inline' => false,
                ],
            ],
            'footer' => [
                'text' => 'Notificación enviada desde La rifa mas montañera',
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        $this->discordWebhook->sendEmbed($embed);
    }

    public function handleBuyTicket(BuyTicket $event): void
    {
        $user = $event->user;
        $raffle = $event->raffle;
        $tickets = $event->tickets;
        $totalPrice = $event->totalPrice;
        $actor = auth()->user();  

        $action = 'compro boletos';
        $this->sendNotificationRaffle($user, $raffle, $action, $actor, self::COLOR_TICKET, $tickets, $totalPrice);
    }




    protected function sendNotification($user, $action, $actor, $color): void
    {
        try {
            $embed = [
                'title' => "🎉 La rifa mas montañera - Usuario {$action} 🎉",
                'color' => $color,
                'thumbnail' => [
                    'url' => "https://i.postimg.cc/DwX5NCg5/La-Rifa-Monta-era-Photoroom.png",
                ],
                'fields' => [
                    [
                        'name' => '💼 Id de user',
                        'value' => "{$user->id}",
                        'inline' => true,
                    ],
                    [
                        'name' => '👤 Nombre Completo',
                        'value' => "{$user->name}",
                        'inline' => true,
                    ],
                    [
                        'name' => '📧 Correo Electrónico',
                        'value' => $user->email,
                        'inline' => false,
                    ],
                    [
                        'name' => '🏠 Dirección',
                        'value' => $user->address ?? 'No proporcionado',
                        'inline' => false,
                    ],
                    [
                        'name' => '🛠️ Realizado por',
                        'value' => "{$actor->name} con el ID {$actor->id}",
                        'inline' => false,
                    ],
                ],
                'footer' => [
                    'text' => implode(" | ", [
                        '📍 Realizado en La rifa mas montañera',
                        '🕒 Notificación realizada el ' . now()->format('d/m/y H:i')
                    ]),
                ],
                'timestamp' => now()->toIso8601String(),
                'author' => [
                    'name' => "👤 {$actor->name}",
                ],
            ];

            $this->discordWebhook->sendEmbed($embed);
        } catch (\Exception $e) {
            Log::error("Error al enviar notificación de Discord: " . $e->getMessage());
        }
    }

    protected function sendNotificationRaffle($user, $raffle, $action, $actor, $color, $tickets, $totalPrice): void
    {
        try {
            $embed = [
                'title' => "🎟️ ¡Compra de boletos realizada exitosamente! 🎟️",
                'color' => self::COLOR_TICKET,
                'thumbnail' => [
                    'url' => "https://i.postimg.cc/DwX5NCg5/La-Rifa-Monta-era-Photoroom.png",
                ],
                'fields' => [
                    [
                        'name' => '🛡️ Rifa',
                        'value' => $raffle->title, 
                        'inline' => false,
                    ],
                    [
                        'name' => '⚪ Nombre',
                        'value' => $user->name,
                        'inline' => true,
                    ],
                    [
                        'name' => '✉ Correo Electrónico',
                        'value' => $user->email,
                        'inline' => true,
                    ],
                    [
                        'name' => '🎫 Boletos Comprados',
                        'value' => implode(', ', $tickets),
                        'inline' => false,
                    ],
                    [
                        'name' => '💰 Precio Total',
                        'value' => "$" . number_format($totalPrice, 2) . " COP",
                        'inline' => false,
                    ],
                    [
                        'name' => '🛠️ Realizado por',
                        'value' => "{$actor->name} con el ID {$actor->id}",
                        'inline' => false,
                    ],
                ],
                'footer' => [
                    'text' => 'Notificación enviada desde La rifa más montañera',
                ],
                'timestamp' => now()->toIso8601String(),
                'author' => [
                    'name' => "👤 {$actor->name}",
                ],
            ];
    
            $this->discordWebhook->sendEmbed($embed);
        } catch (\Exception $e) {
            Log::error("Error al enviar notificación de Discord: " . $e->getMessage());
        }
    }
}
