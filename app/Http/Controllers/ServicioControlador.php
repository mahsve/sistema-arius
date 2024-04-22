<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicioControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 29;

	// Display a listing of the resource. 
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$modulos = Modulo::all();
		$servicios = DB::table('tb_servicios')
			->select('tb_servicios.*', 'tb_modulos.modulo')
			->join('tb_modulos', 'tb_servicios.idmodulo', 'tb_modulos.idmodulo')
			->get();
		return view('servicio.index', [
			'permisos' => $permisos,
			"modulos" => $modulos,
			"servicios" => $servicios,
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
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el módulo"]]);
		} else if ($request->c_servicio == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del servicio"]]);
		} else if (strlen($request->c_servicio) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El servicio debe tener al menos 3 caracteres"]]);
		}

		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existente = DB::table('tb_servicios')
			->select('idservicio')
			->where('servicio', '=', mb_convert_case($request->c_servicio, MB_CASE_UPPER))
			->where('idmodulo', '=', $request->c_modulo)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este servicio ya se encuentra registrado"]]);
		}

		// Creamos el nuevo registro del servicio.
		$servicio = new Servicio();
		$servicio->servicio = mb_convert_case($request->c_servicio, MB_CASE_UPPER); // Transformamos a mayuscula.
		$servicio->enlace = "";
		$servicio->visible = 0;
		$servicio->idmodulo = $request->c_modulo;
		$servicio->save();

		return json_encode(["status" => "success", "response" => ["message" => "Servicio registrado exitosamente"]]);
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
		$servicio = Servicio::find($id);
		return json_encode($servicio);
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
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el módulo"]]);
		} else if ($request->c_servicio == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del servicio"]]);
		} else if (strlen($request->c_servicio) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El servicio debe tener al menos 3 caracteres"]]);
		}

		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existente = DB::table('tb_servicios')
			->select('idservicio')
			->where('servicio', '=', mb_convert_case($request->c_servicio, MB_CASE_UPPER))
			->where('idmodulo', '=', $request->c_modulo)
			->where('idservicio', '!=', $id)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este servicio ya se encuentra registrado"]]);
		}

		// Consultamos y modificamos el registro del servicio.
		$servicio = Servicio::find($id);
		$servicio->servicio = mb_convert_case($request->c_servicio, MB_CASE_UPPER);
		$servicio->enlace = "";
		$servicio->visible = 0;
		$servicio->idmodulo = $request->c_modulo;
		$servicio->save();

		return json_encode(["status" => "success", "response" => ["message" => "Servicio modificado exitosamente"]]);
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
		$servicio = Servicio::find($id);
		$servicio->estatus = $servicio->estatus != "A" ? "A" : "I";
		$servicio->save();

		return json_encode(["status" => "success", "response" => ["message" => ""]]);
	}
}
