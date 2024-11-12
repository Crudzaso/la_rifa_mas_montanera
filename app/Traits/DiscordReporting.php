<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait DiscordReporting
{
    protected function handleOperationWithDiscord(callable $operation, string $action, array $context = [])
    {
        try {
            $result = $operation();

            if ($result === true) {
                return redirect()->route('usuarios.index')
                    ->with('success', 'Operación realizada con éxito.');
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error capturado:', [
                'error' => $e->getMessage(),
                'action' => $action,
                'context' => $context
            ]);

            $this->sendErrorToDiscord($e, $action, $context);

            return redirect()->route('usuarios.index')
                ->with('error', $this->getErrorMessage($action));
        }
    }

    protected function sendErrorToDiscord(\Exception $e, string $action, array $context)
    {
        $webhook_url = config('services.discord.webhook_url');
        $user = Auth::user();

        // Obtener información más específica del error
        $errorDetails = $this->getErrorDetails($action, $context);

        $message = [
            'content' => "🚨 La rifa mas montanera",
            'embeds' => [[
                'title' => $errorDetails['description'],
                'description' =>"Error", $e->getMessage(),
                'fields' => [
                    [
                        'name' => '⚡ Acción Realizada',
                        'value' => $errorDetails['action_description'],
                        'inline' => true
                    ],
                    [
                        'name' => '⏰ Fecha y Hora',
                        'value' => now()->format('Y-m-d H:i:s'),
                        'inline' => true
                    ],
                    [
                        'name' => '👤 Usuario que Ejecutó',
                        'value' => $user ? sprintf(
                            "Email: %s\nID: %s",
                            $user->email,
                            $user->id
                        ) : 'Usuario no autenticado',
                        'inline' => false
                    ],
                    [
                        'name' => '📍 Ubicación',
                        'value' => sprintf(
                            "Archivo: `%s`\nLínea: `%d`",
                            basename($e->getFile()),
                            $e->getLine()
                        ),
                        'inline' => false
                    ],
                    [
                        'name' => '🔍 Detalles de la Operación',
                        'value' => $this->formatContextData($context),
                        'inline' => false
                    ]
                ],
                'color' => 15158332,
                'timestamp' => now()->toIso8601String()
            ]]
        ];

        try {
            $response = Http::post($webhook_url, $message);
            Log::info('Respuesta de Discord', [
                'status' => $response->status()
            ]);
        } catch (\Exception $discordError) {
            Log::error('Error al enviar a Discord:', [
                'error' => $discordError->getMessage()
            ]);
        }
    }

    protected function getErrorDetails(string $action, array $context): array
    {
        return match($action) {
            'index' => [
                'title' => 'Error en Listado de Usuarios',
                'description' => 'Error al intentar cargar la lista de usuarios',
                'action_description' => 'Consulta de listado de usuarios con paginación'
            ],
            'show' => [
                'title' => 'Error al Mostrar Usuario',
                'description' => "Error al intentar mostrar el usuario ID: {$context['user_id']}",
                'action_description' => 'Consulta de detalles de usuario específico'
            ],
            'store' => [
                'title' => 'Error en Creación de Usuario',
                'description' => 'Error al intentar crear nuevo usuario',
                'action_description' => 'Creación de nuevo registro de usuario'
            ],
            'update' => [
                'title' => 'Error en Actualización de Usuario',
                'description' => "Error al actualizar usuario ID: {$context['user_id']}",
                'action_description' => 'Actualización de datos de usuario existente'
            ],
            'delete' => [
                'title' => 'Error en Eliminación de Usuario',
                'description' => "Error al eliminar usuario ID: {$context['user_id']}",
                'action_description' => 'Eliminación lógica de usuario'
            ],
            'restore' => [
                'title' => 'Error en Restauración de Usuario',
                'description' => "Error al restaurar usuario ID: {$context['user_id']}",
                'action_description' => 'Restauración de usuario eliminado'
            ],
            default => [
                'title' => 'Error en el Sistema',
                'description' => 'Error al procesar la operación solicitada',
                'action_description' => 'Operación no especificada'
            ]
        };
    }

    protected function formatContextData(array $context): string
    {
        $formattedData = [];

        if (isset($context['user_id'])) {
            $formattedData[] = "📌 ID de Usuario: " . $context['user_id'];
        }

        if (isset($context['request_data'])) {
            $formattedData[] = "📝 Datos de la Solicitud:";
            foreach ($context['request_data'] as $key => $value) {
                $formattedData[] = "   - $key: $value";
            }
        }

        if (isset($context['url'])) {
            $formattedData[] = "🔗 URL: " . $context['url'];
        }

        if (isset($context['method'])) {
            $formattedData[] = "📡 Método: " . $context['method'];
        }

        if (empty($formattedData)) {
            return "No hay datos de contexto disponibles";
        }

        return "```" . implode("\n", $formattedData) . "```";
    }

    protected function getErrorMessage(string $action): string
    {
        return match($action) {
            'index' => 'Error al cargar los usuarios.',
            'show' => 'Usuario no encontrado.',
            'store' => 'Error al crear el usuario.',
            'update' => 'Error al actualizar el usuario.',
            'delete' => 'Error al eliminar el usuario.',
            'restore' => 'Error al restaurar el usuario.',
            'trashed' => 'Error al cargar usuarios eliminados.',
            default => 'Error al realizar la operación.'
        };
    }
}
