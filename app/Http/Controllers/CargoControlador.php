<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CargoControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 9;

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
		$cargos = DB::table('tb_cargos')
			->select('tb_cargos.*', 'tb_departamentos.departamento')
			->join('tb_departamentos', 'tb_cargos.iddepartamento', 'tb_departamentos.iddepartamento')
			->get();
		return view('cargo.index', [
			'permisos' => $permisos,
			"departamentos" => $departamentos,
			"cargos" => $cargos,
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
		if ($request->c_departamento == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el departamento"]]);
		} else if ($request->c_cargo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del cargo"]]);
		} else if (strlen($request->c_cargo) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El cargo debe tener al menos 3 caracteres"]]);
		}

		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existente = DB::table('tb_cargos')
			->select('idcargo')
			->where('cargo', '=', mb_convert_case($request->c_cargo, MB_CASE_UPPER))
			->where('iddepartamento', '=', $request->c_departamento)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este cargo ya se encuentra registrado"]]);
		}

		// Creamos el nuevo registro del cargo.
		$cargo = new Cargo();
		$cargo->cargo = mb_convert_case($request->c_cargo, MB_CASE_UPPER); // Transformamos a mayuscula.
		$cargo->iddepartamento = $request->c_departamento;
		$cargo->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Cargo registrado exitosamente!"]];
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
			return $this->error403();
		}

		// Consultamos el registro a modificar.
		$cargo = Cargo::find($id);
		return json_encode($cargo);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, '')) {
			return $this->error403();
		}

		// Validamos.
		if ($request->c_departamento == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el departamento"]]);
		} else if ($request->c_cargo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del cargo"]]);
		} else if (strlen($request->c_cargo) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El cargo debe tener al menos 3 caracteres"]]);
		}

		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existente = DB::table('tb_cargos')
			->select('idcargo')
			->where('cargo', '=', mb_convert_case($request->c_cargo, MB_CASE_UPPER))
			->where('iddepartamento', '=', $request->c_departamento)
			->where('idcargo', '!=', $id)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este cargo ya se encuentra registrado"]]);
		}

		// Consultamos y modificamos el registro del cargo.
		$cargo = Cargo::find($id);
		$cargo->cargo = mb_convert_case($request->c_cargo, MB_CASE_UPPER);
		$cargo->iddepartamento = $request->c_departamento;
		$cargo->save();

		return json_encode(["status" => "success", "response" => ["message" => "Cargo modificado exitosamente"]]);
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, '')) {
			return $this->error403();
		}

		// Consultamos el registro a actualizar el estatus.
		$cargo = Cargo::find($id);
		$cargo->estatus = $cargo->estatus != "A" ? "A" : "I";
		$cargo->save();

		return json_encode(["status" => "success", "response" => ["message" => ""]]);
	}
}
