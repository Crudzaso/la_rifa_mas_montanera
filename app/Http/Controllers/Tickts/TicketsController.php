<?php

namespace App\Http\Controllers\Tickts;

use App\Http\Controllers\Controller;
use App\Models\Tickets;
use App\Models\Raffle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Tickets::all();
        return Inertia::render('Tickets/indexTicket',compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $raffle = Raffle::findOrFail($request->raffle_id);
        return Inertia::render('Tickets/createTicket', [
            'raffle' => $raffle
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
