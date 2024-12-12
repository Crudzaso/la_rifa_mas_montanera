<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Client\Payment\PaymentClient;
use App\Events\ErrorOccurred;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    public function __construct()
    {
        $this->authenticate();
    }

    protected function authenticate()
    {
        $mpAccessToken = config('services.mercadopago.access_token');

        Log::info('Intentando autenticar con token:', [
            'token_exists' => !empty($mpAccessToken),
            'token_length' => strlen($mpAccessToken ?? '')
        ]);

        if (empty($mpAccessToken)) {
            Log::error('Token de MercadoPago no encontrado en la configuración');
            throw new \Exception('Token de MercadoPago no configurado');
        }

        try {
            MercadoPagoConfig::setAccessToken($mpAccessToken);
            Log::info('MercadoPago autenticado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al autenticar MercadoPago:', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function createPaymentPreference($items, $payer)
    {
        try {
            $client = new PreferenceClient();

            $preferenceData = [
                "items" => array_map(function($item) {
                    return [
                        "title" => $item['title'],
                        "quantity" => $item['quantity'],
                        "unit_price" => floatval($item['unit_price']),
                        "currency_id" => "COP"
                    ];
                }, $items),
                "payer" => $payer,
                "back_urls" => [
                    "success" => route('mercadopago.success'),
                    "failure" => route('mercadopago.failure'),
                    "pending" => route('mercadopago.failure')
                ],
                "auto_return" => "approved",
                "binary_mode" => true
            ];

            Log::info('Creando preferencia con datos:', $preferenceData);

            $preference = $client->create($preferenceData);
            Log::info('Preferencia creada:', ['preference_id' => $preference->id]);

            return $preference;
        } catch (MPApiException $e) {
            Log::error('Error MercadoPago:', [
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
                'response' => $e->getApiResponse()
            ]);
            throw $e;
        }
    }

    public function getPaymentStatus($paymentId)
    {
        Log::info('Obteniendo el estado del pago para el ID:', ['payment_id' => $paymentId]);

        $client = new PaymentClient();

        try {
            $payment = $client->get($paymentId);
            Log::info('Estado del pago recibido:', ['payment' => $payment]);
            return $payment;
        }
        catch (MPApiException $e) {
            Log::error('Error al obtener el estado del pago de Mercado Pago:', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);

            event(new ErrorOccurred('Error al obtener el estado del pago con Mercado Pago', $e->getMessage()));
          
            return ['error' => 'Error al obtener el estado del pagoo.', $e->getMessage()];
        }
    }
}
