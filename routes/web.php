<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubcategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\MovimientoController;

// Redirigir raíz al dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rutas protegidas
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Catálogo
    Route::resource('categorias', CategoriaController::class);
    Route::resource('subcategorias', SubcategoriaController::class);

    // Inventario
    Route::resource('productos', ProductoController::class);
    Route::resource('movimientos', MovimientoController::class);

    // Ventas
    Route::resource('clientes', ClienteController::class);
    Route::resource('ventas', VentaController::class);
    Route::resource('detalle-ventas', DetalleVentaController::class);

    // Búsqueda de productos para venta (AJAX)
    Route::get('/productos/buscar', [ProductoController::class, 'buscar'])->name('productos.buscar');

});

// Autenticación
require __DIR__.'/auth.php';