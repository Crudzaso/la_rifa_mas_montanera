<?php

namespace App\Http\Controllers\Raffles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Raffles\StoreRequest;
use App\Models\Raffle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RaffleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $raffles = Raffle::all();
        //dd($raffles);
        return Inertia::render('Raffles/indexRaffle',compact('raffles'));
        dd($raffles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Raffles/createRaffle');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $raffle = $request->validated();
            $raffle['user_id'] = Auth::user()->id;
            $raffle['total_tickets'] = 100;
            $raffle['status'] = now()->lt($raffle['end_date']) ? 'pending' : 'finished';

            Raffle::create($raffle);

            return to_route('raffles.create')
                ->with('message', 'Rifa creada correctamente');
        } catch (\Throwable $th) {
            return to_route('raffles.create')
                ->with('message', 'Error al crear la rifa');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Raffle $raffle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Raffle $raffle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Raffle $raffle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Raffle $raffle)
    {
        //
    }
}
