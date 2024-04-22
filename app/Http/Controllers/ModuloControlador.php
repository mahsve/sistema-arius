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
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, '')) {
			return $this->error403();
		}

		// Validamos.
		if ($request->c_modulo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del módulo"]]);
		} else if (strlen($request->c_modulo) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El módulo debe tener al menos 3 caracteres"]]);
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_modulos')
			->select('modulo')
			->where('modulo', '=', mb_convert_case($request->c_modulo, MB_CASE_UPPER))
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este módulo ya se encuentra registrado"]]);
		}

		// Creamos el nuevo registro del módulo.
		$total	= count(Modulo::select('idmodulo')->get());
		$modulo = new Modulo();
		$modulo->orden = $total;
		$modulo->icono = mb_convert_case($request->c_icono, MB_CASE_LOWER);
		$modulo->modulo = mb_convert_case($request->c_modulo, MB_CASE_UPPER);
		$modulo->save();

		// Enviamos mensaje de existo al usuario.
		return json_encode(["status" => "success", "response" => ["message" => "Módulo registrado exitosamente"]]);
	}

	// Display the specified resource. 
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, '')) {
			return $this->error403();
		}

		// Consultamos el registro a modificar.
		$modulo = Modulo::find($id);
		return json_encode($modulo);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, '')) {
			return $this->error403();
		}

		// Validamos.
		if ($request->c_modulo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del módulo"]]);
		} else if (strlen($request->c_modulo) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El módulo debe tener al menos 3 caracteres"]]);
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_modulos')
			->select('modulo')
			->where('modulo', '=', mb_convert_case($request->c_modulo, MB_CASE_UPPER))
			->where('idmodulo', '!=', $id)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este módulo ya se encuentra registrado"]]);
		}

		// Consultamos y modificamos el registro del módulo.
		$modulo = Modulo::find($id);
		$modulo->icono = mb_convert_case($request->c_icono, MB_CASE_LOWER);
		$modulo->modulo = mb_convert_case($request->c_modulo, MB_CASE_UPPER);
		$modulo->save();

		// Enviamos mensaje de existo al usuario.
		return json_encode(["status" => "success", "response" => ["message" => "Módulo modificado exitosamente"]]);
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}

	// Update module's order.
	public function order(Request $request)
	{
		DB::transaction(function () use ($request) {
			// Recorremos los módulos y actualizamos su orden según lo descrito por el usuario.
			for ($i = 0; $i < count($request->modulo); $i++) {
				$modulo = Modulo::find($request->modulo[$i]);
				$modulo->orden = $request->orden[$i];
				$modulo->save();
			}
		});
		return json_encode(["status" => "success", "response" => ["message" => "¡Orden actualizado exitosamente!"]]);
	}

	// Update status.
	public function toggle(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, '')) {
			return $this->error403();
		}

		// Consultamos el registro a actualizar el estatus.
		$modulo = Modulo::find($id);
		$modulo->estatus = $modulo->estatus != "A" ? "A" : "I";
		$modulo->save();

		return json_encode(["status" => "success", "response" => ["message" => ""]]);
	}
}
