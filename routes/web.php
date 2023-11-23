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

Route::get('/iniciar-sesion', [App\Http\Controllers\SessionController::class, 'showLogin'])->name('show-login');
Route::post('/login', [App\Http\Controllers\SessionController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\SessionController::class, 'logout'])->name('logout');

Route::get('/recuperar', [App\Http\Controllers\SessionController::class, 'showRecover'])->name('show-recover');

// Controladores [Personal]
Route::resource('/personal', App\Http\Controllers\PersonalController::class);
Route::resource('/departamentos', App\Http\Controllers\DepartmentController::class);
Route::resource('/tipo-personal', App\Http\Controllers\TypePersonalController::class);

Route::resource('/clientes', App\Http\Controllers\ClientController::class);
