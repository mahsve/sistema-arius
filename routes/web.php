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

// Rutas disponibles cuando el usuario no haya iniciado sesión.
Route::middleware('guest')->group(function () {
	// Controlador [Sesión].
	Route::controller(App\Http\Controllers\SesionControlador::class)->group(function () {
		Route::get('/iniciar_sesion', 'formulario_iniciar_sesion')->name('session.login');
		Route::post('/iniciar_sesion', 'iniciar_sesion')->name('session.login');
		Route::get('/recuperar_cuenta', 'formulario_recuperar')->name('session.recover');
		Route::post('/recuperar_cuenta', 'recuperar_cuenta')->name('session.recover');
		Route::get('/preguntas_seguridad', 'formulario_preguntas')->name('session.questions');
		Route::post('/preguntas_seguridad', 'verificar_respuestas')->name('session.verify_answers');
		Route::get('/restablecer', 'formulario_contrasena')->name('session.form_password');
		Route::post('/restablecer', 'restablecer')->name('session.reset_password');
	});
});

// Rutas disponibles cuando el usuario haya iniciado sesión.
Route::middleware('auth')->group(function () {
	// Controlador [Dashboard]
	Route::get('/', [App\Http\Controllers\PanelControlador::class, 'dashboard']);
	Route::get('/acceso_restringido', [App\Http\Controllers\PanelControlador::class, 'error403'])->name('dashboard.403');

	// Controlador [Tarea].
	Route::resource('/tareas', App\Http\Controllers\TareaControlador::class);

	/**
	 * MONITOREO
	 */
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
		Route::patch('/mapas_de_zonas/modificar/{id}', 'update')->name('mapas_de_zonas.update');
		Route::get('/mapas_de_zonas/pdf/{id}', 'generar_pdf')->name('mapas_de_zonas.pdf');
	});

	// Controlador [Servicio técnico].
	Route::controller(App\Http\Controllers\ServicioTecnicoSolicitadoControlador::class)->group(function () {
		Route::get('/servicios_tecnico_solicitados', 'index')->name('servicios_tecnico_solicitados.index');
		Route::get('/servicios_tecnico_solicitados/clientes/{string}', 'clientes')->name('servicios_tecnico_solicitados.client'); // Buscar clientes.
		Route::get('/servicios_tecnico_solicitados/mapa_de_zona/{id}', 'mapa_de_zona')->name('servicios_tecnico_solicitados.selected'); // Consultar cliente seleccionado por ID.
		Route::post('/servicios_tecnico_solicitados', 'store')->name('servicios_tecnico_solicitados.store');
		Route::get('/servicios_tecnico_solicitados/modificar/{id}', 'edit')->name('servicios_tecnico_solicitados.edit');
		Route::patch('/servicios_tecnico_solicitados', 'update')->name('servicios_tecnico_solicitados.update');
		Route::get('/servicios_tecnico_solicitados/pdf/{id}', 'generar_pdf')->name('servicios_tecnico_solicitados.pdf');
	});

	// Controlador [Monitoreo].
	Route::controller(App\Http\Controllers\MonitoreoControlador::class)->group(function () {
		Route::get('/monitoreo', 'index')->name('monitoreo.index');
		Route::get('/monitoreo/registrar', 'create')->name('monitoreo.create');
		Route::post('/monitoreo', 'store')->name('monitoreo.store');
		Route::get('/monitoreo/modificar/{id}', 'edit')->name('monitoreo.edit');
		Route::patch('/monitoreo', 'update')->name('monitoreo.update');
		Route::get('/monitoreo/pdf/{id}', 'generar_pdf')->name('monitoreo.pdf');
	});

	/**
	 * CONFIGURACION
	 */
	// Controlador [Clientes].
	Route::controller(App\Http\Controllers\ClienteControlador::class)->group(function () {
		Route::get('/clientes', 'index')->name('clientes.index');								// Mostrar el listado.
		Route::get('/clientes/registrar', 'create')->name('clientes.create');		// Mostrar formulario para nuevo registro.
		Route::post('/clientes', 'store')->name('clientes.store');							// Enviar los datos al controlador para nuevo registro.
		Route::get('/clientes/modificar/{id}', 'edit')->name('clientes.edit');	// Mostrar formulario para modificar registro.
		Route::put('/clientes/{id}', 'update')->name('clientes.update');				// Enviar los datos al controlador para modificar registro.
		Route::put('/clientes/estatus/{id}', 'toggle')->name('clientes.status'); // 
	});

	// Controlador [Departamentos].
	Route::resource('/departamentos', App\Http\Controllers\DepartamentoControlador::class);
	Route::put('/departamentos/estatus/{id}', [App\Http\Controllers\DepartamentoControlador::class, 'toggle'])->name('departamentos.status');

	// Controlador [Cargos].
	Route::resource('/cargos', App\Http\Controllers\CargoControlador::class);
	Route::put('/cargos/estatus/{id}', [App\Http\Controllers\CargoControlador::class, 'toggle'])->name('cargos.status');

	// Controlador [Personal].
	Route::controller(App\Http\Controllers\PersonalControlador::class)->group(function () {
		Route::get('/personal', 'index')->name('personal.index');								// Mostrar el listado.
		Route::get('/personal/registrar', 'create')->name('personal.create');		// Mostrar formulario para nuevo registro.
		Route::get('/personal/cargos/{id}', 'cargos')->name('personal.cargos');	// 
		Route::post('/personal', 'store')->name('personal.store');							// Enviar los datos al controlador para nuevo registro.
		Route::get('/personal/modificar/{id}', 'edit')->name('personal.edit');	// Mostrar formulario para modificar registro.
		Route::patch('/personal/{id}', 'update')->name('personal.update');				// Enviar los datos al controlador para modificar registro.
		Route::put('/personal/estatus/{id}', 'toggle')->name('personal.status');
	});

	// Controlador [Dispositivos de zonas].
	Route::resource('/dispositivos', App\Http\Controllers\DispositivoControlador::class);
	Route::put('/dispositivos/estatus/{id}', [App\Http\Controllers\DispositivoControlador::class, 'toggle'])->name('dispositivos.status');

	// Controlador [Configuración dispositivos de zonas].
	Route::resource('/configuracion_disp', App\Http\Controllers\ConfiguracionDisControlador::class);
	Route::put('/configuracion_disp/estatus/{id}', [App\Http\Controllers\ConfiguracionDisControlador::class, 'toggle'])->name('dispositivo_cog.status');

	/**
	 * SEGURIDAD
	 */
	// Controlador [Módulos].
	Route::resource('/modulos', App\Http\Controllers\ModuloControlador::class);
	Route::post('/modulos/orden', [App\Http\Controllers\ModuloControlador::class, 'order'])->name('modulos.order');
	Route::put('/modulos/estatus/{id}', [App\Http\Controllers\ModuloControlador::class, 'toggle'])->name('modulos.status');

	// Controlador [Servicios].
	Route::resource('/servicios', App\Http\Controllers\ServicioControlador::class);
	Route::post('/servicios/orden', [App\Http\Controllers\ServicioControlador::class, 'order'])->name('servicios.order');
	Route::get('/servicios/submodulos/{id}', [App\Http\Controllers\ServicioControlador::class, 'submodulos'])->name('servicios.submodules');
	Route::put('/servicios/estatus/{id}', [App\Http\Controllers\ServicioControlador::class, 'toggle'])->name('servicios.status');

	// Controlador [Roles].
	Route::resource('/roles', App\Http\Controllers\RolControlador::class);
	Route::put('/roles/estatus/{id}', [App\Http\Controllers\RolControlador::class, 'toggle'])->name('roles.status');

	// Controlador [Roles].
	Route::resource('/usuarios', App\Http\Controllers\UsuarioControlador::class);
	Route::put('/usuarios/estatus/{id}', [App\Http\Controllers\UsuarioControlador::class, 'toggle'])->name('roles.status');

	// Controlador [Roles].
	Route::get('/bitacora', App\Http\Controllers\BitacoraControlador::class)->name('bitacora.index');

	/**
	 * PERFIL
	 */
	// Controlador [Gestionar perfil del usuario].
	Route::controller(App\Http\Controllers\PerfilControlador::class)->group(function () {
		Route::get('/perfil', 'formulario_perfil')->name('profile.index');
		Route::post('/perfil', 'actualizar_datos')->name('profile.update');
		Route::get('/seguridad', 'formulario_seguridad')->name('security.index');
		Route::post('/actualizar_contrasena', 'actualizar_contrasena')->name('password.update');
		Route::post('/actualizar_preguntas', 'actualizar_preguntas')->name('questions.update');
	});

	// Controlador [Sesión].
	Route::get('/cerrar_sesion', [App\Http\Controllers\SesionControlador::class, 'cerrar_sesion'])->name('session.logout');
});
