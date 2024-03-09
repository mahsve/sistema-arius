<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', App\Http\Controllers\PanelControlador::class);

// // Controlador [Sesión]
// Route::get('/iniciar-sesion', [App\Http\Controllers\SessionController::class, 'show_login']);
// Route::post('/login', [App\Http\Controllers\SessionController::class, 'login'])->name('login');
// Route::get('/logout', [App\Http\Controllers\SessionController::class, 'logout'])->name('logout');
// Route::get('/recuperar', [App\Http\Controllers\SessionController::class, 'show_recover']);

// Controlador [Mapa de zona]
// Route::controller(App\Http\Controllers\MapaDeZonaControlador::class)->group(function () {
// 	Route::get('/mapas-de-zonas', 'index');

// 	// Route::resource('/mapas-de-zonas', App\Http\Controllers\MapaDeZonaControlador::class);
// 	// Route::get('/buscar-cliente/{type}/{id}', [App\Http\Controllers\MapaDeZonaControlador::class, 'search_client']);
// });


// Controlador [Cliente].
Route::resource('/cargos', App\Http\Controllers\CargoControlador::class);
Route::resource('/departamentos', App\Http\Controllers\DepartamentoControlador::class);
Route::resource('/personal', App\Http\Controllers\PersonalControlador::class);
Route::resource('/clientes', App\Http\Controllers\ClienteControlador::class);


// Route::get('/clientes/{id}/instalacion', [App\Http\Controllers\ClientController::class, 'install'])->name('instalacion');
// Route::post('/clientes/{id}/instalacion', [App\Http\Controllers\ClientController::class, 'update_install']);