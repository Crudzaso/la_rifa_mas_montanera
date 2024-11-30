<?php

namespace App\Http\Controllers\Raffles;
use App\Http\Requests\Raffles\UpdateRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Raffles\StoreRequest;
use App\Models\Raffle;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

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

            // Determinar estado inicial
            $now = now();
            $startDate = Carbon::parse($raffle['start_date']);

            if ($now->lt($startDate)) {
                $raffle['status'] = 'pending';
            } else {
                $raffle['status'] = 'ongoing';
            }

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
        $raffle = Raffle::findOrFail($raffle->id);
        return Inertia::render('Raffles/editRaffle',compact('raffle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Raffle $raffle)
    {
        try {
            $validated = $request->validated();

            // Determinar estado según fechas
            $now = now()->startOfDay();
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->startOfDay();

            if ($now->lt($startDate)) {
                $validated['status'] = 'pending';
            } elseif ($now->between($startDate, $endDate)) {
                $validated['status'] = 'ongoing';
            } else {
                $validated['status'] = 'finished';
            }

            $raffle->update($validated);

            return to_route('raffles.edit', $raffle)
                ->with('message', 'Rifa actualizada correctamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar la rifa']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Raffle $raffle)
    {
        //
    }
}
