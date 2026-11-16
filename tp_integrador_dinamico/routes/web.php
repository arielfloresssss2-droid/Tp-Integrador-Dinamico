<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutoController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\VentaController;

Route::get('/', [AutoController::class, 'index'])->name('home');
Route::get('/autos', [AutoController::class, 'index'])->name('autos.index');

Route::prefix('vendedores')->name('vendedores.')->group(function () {
    Route::get('/', [VendedorController::class, 'index'])->name('index');
    Route::get('/crear', [VendedorController::class, 'create'])->name('create');
    Route::post('/', [VendedorController::class, 'store'])->name('store');
    Route::get('/{id}', [VendedorController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::get('/{id}/editar', [VendedorController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
    Route::put('/{id}', [VendedorController::class, 'update'])->name('update')->where('id', '[0-9]+');
    Route::delete('/{id}', [VendedorController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
    Route::patch('/{id}/toggle-activo', [VendedorController::class, 'toggleActivo'])->name('toggle')->where('id', '[0-9]+');
});

Route::prefix('ventas')->name('ventas.')->group(function () {
    Route::get('/', [VentaController::class, 'index'])->name('index');
    Route::get('/crear', [VentaController::class, 'create'])->name('create');
    Route::post('/', [VentaController::class, 'store'])->name('store');
    Route::get('/{id}', [VentaController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::get('/{id}/editar', [VentaController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
    Route::put('/{id}', [VentaController::class, 'update'])->name('update')->where('id', '[0-9]+');
    Route::delete('/{id}', [VentaController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
});

Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/autos/{id}', [AutoController::class, 'show'])->name('autos.show')->where('id', '[0-9]+');
Route::get('/publicar', [AutoController::class, 'create'])->name('autos.create');
Route::post('/autos', [AutoController::class, 'store'])->name('autos.store');
Route::get('/autos/{id}/editar', [AutoController::class, 'edit'])->name('autos.edit')->where('id', '[0-9]+');
Route::put('/autos/{id}', [AutoController::class, 'update'])->name('autos.update')->where('id', '[0-9]+');
Route::delete('/autos/{id}', [AutoController::class, 'destroy'])->name('autos.destroy')->where('id', '[0-9]+');

Route::middleware('auth')->group(function () {
    Route::get('/mis-publicaciones', [AutoController::class, 'myAutos'])->name('autos.my');
    Route::get('/autos/{id}/comprar', [VentaController::class, 'checkout'])->name('ventas.checkout')->where('id', '[0-9]+');
    Route::post('/autos/{id}/comprar', [VentaController::class, 'comprar'])->name('ventas.comprar')->where('id', '[0-9]+');
    Route::get('/mis-compras', [VentaController::class, 'myCompras'])->name('ventas.compras');
    Route::get('/mis-ventas', [VentaController::class, 'myVentas'])->name('ventas.sales');
});
