<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioControlador extends Controller
{
	use SeguridadControlador;
	use RegistroBitacoraControlador;

	// Atributos de la clase.
	public $idservicio = 54;

	// Display a listing of the resource.
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$usuarios = Usuario::select('tb_usuarios.*', 'tb_personal.nombre', 'tb_roles.rol')
			->join('tb_personal', 'tb_usuarios.cedula', 'tb_personal.cedula')
			->join('tb_roles', 'tb_usuarios.idrol', 'tb_roles.idrol')
			->get();
		$personal = Personal::select('tb_personal.cedula', 'tb_personal.nombre')
			->leftjoin('tb_usuarios', 'tb_personal.cedula', 'tb_usuarios.cedula')
			->whereNull('tb_usuarios.usuario')
			->get();
		$roles = Rol::all();
		return view('usuario.index', [
			'permisos' => $permisos,
			"usuarios" => $usuarios,
			'personal' => $personal,
			'roles' => $roles,
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
		if ($request->c_cedula == "") {
			$message = "¡Seleccione la persona a crear el usuario!";
		} else if ($request->c_rol == "") {
			$message = "¡Seleccione el rol de este usuario!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = Usuario::where('cedula', '=', $request->c_cedula)->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este personal ya tiene un usuario registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del usuario.
		$usuario = new Usuario();
		$usuario->cedula = $request->c_cedula;
		$usuario->idrol = $request->c_rol;
		$usuario->usuario = mb_convert_case(str_replace('-', '', $request->c_cedula), MB_CASE_LOWER);
		$usuario->contrasena = password_hash($usuario->usuario, PASSWORD_DEFAULT);
		$usuario->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Usuario registrado exitosamente!"]];
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
		$usuario = Usuario::select('tb_usuarios.*', 'tb_personal.nombre')
			->join('tb_personal', 'tb_usuarios.cedula', 'tb_personal.cedula')
			->where('idusuario', '=', $id)
			->first();
		return response($usuario, 200)->header('Content-Type', 'text/json');
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
			$message = "¡Seleccione el rol de este usuario!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos los datos del registro del usuario.
		$usuario = Usuario::find($id);
		$usuario->idrol = $request->c_rol;
		$usuario->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Usuario modificado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Remove the specified resource from storage.
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(Request $request, string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'toggle')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para cambiar el estatus!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos el registro a actualizar el estatus.
		$usuario = Usuario::find($id);
		if ($request->c_estatus == "1") {
			$usuario->contrasena = password_hash(mb_convert_case(str_replace('-', '', $usuario->cedula), MB_CASE_LOWER), PASSWORD_DEFAULT);
			$usuario->pregunta1 = null;
			$usuario->respuesta1 = null;
			$usuario->pregunta2 = null;
			$usuario->respuesta2 = null;
			$usuario->estatus = "P";
			$message = "¡Usuario restablecido exitosamente!";
		} else if ($request->c_estatus == "2") {
			if ($usuario->pregunta1 != null and $usuario->respuesta1 != null and $usuario->pregunta2 != null and $usuario->respuesta2 != null) {
				$usuario->estatus = $usuario->estatus != "A" ? "A" : "I";
				$message = $usuario->estatus == "A" ? "¡Usuario habilitado exitosamente!" : "¡Usuario deshabilitado exitosamente!";
			} else {
				$usuario->estatus = $usuario->estatus != "P" ? "P" : "I";
				$message = $usuario->estatus == "A" ? "¡Usuario habilitado exitosamente!" : "¡Usuario deshabilitado exitosamente!";
			}
		}
		$usuario->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => $message, "data" => ["estatus" => $usuario->estatus]]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
