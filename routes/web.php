<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MercadoPagoController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('mercadopago/pagar', [MercadoPagoController::class, 'showPaymentForm'])->name('mercadopago.payment');
Route::post('mercadopago/crear-pago', [MercadoPagoController::class, 'createPayment'])->name('mercadopago.createPayment');
Route::get('mercadopago/success', [MercadoPagoController::class, 'success'])->name('mercadopago.success');
Route::get('mercadopago/failure', [MercadoPagoController::class, 'failure'])->name('mercadopago.failed');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
