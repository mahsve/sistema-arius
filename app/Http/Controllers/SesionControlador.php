<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesionControlador extends Controller
{
	public function formulario_iniciar_sesion()
	{
		// session()->forget('usuario');
		return view('sesion.iniciar_sesion');
	}

	public function iniciar_sesion(Request $request)
	{
		// Consultamos la información del usuario en la base de datos.
		$usuario = Usuario::where('usuario', '=', $request->usuario)->first();

		// Validamos si encontró el usuario solicitado.
		if (!$usuario) {
			$response = ["status" => "error", "response" => ["message" => "¡El usuario no se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Verificamos el estatus del usuario antes de continuar.
		if ($usuario->estatus != 'A' and $usuario->estatus != "P") {
			if ($usuario->estatus == "B") {
				$response = ["status" => "error", "response" => ["type" => 'swal', "title" => "¡Su usuario se encuentra bloqueado!", "message" => "Intente restablecer contraseña para desbloquear o hable con el administrador."]];
			} else if ($usuario->estatus == "I") {
				$response = ["status" => "error", "response" => ["type" => 'swal', "title" => "¡Su usuario se encuentra inactivo!", "message" => "Hable con el administrador para habilitarlo nuevamente."]];
			}
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Comprobamos que la contraseña sea correcta.
		if (!password_verify($request->contrasena, $usuario->contrasena)) {
			$intentos = intval($usuario->intentos) + 1;
			if ($intentos < 3) {
				$restante = 3 - $intentos;
				$response = ["status" => "error", "response" => ["type" => 'swal', "title" => "¡La contraseña incorrecta!", "message" => "Tiene $restante intentos más antes de bloquear su usuario."]];
				$usuario->intentos = $intentos;
				$usuario->save();
			} else if ($intentos >= 3) {
				$response = ["status" => "error", "response" => ["type" => 'swal', "title" => "¡Su usuario se encuentra bloqueado!", "message" => "Intente restablecer contraseña para desbloquear o hable con el administrador."]];
				$usuario->intentos = 0;
				$usuario->estatus = 'B'; // Bloqueado por intentos fallidos.
				$usuario->save();
			}
			// Retornamos mensaje de error al usuario al intentar iniciar sesión.
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// // Si ocurrió algún error de contraseña invalida, reseteamos los intentos a 0.
		// $usuario->intentos = 0;
		// $usuario->save();

		// Iniciamos la sesión.
		Auth::login($usuario);
		$request->session()->regenerate();
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

	public function recuperar_cuenta(Request $request)
	{
		// Consultamos la información del usuario en la base de datos.
		$usuario = Usuario::where('usuario', '=', $request->usuario)->first();
		if (!$usuario) {
			$response = ["status" => "error", "response" => ["message" => "¡El usuario no se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Verificamos el estatus del usuario antes de continuar [Inactivos por el administrador no puede acceder ni restablecer contraseña].
		if ($usuario->estatus == "I") {
			$response = ["status" => "error", "response" => ["type" => 'swal', "title" => "¡Su usuario se encuentra inactivo!", "message" => "Hable con el administrador para habilitarlo nuevamente."]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Verificamos que haya definido las preguntas y las respuestas de seguridad.
		if (
			$usuario->pregunta1 == null and
			$usuario->respuesta1 == null and
			$usuario->pregunta2 == null and
			$usuario->respuesta1 == null
		) {
			$response = ["status" => "error", "response" => ["type" => "swal", "title" => "¡Preguntas de seguridad no definidas!", "message" => "No podemos verificar su identidad porque no cuenta con las preguntas de seguridad definidas en el sistema<br>Hable con el administrador"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Retornamos los datos encontrados.
		session_start();
		$_SESSION['usuario'] = $usuario;
		$response = ["status" => "success", "response" => ["message" => "¡Usuario encontrado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	public function formulario_preguntas()
	{
		session_start();
		if (isset($_SESSION['usuario']) and !empty($_SESSION['usuario'])) { // Verifica si ya se consultó el usuario para restablecer contraseña en caso de intentar ingresar al formulario de las preguntas de seguridad.
			return view('sesion.preguntas_seguridad', ['usuario' => $_SESSION['usuario']]);
		} else {
			redirect()->route('session.recover'); // De lo contario lo redirecciona al formulario para consultar primero el usuario antes de continuar.
		}
	}

	public function verificar_respuestas(Request $request)
	{
		session_start();
		// Verificamos que haya consultado la información del usuario primero para verificar las preguntas de seguridad, procede a denegar el acceso y redireccionar.
		if (isset($_SESSION['usuario']) and !empty($_SESSION['usuario'])) {
			$response = ["status" => "error", "response" => ["type" => "swal", "title" => "¡Usuario no validado!", "message" => "Usted debe validar primero su usuario para contestar las preguntas de seguridad.", "reload" => true]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Procedemos a verificar las respuestas obtenidas del formulario.
		$usuario = $_SESSION['usuario']; // Capturamos el usuario guardado en la variable global SESSION.
		if (!password_verify($request->respuesta1, $usuario->respuesta1) or !password_verify($request->respuesta2, $usuario->respuesta2)) {
			$response = ["status" => "error", "response" => ["type" => 'swal', "title" => "¡Las respuestas incorrectas!", "message" => "Las respuestas que ingreso son incorrectas, por favor intente nuevamente.", "reload" => true]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Enviamos mensaje de exito al usuario.
		$_SESSION["nueva_contrasena"] = true; // Con esta variable verificamos que se validó el usuario y las preguntas de seguridad y que estan correctas.
		$response = ["status" => "success", "response" => ["message" => "¡Ahora puede restablecer su contraseña!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	public function formulario_contrasena()
	{
		session_start();

		// Verificamos si ya se validaron las preguntas de seguridad antes de permitir restablecer contraseña.
		if (isset($_SESSION['nueva_contrasena']) and !empty($_SESSION['nueva_contrasena'])) {
			return view('sesion.restablecer_contrasena', ['usuario' => $_SESSION['usuario']]);
		}

		// Si no se validaron todavía se procede a redirecionar al formulario de preguntas de seguridad en caso de que ya se haya consultado los datos del usuario.
		if (isset($_SESSION['usuario']) and !empty($_SESSION['usuario'])) {
			return redirect()->route('session.questions');
		}

		// Si no se ha validado todavía las preguntas de seguridad y el usuario procede a redireccionarlo al formulario para consultar el usuario primero.
		return redirect()->route('session.recover');
	}

	public function restablecer(Request $request)
	{
		session_start();
		// Verificamos si no hay usuario consultado y las preguntas de seguridad no se validaron, procede a enviar mensaje de error.
		if (!isset($_SESSION['usuario']) or empty($_SESSION['usuario']) or !isset($_SESSION['nueva_contrasena']) or empty($_SESSION['nueva_contrasena'])) {
			$response = ["status" => "error", "response" => ["type" => "swal", "title" => "¡Usuario no validado!", "message" => "Usted debe validar primero su usuario y las preguntas de seguridad antes de restablecer contraseña"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos.
		$message = "";
		if ($request->contrasena1 == "") {
			$message = "¡Ingrese su nueva contraseña!";
		} else if (strlen($request->contrasena1) < 6) {
			$message = "¡La contraseña debe tener al menos 6 caracteres!";
		} else if ($request->contrasena2 == "") {
			$message = "¡Por favor repita la contraseña!";
		} else if ($request->contrasena1 != $request->contrasena2) {
			$message = "¡Las contraseñas ingresadas no coinciden!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos el usuario que vamos a actualizar.
		$usuario = Usuario::find($_SESSION['usuario']->idusuario);
		$usuario->contrasena = password_hash($request->contrasena1, PASSWORD_DEFAULT);
		$usuario->estatus = "A";
		$usuario->save();

		// Destruimos todos los datos de las sessiones y mandamos mensaje de exito al usuario.
		session_destroy();
		$response = ["status" => "success", "response" => ["message" => "¡Contraseña restablecida exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
