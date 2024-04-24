<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuloControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 25;

	// Display a listing of the resource. 
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$modulos = Modulo::select()->orderBy('orden', 'ASC')->get();
		return view('modulo.index', [
			'permisos' => $permisos,
			"modulos" => $modulos
		]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'create')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para registrar!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos.
		$message = "";
		if ($request->c_modulo == "") {
			$message = "¡Ingrese el nombre del módulo!";
		} else if (strlen($request->c_modulo) < 3) {
			$message = "¡El módulo debe tener al menos 3 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_modulos')
			->select('modulo')
			->where('modulo', '=', mb_convert_case($request->c_modulo, MB_CASE_UPPER))
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este módulo ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del módulo.
		$total	= count(Modulo::select('idmodulo')->get()) + 1;
		$modulo = new Modulo();
		$modulo->orden = $total;
		$modulo->icono = mb_convert_case($request->c_icono, MB_CASE_LOWER);
		$modulo->modulo = mb_convert_case($request->c_modulo, MB_CASE_UPPER);
		$modulo->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Módulo registrado exitosamente!"]];
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
		$modulo = Modulo::find($id);
		return response($modulo, 200)->header('Content-Type', 'text/json');
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
		if ($request->c_modulo == "") {
			$message = "¡Ingrese el nombre del módulo!";
		} else if (strlen($request->c_modulo) < 3) {
			$message = "¡El módulo debe tener al menos 3 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_modulos')
			->select('modulo')
			->where('modulo', '=', mb_convert_case($request->c_modulo, MB_CASE_UPPER))
			->where('idmodulo', '!=', $id)
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este módulo ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos y modificamos el registro del módulo.
		$modulo = Modulo::find($id);
		$modulo->icono = mb_convert_case($request->c_icono, MB_CASE_LOWER);
		$modulo->modulo = mb_convert_case($request->c_modulo, MB_CASE_UPPER);
		$modulo->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Módulo modificado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}

	// Update module's order.
	public function order(Request $request)
	{
		// Ejecutamos una nueva transacción.
		try {
			// Recorremos los módulos y actualizamos su orden según lo descrito por el usuario.
			DB::transaction(function () use ($request) {
				for ($i = 0; $i < count($request->modulo); $i++) {
					$modulo = Modulo::find($request->modulo[$i]);
					$modulo->orden = $request->orden[$i];
					$modulo->save();
				}
			});
		} catch (\Throwable $th) {
			$response = ["status" => "error", "response" => ["message" => "¡Ocurrió un error al actualizar el orden de los módulos!", "error" => $th]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Enviamos un mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Orden actualizado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
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
		$modulo = Modulo::find($id);
		$modulo->estatus = $modulo->estatus != "A" ? "A" : "I";
		$modulo->save();

		// Enviamos un mensaje de exito al usuario.
		$message	= $modulo->estatus == "A" ? "¡Estatus cambiado a activo!" : "¡Estatus cambiado a inactivo!";
		$response = ["status" => "success", "response" => ["message" => $message]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
