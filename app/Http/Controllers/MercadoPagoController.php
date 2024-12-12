<?php

namespace App\Http\Controllers;

use App\Services\MercadoPagoService;
use App\Models\Raffle;
use App\Models\TicketPurchase;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MercadoPagoController extends Controller
{
    protected $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
        Log::info('MercadoPagoController iniciado.');
    }

    /**
     * Crear preferencia de pago y devolver URL de redirección.
     */
    public function createPayment(Request $request)
    {

        Log::info('Autenticación con Mercado Pago iniciada.', ['access_token' => config('services.mercadopago.access_token')]);
        Log::info('MercadoPagoController iniciado.');


        Log::info('Creando pago...', ['user_id' => Auth::id()]);

        $user = Auth::user();

        $request->merge([
            'ticket_numbers' => array_map('intval', $request->input('ticket_numbers', []))
        ]);

        $validated = $request->validate([
            'raffle_id' => 'required|exists:raffles,id',
            'ticket_numbers' => 'required',
        ]);

        $raffle = Raffle::find($validated['raffle_id']);

        if (!$raffle) {
            Log::error('Rifa no válida:', ['raffle_id' => $validated['raffle_id']]);
            return response()->json([
                'error' => 'La rifa seleccionada no es válida.'
            ], 400);
        }

        $items = [
            [
                "id" => $raffle->id,
                "title" => $raffle->title,
                "description" => $raffle->description,
                "currency_id" => "COP",
                "quantity" => count($validated['ticket_numbers']),
                "unit_price" => $raffle->price_tickets,
            ]
        ];

        $payer = [
            "name" => $user->name,
            "email" => $user->email,
        ];

        Log::info('Enviando solicitud de preferencia de pago...', ['items' => $items, 'payer' => $payer]);

        try {

            Log::info('creando prefencia');

            $preference = $this->mercadoPagoService->createPaymentPreference($items, $payer);

            if (isset($preference['error'])) {
                Log::error('Error al crear preferencia de pago:', ['preference' => $preference]);
                return response()->json([
                    'error' => $preference['error']
                ], 500);
            }

            if ($preference && isset($preference->init_point)) {
                Log::info('Preferencia de pago creada correctamente:', ['redirect_url' => $preference->init_point]);
                return response()->json([
                    'redirect_url' => $preference->init_point
                ], 200);
            } else {
                Log::error('No se pudo crear la preferencia de pago.', ['preference' => $preference]);
                return response()->json([
                    'error' => 'No se pudo crear la preferencia de pago.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error al crear la preferencia de pago:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => 'Hubo un error al procesar la solicitud de pago.'
            ], 500);
        }
    }

    /**
     * Acción cuando el pago es exitoso.
     */
    public function success(Request $request)
    {
        Log::info('Pago exitoso recibido:', ['payment_id' => $request->get('payment_id')]);

        $paymentId = $request->get('payment_id');

        $payment = $this->mercadoPagoService->getPaymentStatus($paymentId);

        if (isset($payment['error'])) {
            Log::error('Error al obtener el estado del pago:', ['payment' => $payment]);
            return response()->json([
                'error' => $payment['error']
            ], 400);
        }

        if ($payment && $payment->status == 'approved') {
            Log::info('Pago aprobado, creando boletos...', ['payment' => $payment]);

            $raffle = Raffle::find($payment->external_reference);

            if ($raffle) {
                TicketPurchase::create([
                    'user_id' => Auth::id(),
                    'raffle_id' => $raffle->id,
                    'quantity' => count($request->get('ticket_numbers')),
                ]);

                Log::info('Boletos creados correctamente para el usuario.', ['user_id' => Auth::id()]);
                return response()->json([
                    'message' => 'Pago realizado con éxito y boletos creados.'
                ], 200);
            }

            Log::error('Rifa no encontrada para el pago.', ['raffle_id' => $payment->external_reference]);
            return response()->json([
                'error' => 'Rifa no encontrada.'
            ], 400);
        }

        return response()->json([
            'error' => 'El pago ha fallado.'
        ], 400);
    }

    /**
     * Acción cuando el pago falla.
     */
    public function failure()
    {
        Log::error('El pago ha fallado.');
        return response()->json([
            'error' => 'El pago ha fallado.'
        ], 400);
    }
}
