<?php

namespace App\Http\Controllers;


use App\Services\MercadoPagoService;
use App\Models\Raffle;
use Illuminate\Http\Request;

class MercadoPagoController extends Controller
{
    protected $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    public function showPaymentForm($raffleId)
    {
        $raffle = Raffle::findOrFail($raffleId);
        $remainingTickets = $raffle->remaining_tickets;

        return view('mercadopago.payment', compact('raffle', 'remainingTickets'));
    }

    public function createPayment(Request $request, $raffleId)
    {
        $raffle = Raffle::findOrFail($raffleId);
        $quantity = $request->input('quantity');
        $ticketPrice = $raffle->price_tickets;

        $items = [
            [
                "id" => "ticket_" . $raffle->id,
                "title" => $raffle->title,
                "description" => $raffle->description,
                "currency_id" => "COP",
                "quantity" => $quantity, 
                "unit_price" => $ticketPrice
            ]
        ];

        // Obtener los datos del pagador (usualmente desde el formulario)
        $payer = [
            "name" => $request->input('name'),
            "surname" => $request->input('surname'),
            "email" => $request->input('email'),
        ];

        // Crear la preferencia de pago
        $preference = $this->mercadoPagoService->createPaymentPreference($items, $payer);

        // Verificar si la preferencia fue creada correctamente
        if (isset($preference->init_point)) {
            return redirect($preference->init_point); // Redirigir al usuario a MercadoPago
        } else {
            return redirect()->route('mercadopago.payment', ['raffleId' => $raffleId])->with('error', 'No se pudo crear la preferencia de pago.');
        }
    }

    public function success()
    {
        return redirect()->route('mercadopago.payment')->with('success', 'Pago realizado con éxito.');
    }

    public function failure()
    {
        return redirect()->route('mercadopago.payment')->with('error', 'El pago ha fallado.');
    }
}
