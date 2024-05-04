<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionDis;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracionDisControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 21;

	// Display a listing of the resource.
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$dispositivos = Dispositivo::all();
		$configuraciones = ConfiguracionDis::all();
		return view('configuracion_dispositivo.index', [
			'permisos' => $permisos,
			"configuraciones" => $configuraciones,
			'dispositivos' => $dispositivos,
		]);
	}

	// Show the form for creating a new resource.
	public function create()
	{
	}

	// Store a newly created resource in storage.
	public function store(Request $request)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'create')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para registrar!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos.
		$message = "";
		if ($request->c_configuracion == "") {
			$message = "¡Ingrese el nombre de la configuración!";
		} else if (strlen($request->c_configuracion) < 2) {
			$message = "¡La configuración debe tener al menos 2 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existente = DB::table('tb_config_disp')
			->select('idconfiguracion')
			->where('configuracion', '=', mb_convert_case($request->c_configuracion, MB_CASE_UPPER))
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Esta configuración del dispositivo ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro de la configuracion.
		$configuracion = new ConfiguracionDis();
		$configuracion->configuracion = mb_convert_case($request->c_configuracion, MB_CASE_UPPER); // Transformamos a mayuscula.
		$configuracion->save();

		// Verificamos si es un registro rápido.
		$registro = null;
		if (isset($request->modulo) and !empty($request->modulo)) {
			$registro = $configuracion;
		}

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Configuración del dispositivo registrado exitosamente!", "data" => $registro]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Display the specified resource.
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource.
	public function edit(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'update')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para modificar!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos el registro a modificar.
		$configuracion = ConfiguracionDis::find($id);
		return response($configuracion, 200)->header('Content-Type', 'text/json');
	}

	// Update the specified resource in storage.
	public function update(Request $request, string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'update')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para modificar!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos.
		$message = "";
		if ($request->c_configuracion == "") {
			$message = "¡Ingrese el nombre de la configuración!";
		} else if (strlen($request->c_configuracion) < 2) {
			$message = "¡La configuración debe tener al menos 2 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existente = DB::table('tb_config_disp')
			->select('idconfiguracion')
			->where('configuracion', '=', mb_convert_case($request->c_configuracion, MB_CASE_UPPER))
			->where('idconfiguracion', '!=', $id)
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Esta configuración del dispositivo ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos y modificamos el registro de la configuración.
		$configuracion = ConfiguracionDis::find($id);
		$configuracion->configuracion = mb_convert_case($request->c_configuracion, MB_CASE_UPPER);
		$configuracion->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Configuración del dispositivo modificado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Remove the specified resource from storage.
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'toggle')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para cambiar el estatus!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos el registro a actualizar el estatus.
		$configuracion = ConfiguracionDis::find($id);
		$configuracion->estatus = $configuracion->estatus != "A" ? "A" : "I";
		$configuracion->save();

		// Enviamos un mensaje de exito al usuario.
		$message	= $configuracion->estatus == "A" ? "¡Estatus cambiado a activo!" : "¡Estatus cambiado a inactivo!";
		$response = ["status" => "success", "response" => ["message" => $message]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
