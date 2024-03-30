<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SesionControlador extends Controller
{
	public function formulario_iniciar_sesion()
	{
		return view('sesion.iniciar_sesion');
	}

	public function iniciar_sesion(Request $request)
	{
		// Consultamos la información del usuario en la base de datos.
		$usuario = Usuario::select('tb_personal.*', 'tb_usuarios.usuario', 'tb_usuarios.idusuario', 'tb_usuarios.estatus', 'tb_usuarios.contrasena')
			->join('tb_personal', 'tb_usuarios.cedula', '=', 'tb_personal.cedula')
			->where('usuario', '=', $request->usuario)
			->first();

		// Validamos si encontró el usuario solicitado.
		if (!$usuario) {
			return json_encode(["status" => "error", "response" => ["message" => "¡El usuario no se encuentra registrado!"]]);
		}

		// Comprobamos que la contraseña sea correcta.
		if (!password_verify($request->contrasena, $usuario->contrasena)) {
			return json_encode(["status" => "error", "response" => ["message" => "¡La contraseña ingresada es incorrecta!"]]);
		}

		// Iniciamos la sesión.
		Auth::login($usuario);
		$request->session()->regenerate();
		session(['datos_personales', $usuario]);
		$response = ["status" => "success", "response" => ["message" => "¡Sesión iniciada exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	public function cerrar_sesion(Request $request)
	{
		// Finalizamos la sesión.
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		$response = ["status" => "success", "response" => ["message" => "¡Sesión cerrada exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	public function formulario_recuperar()
	{
		return view('sesion.recuperar_cuenta');
	}
}
