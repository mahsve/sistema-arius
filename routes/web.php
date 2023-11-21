<?php

// Importamos los controladores necesarios.
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TypePersonalController;
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

// Controladores [Personal]
Route::resource('/personal', PersonalController::class);
Route::resource('/departamentos', DepartmentController::class);
Route::resource('/tipo-personal', TypePersonalController::class);
