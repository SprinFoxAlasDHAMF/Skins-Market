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





Route::post('/depositar', [StripeController::class, 'depositar']);