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

// Controlador [Sesión].
Route::controller(App\Http\Controllers\SesionControlador::class)->group(function () {
	Route::get('/iniciar_sesion', 'formulario_iniciar_sesion')->name('iniciar_sesion');
	Route::post('/iniciar_sesion', 'iniciar_sesion')->name('iniciar_sesion');
	Route::get('/cerrar_sesion', 'cerrar_sesion')->name('cerrar_sesion');
});

// Controlador [Dashboard]
Route::get('/', App\Http\Controllers\PanelControlador::class);

// Controlador [Mapa de zona].
Route::controller(App\Http\Controllers\MapaDeZonaControlador::class)->group(function () {
	Route::get('/mapas_de_zonas', 'index')->name('mapas_de_zonas.index');
	Route::get('/mapas_de_zonas/registrar', 'create')->name('mapas_de_zonas.create');
	Route::get('/mapas_de_zonas/codigo/{id}', 'codigo')->name('mapas_de_zonas.code');
	Route::get('/mapas_de_zonas/clientes/{string}', 'clientes')->name('mapas_de_zonas.client'); // Buscar clientes.
	Route::get('/mapas_de_zonas/cliente/{id}', 'cliente')->name('mapas_de_zonas.selected'); // Consultar cliente seleccionado por ID.
	Route::get('/mapas_de_zonas/cedula/{id}', 'cedula')->name('mapas_de_zonas.cedula'); // Consultar usuario por cédula.
	Route::get('/mapas_de_zonas/configuracion/{id}', 'configuraciones')->name('mapas_de_zonas.cogs'); // Consultar la configuración del equipos seleccionado en la tabla ZONAS.
	Route::post('/mapas_de_zonas', 'store')->name('mapas_de_zonas.store');
	Route::get('/mapas_de_zonas/modificar/{id}', 'edit')->name('mapas_de_zonas.edit');
	Route::patch('/mapas_de_zonas', 'update')->name('mapas_de_zonas.update');
	Route::patch('/mapas_de_zonas/pdf/{id}', 'update')->name('mapas_de_zonas.pdf');
});

// Controlador [Clientes].
Route::controller(App\Http\Controllers\ClienteControlador::class)->group(function () {
	Route::get('/clientes', 'index')->name('clientes.index');								// Mostrar el listado.
	Route::get('/clientes/registrar', 'create')->name('clientes.create');		// Mostrar formulario para nuevo registro.
	Route::post('/clientes', 'store')->name('clientes.store');							// Enviar los datos al controlador para nuevo registro.
	Route::get('/clientes/modificar/{id}', 'edit')->name('clientes.edit');	// Mostrar formulario para modificar registro.
	Route::put('/clientes/{id}', 'update')->name('clientes.update');				// Enviar los datos al controlador para modificar registro.
	Route::put('/clientes/estatus/{id}', 'toggle')->name('clientes.status');// 
});

// Controlador [Tarea].
Route::resource('/tareas', App\Http\Controllers\TareaControlador::class);
// Controlador [Cargos].
Route::resource('/cargos', App\Http\Controllers\CargoControlador::class);
Route::put('/cargos/estatus/{id}', [App\Http\Controllers\CargoControlador::class, 'toggle'])->name('cargos.status');
// Controlador [Departamentos].
Route::resource('/departamentos', App\Http\Controllers\DepartamentoControlador::class);
Route::put('/departamentos/estatus/{id}', [App\Http\Controllers\DepartamentoControlador::class, 'toggle'])->name('departamentos.status');
// Controlador [Personal].
Route::resource('/personal', App\Http\Controllers\PersonalControlador::class);
Route::get('/personal/consultar_cargos/{id}', [App\Http\Controllers\PersonalControlador::class, 'consultar_cargos']);
Route::put('/personal/estatus/{id}', [App\Http\Controllers\PersonalControlador::class, 'toggle'])->name('personal.status');
// Controlador [Roles].
Route::resource('/roles', App\Http\Controllers\RolControlador::class);
// Controlador [Dispositivos de zonas].
Route::resource('/dispositivos', App\Http\Controllers\DispositivoControlador::class);
Route::put('/dispositivos/estatus/{id}', [App\Http\Controllers\DispositivoControlador::class, 'toggle'])->name('dispositivos.status');
// Controlador [Configuración dispositivos de zonas].
Route::resource('/dispositivo_cog', App\Http\Controllers\ConfiguracionDisControlador::class);
Route::put('/dispositivo_cog/estatus/{id}', [App\Http\Controllers\ConfiguracionDisControlador::class, 'toggle'])->name('dispositivo_cog.status');