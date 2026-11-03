<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutoController;
use App\Http\Controllers\VentaController;

/*
|--------------------------------------------------------------------------
| Web Routes - Pick-Up Chacabuco
|--------------------------------------------------------------------------
*/

// Catálogo Principal
Route::get('/', [AutoController::class, 'index'])->name('home');
Route::get('/autos', [AutoController::class, 'index'])->name('autos.index');

// Autenticación (Invitados)
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Cerrar sesión (Usuarios autenticados)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas de Autos
Route::get('/autos/{id}', [AutoController::class, 'show'])->name('autos.show')->where('id', '[0-9]+');

// Rutas protegidas (Requieren inicio de sesión)
Route::middleware('auth')->group(function () {
    // Publicar autos
    Route::get('/publicar', [AutoController::class, 'create'])->name('autos.create');
    Route::post('/autos', [AutoController::class, 'store'])->name('autos.store');
    Route::get('/autos/{id}/editar', [AutoController::class, 'edit'])->name('autos.edit')->where('id', '[0-9]+');
    Route::put('/autos/{id}', [AutoController::class, 'update'])->name('autos.update')->where('id', '[0-9]+');
    Route::delete('/autos/{id}', [AutoController::class, 'destroy'])->name('autos.destroy')->where('id', '[0-9]+');

    // Panel del usuario
    Route::get('/mis-publicaciones', [AutoController::class, 'myAutos'])->name('autos.my');

    // Compras y Ventas
    Route::get('/autos/{id}/comprar', [VentaController::class, 'checkout'])->name('ventas.checkout')->where('id', '[0-9]+');
    Route::post('/autos/{id}/comprar', [VentaController::class, 'comprar'])->name('ventas.comprar')->where('id', '[0-9]+');
    Route::get('/mis-compras', [VentaController::class, 'myCompras'])->name('ventas.compras');
    Route::get('/mis-ventas', [VentaController::class, 'myVentas'])->name('ventas.sales');
});
