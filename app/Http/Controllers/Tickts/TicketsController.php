<?php

namespace App\Http\Controllers\Tickts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\StoreRequest;
use App\Models\Tickets;
use App\Models\Raffle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Tickets::with('raffle')  // Carga toda la relación
            ->where('user_id', Auth::id())
            ->get();


        return Inertia::render('Tickets/indexTicket', [
            'tickets' => $tickets
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($raffle_id)
    {
        $raffle = Raffle::with('tickets')->findOrFail($raffle_id);
        return Inertia::render('Tickets/createTicket', [
            'raffle' => $raffle
        ]);
    }
    /*
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $raffle = Raffle::findOrFail($validated['raffle_id']);

            // Verificar si el usuario ya tiene tickets en la rifa
            $existingTickets = Tickets::where('user_id', Auth::id())
                ->where('raffle_id', $raffle->id)
                ->count();

                //Se puede usar para calcular el total de la compra y pasarlo a mercado pago

            //$totalAmount = count($validated['ticket_numbers']) * $raffle->price_tickets; 

            foreach ($validated['ticket_numbers'] as $number) {
                Tickets::create([
                    'user_id' => Auth::id(),
                    'raffle_id' => $raffle->id,
                    'ticket_number' => $number,
                    'is_winner' => false
                ]);
            }

            // Actualizar tickets vendidos
            $raffle->increment('tickets_sold', count($validated['ticket_numbers']));

            DB::commit();
            return to_route('tickets.create', $raffle->id)
                ->with('message', 'Boletos comprados exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return to_route('tickets.create', $raffle->id)->withErrors(['error' => 'Error al comprar los boletos']);
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
