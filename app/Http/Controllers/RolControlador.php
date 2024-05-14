<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\RolModulo;
use App\Models\RolServicio;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolControlador extends Controller
{
	use SeguridadControlador;
	use RegistroBitacoraControlador;

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

		$servicios = Servicio::select(
			DB::raw("CASE WHEN `idservicio_raiz` IS NOT NULL THEN CONCAT(`idservicio_raiz`,'_',`idservicio`) ELSE CONCAT(`idservicio`,'_',0) END AS 'group'"),
			'tb_servicios.*',
			'tb_modulos.orden as morden',
			'tb_modulos.icono',
			'tb_modulos.modulo',
			'tb_modulos.estatus as m_estatus'
		)->join('tb_modulos', 'tb_servicios.idmodulo', 'tb_modulos.idmodulo')
			->orderBy('tb_modulos.orden', 'ASC')
			->orderBy('group', 'ASC')
			->get();

		// Recorremos todos los servicios encontrados y su módulo correspondiente.
		foreach ($servicios as $servicio) {
			// Creamos en el primer nivel la información del módulo y dentro un nuevo arreglo vacío para guardar los servicios.
			if (!isset($modulos[$servicio->morden])) {
				$modulos[$servicio->morden] = [
					'idmodulo' => $servicio->idmodulo,
					'icono' => $servicio->icono,
					'modulo' => $servicio->modulo,
					'servicios' => [],
					'estatus' => $servicio->m_estatus,
				];
			}

			// Agregamos la info del servicio dentro del arreglo.
			$modulos[$servicio->morden]['servicios'][] = $servicio;
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

		// Ejecutamos una nueva transacción.
		try {
			// Recorremos los módulos y actualizamos su orden según lo descrito por el usuario.
			DB::transaction(function () use ($request) {
				// Creamos el nuevo registro del rol.
				$rol = new Rol();
				$rol->rol = mb_convert_case($request->c_rol, MB_CASE_UPPER);
				$rol->save();

				// Registramos todos los módulos en el rol.
				for ($i = 0; $i < count($request->modulo); $i++) {
					$rol_modulo = new RolModulo();
					$rol_modulo->idmodulo = $request->modulo[$i];
					$rol_modulo->idrol = $rol->idrol;
					$rol_modulo->save();
				}

				// Registramos todos los servicios en el rol.
				for ($i = 0; $i < count($request->servicio); $i++) {
					$rol_servicio = new RolServicio();
					$rol_servicio->idservicio = $request->servicio[$i];
					$rol_servicio->idrol = $rol->idrol;
					$rol_servicio->save();
				}
			});
		} catch (\Throwable $th) {
			$response = ["status" => "error", "response" => ["message" => "¡Ocurrió un error al registrar el rol con los módulos y los servicios!", "error" => $th->getMessage()]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Enviamos un mensaje de exito al usuario.
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
		$rol->modulos = RolModulo::where('idrol', '=', $rol->idrol)->get();
		$rol->servicios = RolServicio::where('idrol', '=', $rol->idrol)->get();
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

		// Ejecutamos una nueva transacción.
		try {
			// Recorremos los módulos y actualizamos su orden según lo descrito por el usuario.
			DB::transaction(function () use ($request, $id) {
				// Consultamos y modificamos el registro del rol.
				$rol = Rol::find($id);
				$rol->rol = mb_convert_case($request->c_rol, MB_CASE_UPPER);
				$rol->save();
				DB::table('tb_rol_modulo')->where('idrol', '=', $id)->delete();
				DB::table('tb_rol_servicio')->where('idrol', '=', $id)->delete();

				// Registramos todos los módulos en el rol.
				if (isset($request->modulo)) {
					for ($i = 0; $i < count($request->modulo); $i++) {
						$rol_modulo = new RolModulo();
						$rol_modulo->idmodulo = $request->modulo[$i];
						$rol_modulo->idrol = $rol->idrol;
						$rol_modulo->save();
					}
				}

				// Registramos todos los servicios en el rol.
				if (isset($request->servicio)) {
					for ($i = 0; $i < count($request->servicio); $i++) {
						$rol_servicio = new RolServicio();
						$rol_servicio->idservicio = $request->servicio[$i];
						$rol_servicio->idrol = $rol->idrol;
						$rol_servicio->save();
					}
				}
			});
		} catch (\Throwable $th) {
			$response = ["status" => "error", "response" => ["message" => "¡Ocurrió un error al modificar el rol con los módulos y los servicios!", "error" => $th->getMessage()]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

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
