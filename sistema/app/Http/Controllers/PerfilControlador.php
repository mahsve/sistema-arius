<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Personal;
use App\Models\Usuario;
use Illuminate\Http\Request;

class PerfilControlador extends Controller
{
	// Atributos de la clase.
	public $lista_cedula = ["V", "E"];
	public $lista_prefijos = [
		"Móvil" => [
			"412",
			"414",
			"416",
			"424",
			"426",
		],
		"Hogar" => [
			"212",
			"232",
			"234",
			"235",
			"236",
			"237",
			"238",
			"239",
			"241",
			"242",
			"243",
			"244",
			"245",
			"246",
			"247",
			"248",
			"249",
			"251",
			"252",
			"253",
			"254",
			"255",
			"256",
			"257",
			"258",
			"259",
			"260",
			"261",
			"262",
			"263",
			"264",
			"265",
			"266",
			"267",
			"268",
			"269",
			"270",
			"271",
			"272",
			"273",
			"274",
			"275",
			"276",
			"277",
			"278",
			"279",
			"281",
			"282",
			"283",
			"285",
			"286",
			"287",
			"288",
			"291",
			"292",
			"293",
			"294",
			"295",
		],
	];

	public function formulario_perfil()
	{
		$perfil = session('personal'); // Datos del perfil

		// Separar la identificación en tipo y el número.
		$identificacion				= explode('-', $perfil->cedula);
		$tipo_identificacion	= $identificacion[0];
		$identificacion				= $identificacion[1];

		// Separar el teléfono 1 en prefijo y número.
		$telefono1	= explode(' ', $perfil->telefono1);
		$prefijo_t1	= substr($telefono1[0], 1, 3);
		$telefono1	= $telefono1[1];

		// Separar el teléfono 2 en prefijo y número.
		$telefono2	= "";
		$prefijo_t2	= "";
		if ($perfil->telefono2 != null) {
			$telefono2	= explode(' ', $perfil->telefono2);
			$prefijo_t2	= substr($telefono2[0], 1, 3);
			$telefono2	= $telefono2[1];
		}

		// Actualizamos los datos del objeto.
		$perfil->id		= $identificacion;
		$perfil->ti		= $tipo_identificacion;
		$perfil->pt1	= $prefijo_t1;
		$perfil->tl1	= $telefono1;
		$perfil->pt2	= $prefijo_t2;
		$perfil->tl2	= $telefono2;

		// Cargamos la vista con la información.
		$departamentos = Departamento::all();
		$cargos = Cargo::select('*')->where('iddepartamento', '=', $perfil->iddepartamento)->get();
		return view('perfil.perfil', [
			'perfil' => $perfil,
			'lista_cedula' => $this->lista_cedula,
			'lista_prefijos' => $this->lista_prefijos,
			'departamentos' => $departamentos,
			'cargos' => $cargos,
		]);
	}

	public function actualizar_datos(Request $request)
	{
		// Validamos.
		$message = "";
		if ($request->c_nombre_completo == "") {
			$message = "¡Ingrese el nombre completo!";
		} else if (strlen($request->c_nombre_completo) < 10) {
			$message = "¡El nombre debe tener al menos 10 caracteres!";
		} else if ($request->c_prefijo_telefono1 == "") {
			$message = "¡Seleccione el código del primer teléfono!";
		} else if ($request->c_telefono1 == "") {
			$message = "¡Ingrese el número del primer teléfono!";
		} else if (strlen($request->c_telefono1) < 8) {
			$message = "¡Ingrese el número del primer teléfono completo!";
		} else if ($request->c_telefono2 != "" and $request->c_prefijo_telefono2 == "") {
			$message = "¡Seleccione el código del segundo teléfono!";
		} else if ($request->c_prefijo_telefono2 != "" and $request->c_telefono2 == "") {
			$message = "¡Ingrese el número del segundo teléfono!";
		} else if ($request->c_prefijo_telefono2 != "" and strlen($request->c_telefono2) < 8) {
			$message = "¡Ingrese el número del segundo teléfono completo!";
		} else if ($request->c_correo_electronico == "") {
			$message = "¡Ingrese el correo electrónico!";
		} else if (filter_var($request->c_correo_electronico, FILTER_VALIDATE_EMAIL) === false) {
			$message = "¡Ingrese un correo electrónico válido!\nEj: usuario@email.com";
		} else if ($request->c_direccion == "") {
			$message = "¡Ingrese la dirección física!";
		} else if (strlen($request->c_direccion) < 10) {
			$message = "¡La dirección debe tener al menos 10 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Concatenamos la identificación y los teléfonos.
		$telefono1	= "(" . $request->c_prefijo_telefono1 . ") " . $request->c_telefono1;
		$telefonox	= "(" . $request->c_prefijo_telefono2 . ") " . $request->c_telefono2;
		$telefono2	= $telefonox != "() " ? $telefonox : "";

		// Creamos el nuevo registro del módulo.
		$personal = Personal::select('tb_personal.*', 'tb_cargos.iddepartamento')
			->join('tb_cargos', 'tb_personal.idcargo', 'tb_cargos.idcargo')
			->where('cedula', '=', session('personal')->cedula)
			->first();
		$personal->nombre = mb_convert_case($request->c_nombre_completo, MB_CASE_UPPER);
		$personal->telefono1 = $telefono1;
		$personal->telefono2 = $telefono2;
		$personal->correo = mb_convert_case($request->c_correo_electronico, MB_CASE_UPPER);
		$personal->direccion = mb_convert_case($request->c_direccion, MB_CASE_UPPER);
		$personal->referencia = mb_convert_case($request->c_referencia, MB_CASE_UPPER);
		$personal->save();

		// Enviamos un mensaje de exito al usuario.
		session(['personal' => $personal]);
		$response = ["status" => "success", "response" => ["message" => "¡Datos personales modificados exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	public function formulario_seguridad()
	{
		return view('perfil.seguridad');
	}

	public function actualizar_contrasena(Request $request)
	{
		// Validamos.
		$message = "";
		if ($request->nueva_contrasena == "") {
			$message = "¡Ingrese la nueva contraseña!";
		} else if (strlen($request->nueva_contrasena) < 6) {
			$message = "¡La nueva contraseña debe tener al menos 6 caracteres!";
		} else if ($request->repetir_contrasena == "") {
			$message = "¡Repita la nueva contraseña!";
		} else if ($request->nueva_contrasena != $request->repetir_contrasena) {
			$message = "¡Las contraseñas no coinciden!";
		} else if ($request->actual_contrasena == "") {
			$message = "¡Ingrese su contraseña actual para confirmar la acción!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos el usuario y verificamos que la contraseña actual sea correcta antes de permitir la actualización.
		$usuario = Usuario::find(auth()->user()->idusuario);
		if (!password_verify($request->actual_contrasena, $usuario->contrasena)) {
			$response = ["status" => "error", "response" => ["message" => "La contraseña actual es incorrecta"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Procedemos a actualizar la contraseña.
		$usuario->contrasena = password_hash($request->nueva_contrasena, PASSWORD_DEFAULT);
		$usuario->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Contraseña actualizada exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	public function actualizar_preguntas(Request $request)
	{
		// Validamos.
		$message = "";
		if ($request->pregunta_1 == "") {
			$message = "¡Ingrese la primera pregunta de seguridad!";
		} else if ($request->respuesta_1 == "") {
			$message = "¡Ingrese la respuesta a su pregunta de seguridad!";
		} else if ($request->pregunta_2 == "") {
			$message = "¡Ingrese la segunda pregunta de seguridad!";
		} else if ($request->respuesta_2 == "") {
			$message = "¡Ingrese la respuesta a su pregunta de seguridad!";
		} else if ($request->actual_contrasena == "") {
			$message = "¡Ingrese su contraseña actual para confirmar la acción!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos el usuario y verificamos que la contraseña actual sea correcta antes de permitir la actualización.
		$usuario = Usuario::find(auth()->user()->idusuario);
		if (!password_verify($request->actual_contrasena, $usuario->contrasena)) {
			$response = ["status" => "error", "response" => ["message" => "La contraseña actual es incorrecta"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Procedemos a actualizar la contraseña.
		$usuario->pregunta1 = mb_convert_case($request->pregunta_1, MB_CASE_UPPER);
		$usuario->respuesta1 = password_hash($request->respuesta_1, PASSWORD_DEFAULT);
		$usuario->pregunta2 = mb_convert_case($request->pregunta_2, MB_CASE_UPPER);
		$usuario->respuesta2 = password_hash($request->respuesta_2, PASSWORD_DEFAULT);
		$usuario->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Preguntas de seguridad actualizadas exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
