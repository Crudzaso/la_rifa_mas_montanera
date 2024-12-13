<?php

namespace App\Http\Controllers\Tickts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\StoreRequest;
use App\Models\TicketPurchase;
use App\Models\Tickets;
use App\Models\Raffle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Helpers\EmailHelperGlobal;

class TicketsController extends Controller
{
    /**
     * Mostrar una lista de los recursos.
     */
    public function index()
    {
        $tickets = Tickets::with('raffle')  // Cargar la relación de la rifa
            ->where('user_id', Auth::id())  // Filtrar por el usuario autenticado
            ->get();

        return Inertia::render('Tickets/indexTicket', [
            'tickets' => $tickets
        ]);
    }

    /**
     * Mostrar el formulario para crear un nuevo recurso.
     */
    public function create($raffle_id)
    {
        $raffle = Raffle::with('tickets')->findOrFail($raffle_id);
        return Inertia::render('Tickets/createTicket', [
            'raffle' => $raffle
        ]);
    }

    /*
     * Almacenar un recurso recién creado en almacenamiento.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();  // Validar los datos
            $raffle = Raffle::findOrFail($validated['raffle_id']);  // Buscar la rifa
            $ticketNumbers = $validated['ticket_numbers'];  // Obtener los números de boletos seleccionados
            $quantity = count($ticketNumbers);  // Contar la cantidad de boletos

            // Verificar si el usuario ya tiene boletos en esta rifa
            $existingTickets = TicketPurchase::where('user_id', Auth::id())
                ->where('raffle_id', $raffle->id)
                ->first();


            foreach ($validated['ticket_numbers'] as $number) {
                Tickets::create([
                    'user_id' => Auth::id(),
                    'raffle_id' => $raffle->id,
                    'ticket_number' => $number,
                    'is_winner' => false
                ]);
            }

            if ($existingTickets) {
                // Si ya tiene boletos, actualizamos la cantidad
                $existingTickets->update(['quantity' => $existingTickets->quantity + $quantity]);
            } else {
                TicketPurchase::create([
                    'user_id' => Auth::id(),
                    'raffle_id' => $raffle->id,
                    'quantity' => $quantity,
                ]);
            }

            $raffle->increment('tickets_sold', $quantity);

            // Enviar el correo de confirmación de compra
            $this->sendPurchaseConfirmationEmail(Auth::user(), $raffle, $ticketNumbers);

            DB::commit();
            return to_route('tickets.create', $raffle->id)
                ->with('message', 'Boletos comprados exitosamente');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return back()->with('error', 'Hubo un error al comprar los boletos.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tickets $tickets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tickets $tickets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tickets $tickets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tickets $tickets)
    {
        //
    }
}
