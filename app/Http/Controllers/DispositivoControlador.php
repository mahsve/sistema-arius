<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispositivoControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 17;

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
		return view('dispositivo.index', [
			'permisos' => $permisos,
			"dispositivos" => $dispositivos
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
		if ($request->c_dispositivo == "") {
			$message = "¡Ingrese el nombre del dispositivo!";
		} else if (strlen($request->c_dispositivo) < 2) {
			$message = "¡El dispositivo debe tener al menos 3 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_dispositivos')
			->select('dispositivo')
			->where('dispositivo', '=', mb_convert_case($request->c_dispositivo, MB_CASE_UPPER))
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este dispositivo ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del dispositivo.
		$dispositivo = new Dispositivo();
		$dispositivo->dispositivo = mb_convert_case($request->c_dispositivo, MB_CASE_UPPER);
		$dispositivo->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Dispositivo registrado exitosamente!"]];
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
		$dispositivo = Dispositivo::find($id);
		return response($dispositivo, 200)->header('Content-Type', 'text/json');
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
		if ($request->c_dispositivo == "") {
			$message = "¡Ingrese el nombre del dispositivo!";
		} else if (strlen($request->c_dispositivo) < 2) {
			$message = "¡El dispositivo debe tener al menos 3 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_dispositivos')
			->select('dispositivo')
			->where('dispositivo', '=', mb_convert_case($request->c_dispositivo, MB_CASE_UPPER))
			->where('iddispositivo', '!=', $id)
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este dispositivo ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos y modificamos el registro del dispositivo.
		$dispositivo = Dispositivo::find($id);
		$dispositivo->dispositivo = mb_convert_case($request->c_dispositivo, MB_CASE_UPPER);
		$dispositivo->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Dispositivo modificado exitosamente!"]];
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
		$dispositivo = Dispositivo::find($id);
		$dispositivo->estatus = $dispositivo->estatus != "A" ? "A" : "I";
		$dispositivo->save();

		// Enviamos un mensaje de exito al usuario.
		$message	= $dispositivo->estatus == "A" ? "¡Estatus cambiado a activo!" : "¡Estatus cambiado a inactivo!";
		$response = ["status" => "success", "response" => ["message" => $message]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
