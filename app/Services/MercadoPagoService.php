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
        $mpAccessToken = env('MERCADO_PAGO_ACCESS_TOKEN');
        Log::info('Autenticación con Mercado Pago iniciada.', ['access_token' => $mpAccessToken]);
        MercadoPagoConfig::setAccessToken($mpAccessToken);
    }

    public function createPaymentPreference($items, $payer)
    {
        $paymentMethods = [
            "excluded_payment_methods" => [],
            "installments" => 12,
            "default_installments" => 1,
        ];

        $backUrls = [
            'success' => route('mercadopago.success'),
            'failure' => route('mercadopago.failure'),
        ];

        Log::info('Creando preferencia de pago...', ['items' => $items, 'payer' => $payer]);

        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => "NOMBRE_EN_FACTURA",
            "external_reference" => "1234567890",
            "expires" => false,
            "auto_return" => 'approved',
        ];

        Log::info('Datos de la solicitud de preferencia:', ['request' => $request]);

        $client = new PreferenceClient();

        try {
            $preference = $client->create($request);

            Log::info('Preferencia de pago creada con éxito:', ['response' => $preference]);

            return $preference;

        } catch (MPApiException $e) {
            Log::error('Error al crear preferencia de pago en Mercado Pago:', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'response' => $e->getApiResponse(),
                'trace' => $e->getTraceAsString(),
            ]);
            event(new ErrorOccurred('Error al crear la preferencia de pago con Mercado Pago', $e->getMessage()));
            return ['error' => 'Error al crear la preferencia de pago.' , $e->getMessage()];
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
            return ['error' => 'Error al obtener el estado del pago.', $e->getMessage()];
        }
    }
}
