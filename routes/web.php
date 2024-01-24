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

Route::get('/', App\Http\Controllers\DashboardController::class);

// Controlador [Sesión]
Route::get('/iniciar-sesion', [App\Http\Controllers\SessionController::class, 'show_login']);
Route::post('/login', [App\Http\Controllers\SessionController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\SessionController::class, 'logout'])->name('logout');

// Controlador [Recuperar cuenta]
Route::get('/recuperar', [App\Http\Controllers\SessionController::class, 'show_recover']);

// Controlador [Personal]
Route::resource('/mapas-de-zonas', App\Http\Controllers\ZoneMapController::class);
Route::get('/buscar-cliente/{type}/{id}', [App\Http\Controllers\ZoneMapController::class, 'search_client']);


// Controlador [Cliente].
Route::resource('/personal', App\Http\Controllers\PersonalController::class);
Route::resource('/departamentos', App\Http\Controllers\DepartmentController::class);
Route::resource('/cargo', App\Http\Controllers\PositionController::class);
Route::resource('/clientes', App\Http\Controllers\ClientController::class);
Route::get('/clientes/{id}/instalacion', [App\Http\Controllers\ClientController::class, 'install'])->name('instalacion');
Route::post('/clientes/{id}/instalacion', [App\Http\Controllers\ClientController::class, 'update_install']);