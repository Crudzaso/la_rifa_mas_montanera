<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
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
        $mpAccessToken = env('MERCADO_PAGO_ACCESS_TOKEN');
        MercadoPagoConfig::setAccessToken($mpAccessToken);
    }

    public function createPaymentPreference($items, $payer)
    {
        // Configurar los métodos de pago, por ejemplo, se pueden dejar habilitados todos los métodos disponibles
        $paymentMethods = [
            "excluded_payment_methods" => [],
            "installments" => 12,  // O puedes calcular las cuotas de forma dinámica
            "default_installments" => 1,
        ];

        // URL de retorno dinámicas
        $backUrls = [
            'success' => route('mercadopago.success'),
            'failure' => route('mercadopago.failure'),
        ];

        // Construir la solicitud para MercadoPago
        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => "NOMBRE_EN_FACTURA", // Puedes cambiar esto según tu necesidad
            "external_reference" => uniqid(), // Un identificador único para la referencia externa
            "expires" => false,
            "auto_return" => 'approved',
        ];

        $client = new PreferenceClient();

        try {
            // Crear la preferencia de pago
            $preference = $client->create($request);
            return $preference;
        } catch (MPApiException $e) {
            Log::error('Mercado Pago error:', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);

            event(new ErrorOccurred('Error al crear la preferencia de pago con Mercado Pago', $e->getMessage()));
            return null;
        }
    }
}
