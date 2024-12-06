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
                "unit_price" => 1000.00
            ]
        ];

        $payer = [
            "name" => $request->input('name'),
            "surname" => $request->input('surname'),
            "email" => $request->input('email'),
        ];

        $preference = $this->mercadoPagoService->createPaymentPreference($items, $payer);

        if (isset($preference->init_point)) {
            return redirect($preference->init_point);
        } else {
            return redirect()->route('mercadopago.payment')->with('error', 'No se pudo crear la preferencia de pago.');
        }
    }

    // Página de éxito del pago
    public function success()
    {
        return redirect()->route('mercadopago.payment');
    }

    // Página de fallo en el pago
    public function failure()
    {
        return redirect()->route('mercadopago.payment');
    }
}

