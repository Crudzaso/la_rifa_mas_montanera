<?php

namespace App\Http\Controllers;

use App\Services\MercadoPagoService;
use Illuminate\Http\Request;

class MercadoPagoController extends Controller
{
    protected $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    // Mostrar el formulario de pago
    public function showPaymentForm()
    {
        return view('mercadopago.payment');
    }

    // Crear la preferencia de pago
    public function createPayment(Request $request)
    {
        $items = [
            [
                "id" => "1234567890",
                "title" => "Producto 1",
                "description" => "Descripción del producto 1",
                "currency_id" => "COP",
                "quantity" => 1,
                "unit_price" => 100.00
            ]
        ];

        $payer = [
            "name" => $request->input('name'),
            "surname" => $request->input('surname'),
            "email" => $request->input('email'),
        ];

        $preference = $this->mercadoPagoService->createPaymentPreference($items, $payer);

        if ($preference) {
            return redirect($preference->init_point); // Redirige a MercadoPago para el pago
        } else {
            return redirect()->route('mercadopago.failure');
        }
    }

    // Página de éxito del pago
    public function success()
    {
        return view('mercadopago.success');
    }

    // Página de fallo en el pago
    public function failure()
    {
        return view('mercadopago.failure');
    }
}
