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


    Route::get('usuarios',function(){
        return Inertia::render('Users',[
            'users' => User::all()
        ]);
        Route::get('usuarios',[UserController::class,'index'])->name('usuarios.index');
        Route::get('usuarios/crear',[UserController::class,'create'])->name('usuarios.create');
        Route::post('usuarios',[UserController::class,'store'])->name('usuarios.store');
        Route::get('usuarios/{user}/editar',[UserController::class,'edit'])->name('usuarios.edit');
        Route::put('usuarios/{user}',[UserController::class,'update'])->name('usuarios.update');
        Route::delete('usuarios/{user}',[UserController::class,'destroy'])->name('usuarios.destroy');

    })->name('usuarios.page');


});
