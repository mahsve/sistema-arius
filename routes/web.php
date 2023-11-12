<?php

use App\Http\Controllers\session;
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

Route::get('/', function () {
    if (isset($_SESSION["user"]) and !empty($_SESSION["user"])) {
        return view('dashboard');
    } else {
        return redirect('/iniciar-sesion');
    }
});

Route::get('/iniciar-sesion', function () {
    return view('login');
});

Route::get('/recuperar-cuenta', function () {
    return view('recover');
});

Route::post('/login', [Session::class, 'login']);
