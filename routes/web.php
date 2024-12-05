<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Raffles\RaffleController;
use App\Http\Controllers\Tickts\TicketsController;
use App\Models\Raffle;
use App\Http\Controllers\API\LotteryController;

// Ruta principal
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'raffles' => Raffle::all()
    ]);
})->name('welcome');

// Rutas API
Route::prefix('api')->group(function () {
    Route::get('loteria/resultados', [LotteryController::class, 'getResults']);
});

// Rutas protegidas
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/loterias', function () {
        return Inertia::render('Lottery');
    })->name('lottery');

    Route::prefix('usuarios')->group(function(){
        Route::get('/',[UserController::class,'index'])->name('users.index');
        Route::get('/crear',[UserController::class,'create'])->name('users.create');
        Route::post('/',[UserController::class,'store'])->name('users.store');
        Route::get('/{user}/editar',[UserController::class,'edit'])->name('users.edit');
        Route::put('/{user}',[UserController::class,'update'])->name('users.update');
        Route::delete('/{user}',[UserController::class,'destroy'])->name('users.destroy');
    });


    Route::prefix('rifas')->group(function(){
        Route::get('/',[RaffleController::class,'index'])->name('raffles.index');
        Route::get('/crear',[RaffleController::class,'create'])->name('raffles.create');
        Route::post('/',[RaffleController::class,'store'])->name('raffles.store');
        Route::get('/{raffle}/editar',[RaffleController::class,'edit'])->name('raffles.edit');
        Route::put('/{raffle}',[RaffleController::class,'update'])->name('raffles.update');
        Route::delete('/{raffle}',[RaffleController::class,'destroy'])->name('raffles.destroy');
    });

    Route::prefix('boletos')->group(function(){
        Route::get('/',[TicketsController::class,'index'])->name('tickets.index');
        Route::get('/Comprar/{raffle_id}',[TicketsController::class,'create'])->name('tickets.create');
        Route::post('/',[TicketsController::class,'store'])->name('tickets.store');
        Route::get('/{ticket}/editar',[TicketsController::class,'edit'])->name('tickets.edit');
        Route::put('/{ticket}',[TicketsController::class,'update'])->name('tickets.update');
        Route::delete('/{ticket}',[TicketsController::class,'destroy'])->name('tickets.destroy');
        Route::get('/{ticket}', [TicketsController::class, 'show'])->name('tickets.show');
    });


    Route::get('/rifas/ver', [RaffleController::class, 'publicIndex'])->name('raffles.public');

});

