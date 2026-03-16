<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// routes/web.php
Route::get('/login', function () {
    // Si l'usuari ja està autenticat, el redirigeix al dashboard
    if (auth()->check()) {
        return redirect()->route('filament.auth.dashboard');
    }
    // Si no està autenticat, es deixa a la pàgina de login
    return view('auth.login');
})->name('login');