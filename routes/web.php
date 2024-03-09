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

// Controlador [Sesión]
Route::controller(App\Http\Controllers\SesionControlador::class)->group(function () {
	Route::get('/iniciar_sesion', 'formulario_iniciar_sesion')->name('iniciar_sesion');
	Route::post('/iniciar_sesion', 'iniciar_sesion')->name('iniciar_sesion');
	Route::get('/cerrar_sesion', 'cerrar_sesion')->name('cerrar_sesion');
});

Route::get('/', App\Http\Controllers\PanelControlador::class);

// Controlador [Mapa de zona]
Route::controller(App\Http\Controllers\MapaDeZonaControlador::class)->group(function () {
	Route::get('/mapas_de_zonas', 'index')->name('mapas_de_zonas.index');
	Route::get('/mapas_de_zonas/registrar', 'create')->name('mapas_de_zonas.create');
	Route::post('/mapas_de_zonas', 'store')->name('mapas_de_zonas.store');
	Route::get('/mapas_de_zonas/{id}/modificar', 'edit')->name('mapas_de_zonas.edit');
	Route::patch('/mapas_de_zonas', 'update')->name('mapas_de_zonas.update');
	// Route::get('/buscar-cliente/{type}/{id}', [App\Http\Controllers\MapaDeZonaControlador::class, 'search_client']);
});


// Controlador [Maestros].
Route::controller(App\Http\Controllers\ClienteControlador::class)->group(function () {
	Route::get('/clientes', 'index')->name('clientes.index');								// Mostrar el listado.
	Route::get('/clientes/registrar', 'create')->name('clientes.create');		// Mostrar formulario para nuevo registro.
	Route::post('/clientes', 'store')->name('clientes.store');							// Enviar los datos al controlador para nuevo registro.
	Route::get('/clientes/modificar/{id}', 'edit')->name('clientes.edit');	// Mostrar formulario para modificar registro.
	Route::put('/clientes/{id}', 'update')->name('clientes.update');				// Enviar los datos al controlador para modificar registro.
	// Route::get('/clientes/{id}', 'show')->name('clientes.show');					// Mostrar información sin modificar.
	// Route::delete('/clientes/{id}', 'delete')->name('clientes.delete');	// Enviar los datos al controlador para eliminar registro.
});

Route::resource('/cargos', App\Http\Controllers\CargoControlador::class);
Route::get('/cargos/estatus/{id}', [App\Http\Controllers\CargoControlador::class, 'estatus']);

Route::resource('/departamentos', App\Http\Controllers\DepartamentoControlador::class);
Route::resource('/personal', App\Http\Controllers\PersonalControlador::class);