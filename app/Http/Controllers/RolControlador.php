<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Rol;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 33;

	// Display a listing of the resource. 
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Permitimos el acceso en caso de tener permisos.
		$roles = Rol::all();
		$modulos = []; // Variable vacía para ordenar los módulos y dentro sus servicios asosciados [Matríz].
		$servicios = Servicio::select('tb_servicios.*', 'tb_modulos.orden', 'tb_modulos.icono', 'tb_modulos.modulo', 'tb_modulos.estatus as m_estatus')
			->join('tb_modulos', 'tb_servicios.idmodulo', 'tb_modulos.idmodulo')
			->orderBy('tb_modulos.orden', 'ASC')
			->get();

		// Recorremos todos los servicios encontrados y su módulo correspondiente.
		foreach ($servicios as $servicio) {
			// Creamos en el primer nivel la información del módulo y dentro un nuevo arreglo vacío para guardar los servicios.
			if (!isset($modulos[$servicio->orden])) {
				$modulos[$servicio->orden] = [
					'idmodulo' => $servicio->idmodulo,
					'icono' => $servicio->icono,
					'modulo' => $servicio->modulo,
					'servicios' => [],
					'estatus' => $servicio->m_estatus,
				];
			}

			// Agregamos la info del servicio dentro del arreglo.
			$modulos[$servicio->orden]['servicios'][] = $servicio;
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$modulos = json_decode(json_encode($modulos, JSON_FORCE_OBJECT));
		return view('roles.index', [
			'permisos' => $permisos,
			"roles" => $roles,
			"modulos" => $modulos,
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
		if ($request->c_rol == "") {
			$message = "¡Ingrese el nombre del rol!";
		} else if (strlen($request->c_rol) < 3) {
			$message = "¡El rol debe tener al menos 3 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_roles')
			->select('rol')
			->where('rol', '=', mb_convert_case($request->c_rol, MB_CASE_UPPER))
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este rol ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del rol.
		$rol = new Rol();
		$rol->rol = mb_convert_case($request->c_rol, MB_CASE_UPPER);
		$rol->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Rol registrado exitosamente!"]];
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
		$rol = Rol::find($id);
		return response($rol, 200)->header('Content-Type', 'text/json');
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
		if ($request->c_rol == "") {
			$message = "¡Ingrese el nombre del rol!";
		} else if (strlen($request->c_rol) < 3) {
			$message = "¡El rol debe tener al menos 3 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existente = DB::table('tb_roles')
			->select('rol')
			->where('rol', '=', mb_convert_case($request->c_rol, MB_CASE_UPPER))
			->where('idrol', '!=', $id)
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este rol ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos y modificamos el registro del rol.
		$rol = Rol::find($id);
		$rol->rol = mb_convert_case($request->c_rol, MB_CASE_UPPER);
		$rol->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Rol modificado exitosamente!"]];
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
		$rol = Rol::find($id);
		$rol->estatus = $rol->estatus != "A" ? "A" : "I";
		$rol->save();

		// Enviamos un mensaje de exito al usuario.
		$message	= $rol->estatus == "A" ? "¡Estatus cambiado a activo!" : "¡Estatus cambiado a inactivo!";
		$response = ["status" => "success", "response" => ["message" => $message]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
