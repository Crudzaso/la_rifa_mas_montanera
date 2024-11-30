<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Raffles\RaffleController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

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

});


