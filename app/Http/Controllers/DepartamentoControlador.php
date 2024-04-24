<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartamentoControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 5;

	// Display a listing of the resource. 
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$departamentos = Departamento::all();
		return view('departamento.index', [
			'permisos' => $permisos,
			"departamentos" => $departamentos,
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
		if ($request->c_departamento == "") {
			$message = "¡Ingrese el nombre del departamento!";
		} else if (strlen($request->c_departamento) < 3) {
			$message = "¡El departamento debe tener al menos 3 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_departamentos')
			->select('departamento')
			->where('departamento', '=', mb_convert_case($request->c_departamento, MB_CASE_UPPER))
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Esta departamento ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del departamento.
		$departamento = new Departamento();
		$departamento->departamento = mb_convert_case($request->c_departamento, MB_CASE_UPPER);
		$departamento->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Departamento registrado exitosamente!"]];
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
		$departamento = Departamento::find($id);
		return response($departamento, 200)->header('Content-Type', 'text/json');
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
		if ($request->c_departamento == "") {
			$message = "¡Ingrese el nombre del departamento!";
		} else if (strlen($request->c_departamento) < 3) {
			$message = "¡El departamento debe tener al menos 3 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_departamentos')
			->select('departamento')
			->where('departamento', '=', mb_convert_case($request->c_departamento, MB_CASE_UPPER))
			->where('iddepartamento', '!=', $id)
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Esta departamento ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos y modificamos el registro del departamento.
		$departamento = Departamento::find($id);
		$departamento->departamento = mb_convert_case($request->c_departamento, MB_CASE_UPPER);
		$departamento->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Departamento modificado exitosamente!"]];
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
		$departamento = Departamento::find($id);
		$departamento->estatus = $departamento->estatus != "A" ? "A" : "I";
		$departamento->save();

		// Enviamos un mensaje de exito al usuario.
		$message	= $departamento->estatus == "A" ? "¡Estatus cambiado a activo!" : "¡Estatus cambiado a inactivo!";
		$response = ["status" => "success", "response" => ["message" => $message]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
