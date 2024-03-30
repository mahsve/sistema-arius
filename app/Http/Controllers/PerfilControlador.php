<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class PerfilControlador extends Controller
{
	public function formulario_perfil()
	{
		return view('perfil.perfil');
	}

	public function actualizar_contrasena(Request $request)
	{
		// Validamos.
		if ($request->nueva_contrasena == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese la nueva contraseña"]]);
		} else if (strlen($request->nueva_contrasena) < 6) {
			return json_encode(["status" => "error", "response" => ["message" => "La nueva contraseña debe tener al menos 6 caracteres"]]);
		} else if ($request->repetir_contrasena == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Repita la nueva contraseña"]]);
		} else if ($request->nueva_contrasena != $request->repetir_contrasena) {
			return json_encode(["status" => "error", "response" => ["message" => "Las contraseñas no coinciden"]]);
		} else if ($request->actual_contrasena == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese su contraseña actual para confirmar la acción"]]);
		}

		// Consultamos el usuario y verificamos que la contraseña actual sea correcta antes de permitir la actualización.
		$usuario = Usuario::find(auth()->user()->idusuario);
		if (!password_verify($request->actual_contrasena, $usuario->contrasena)) {
			return json_encode(["status" => "error", "response" => ["message" => "La contraseña actual es incorrecta"]]);
		}

		// Procedemos a actualizar la contraseña.
		$usuario->contrasena = password_hash($request->nueva_contrasena, PASSWORD_DEFAULT);
		$usuario->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Contraseña actualizada exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	public function formulario_seguridad()
	{
		return view('perfil.seguridad');
	}

	public function actualizar_preguntas(Request $request)
	{
		// Validamos.
		if ($request->pregunta_1 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese la primera pregunta de seguridad"]]);
		} else if ($request->respuesta_1 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese la respuesta a su pregunta de seguridad"]]);
		} else if ($request->pregunta_2 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese la segunda pregunta de seguridad"]]);
		} else if ($request->respuesta_2 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese la respuesta a su pregunta de seguridad"]]);
		} else if ($request->actual_contrasena == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese su contraseña actual para confirmar la acción"]]);
		}

		// Consultamos el usuario y verificamos que la contraseña actual sea correcta antes de permitir la actualización.
		$usuario = Usuario::find(auth()->user()->idusuario);
		if (!password_verify($request->actual_contrasena, $usuario->contrasena)) {
			return json_encode(["status" => "error", "response" => ["message" => "La contraseña actual es incorrecta"]]);
		}

		// Procedemos a actualizar la contraseña.
		$usuario->pregunta1 = $request->pregunta_1;
		$usuario->respuesta1 = $request->respuesta_1;
		$usuario->pregunta2 = $request->pregunta_2;
		$usuario->respuesta2 = $request->respuesta_2;
		$usuario->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Preguntas de seguridad actualizadas exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
