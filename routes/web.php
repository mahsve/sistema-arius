<?php

// Importamos los controladores necesarios.
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;

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

Route::get('/', DashboardController::class);

Route::get('/iniciar-sesion', [SessionController::class, 'showLogin'])->name('show-login');
Route::post('/login', [SessionController::class, 'login'])->name('login');
Route::get('/logout', [SessionController::class, 'logout'])->name('logout');

Route::get('/recuperar', [SessionController::class, 'showRecover'])->name('show-recover');