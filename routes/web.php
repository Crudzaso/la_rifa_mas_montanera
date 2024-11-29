<?php

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Users\UserController;

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
});
