<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\RegisterController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\UserPerfilController;
use App\Http\Controllers\SkinController;
use App\Http\Controllers\CalidadController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\Admin\ItemAdminController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\CarritoController; 
/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| USER PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserPerfilController::class, 'show']);
    Route::put('/user', [UserPerfilController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| SKINS
|--------------------------------------------------------------------------
*/

Route::get('/skins', [SkinController::class, 'index']);
Route::get('/skins/{id}', [SkinController::class, 'show']);
Route::get('/skins/{item_id}/exterior/{exterior_id}', [SkinController::class, 'filtrarExterior']);

/*
|--------------------------------------------------------------------------
| FILTER DATA
|--------------------------------------------------------------------------
*/

Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/calidades', [CalidadController::class, 'index']);
Route::get('/filters', [FilterController::class, 'getFilters']);

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::get('/skins/{id}', [ItemAdminController::class, 'show']);      // Obtener item
    Route::post('/skins', [ItemAdminController::class, 'store']);         // Crear item
    Route::put('/skins/{id}', [ItemAdminController::class, 'update']);    // Editar item
    Route::delete('/skins/{id}', [ItemAdminController::class, 'destroy']); // Borrar item
});


//Añadir a Favoritos
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/favoritos/toggle/{item_id}', [UserPerfilController::class, 'toggleFavorito']);
    Route::get('/favoritos', [UserPerfilController::class, 'favoritos']);
});


//Ruta para ver lo que un usuario ha comprado
Route::middleware('auth:sanctum')->get('/usuario/compras', [UserPerfilController::class, 'compras']);
//Para ver lo que posee
Route::middleware('auth:sanctum')->get('/usuario/inventario', [UserPerfilController::class, 'inventario']);

//Carrito 

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/depositar', [StripeController::class, 'depositar']);
    Route::post('/confirmar-deposito', [StripeController::class, 'confirmarDeposito']);
    Route::get('/carrito', [CarritoController::class, 'index']);
    Route::post('/carrito', [CarritoController::class, 'agregar']);
    Route::put('/carrito/{item_id}', [CarritoController::class, 'actualizar']);
    Route::delete('/carrito/{item_id}', [CarritoController::class, 'eliminar']);
    Route::middleware('auth:sanctum')->post('/carrito/checkout', [CarritoController::class, 'checkout']);
});