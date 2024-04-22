<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

// use Illuminate\Validation\Validator;

class ClienteControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 1;
	public $tipos_identificaciones = ["C" => "CÉDULA", "R" => "RIF"];
	public $lista_cedula = ["V", "E"];
	public $lista_rif = ["V", "E", "J", "P", "G"];
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

	// Display a listing of the resource. 
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$clientes = Cliente::all();
		return view('cliente.index', [
			'permisos' => $permisos,
			'clientes' => $clientes,
			'tipos_identificaciones' => $this->tipos_identificaciones,
		]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'create')) {
			return $this->error403();
		}

		// Cargamos la vista para registrar un nuevo cliente con los datos necesarios.
		return view('cliente.registrar', [
			'tipos_identificaciones' => $this->tipos_identificaciones,
			'lista_cedula' => $this->lista_cedula,
			'lista_rif' => $this->lista_rif,
			'lista_prefijos' => $this->lista_prefijos,
		]);
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
		if ($request->c_identificacion == "") {
			$texto_id	= $request->c_tipo_identificacion == "C" ? "Cédula" : "RIF";
			$message	= "¡Ingrese el número de $texto_id!";
		} else if ($request->c_tipo_identificacion == "C" and strlen($request->c_identificacion) < 7) {
			$message = "¡La cédula está incompleta!";
		} else if ($request->c_tipo_identificacion == "R" and strlen($request->c_identificacion) != 10) {
			$message = "¡El RIF está incompleto!";
		} else if ($request->c_nombre_completo == "") {
			$message = "¡Ingrese el Nombre/Razon social del cliente!";
		} else if ($request->c_nombre_completo != "" and strlen($request->c_nombre_completo) < 10) {
			$message = "¡El Nombre/Razon social debe tener al menos 10 caracteres!";
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
		} else if ($request->c_prefijo_telefono2 != "" and $request->c_telefono2 != "" and strlen($request->c_telefono2) < 8) {
			$message = "¡Ingrese el número del segundo teléfono completo!";
		} else if ($request->c_correo_electronico == "") {
			$message = "¡Ingrese el correo electrónico!";
		} else if (filter_var($request->c_correo_electronico, FILTER_VALIDATE_EMAIL) === false) {
			$message = "¡Ingrese un correo electrónico válido!";
		} else if ($request->c_direccion == "") {
			$message = "¡Ingrese la dirección física!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Concatenamos la identificación y los teléfonos.
		$identificacion	= $request->c_prefijo_identificacion . "-" . $request->c_identificacion;
		$telefono1			= "(" . $request->c_prefijo_telefono1 . ") " . $request->c_telefono1;
		$telefonox			= "(" . $request->c_prefijo_telefono2 . ") " . $request->c_telefono2;
		$telefono2			= $telefonox != "() " ? $telefonox : "";

		// Validamos que no este ya registrado.
		if (Cliente::find($identificacion)) {
			$response = ["status" => "error", "response" => ["message" => "¡Este cliente ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del cliente.
		$cliente = new Cliente();
		$cliente->identificacion = $identificacion;
		$cliente->tipo_identificacion = $request->c_tipo_identificacion;
		$cliente->nombre = mb_convert_case($request->c_nombre_completo, MB_CASE_UPPER);
		$cliente->telefono1 = $telefono1;
		$cliente->telefono2 = $telefono2;
		$cliente->correo = mb_convert_case($request->c_correo_electronico, MB_CASE_UPPER);
		$cliente->direccion = mb_convert_case($request->c_direccion, MB_CASE_UPPER);
		$cliente->referencia = mb_convert_case($request->c_referencia, MB_CASE_UPPER);
		$cliente->save();

		// Válidamos si viene la solicitud desde otro módulo o el módulo de cliente.
		if (isset($request->modulo)) {
			return response($cliente, 200)->header('Content-Type', 'text/json');
		} else {
			$response = ["status" => "success", "response" => ["message" => "¡Cliente registrado exitosamente!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}
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

		// Cargamos la vista para modificar un cliente con los datos necesarios.
		$cliente = Cliente::find($id);
		return view('cliente.modificar', [
			'cliente' => $cliente,
			'tipos_identificaciones' => $this->tipos_identificaciones,
			'lista_cedula' => $this->lista_cedula,
			'lista_rif' => $this->lista_rif,
			'lista_prefijos' => $this->lista_prefijos,
		]);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'update')) {
			return $this->error403();
		}

		// Validamos.
		$message = "";
		if ($request->c_nombre_completo == "") {
			$message = "¡Ingrese el Nombre/Razon social del cliente!";
		} else if ($request->c_nombre_completo != "" and strlen($request->c_nombre_completo) < 10) {
			$message = "¡El Nombre/Razon social debe tener al menos 10 caracteres!";
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
		} else if ($request->c_prefijo_telefono2 != "" and $request->c_telefono2 != "" and strlen($request->c_telefono2) < 8) {
			$message = "¡Ingrese el número del segundo teléfono completo!";
		} else if ($request->c_correo_electronico == "") {
			$message = "¡Ingrese el correo electrónico!";
		} else if (filter_var($request->c_correo_electronico, FILTER_VALIDATE_EMAIL) === false) {
			$message = "¡Ingrese un correo electrónico válido!";
		} else if ($request->c_direccion == "") {
			$message = "¡Ingrese la dirección física!";
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

		// Consultamos y modificamos el registro del cliente.
		$cliente = Cliente::find($id);
		$cliente->nombre = mb_convert_case($request->c_nombre_completo, MB_CASE_UPPER);
		$cliente->telefono1 = $telefono1;
		$cliente->telefono2 = $telefono2;
		$cliente->correo = mb_convert_case($request->c_correo_electronico, MB_CASE_UPPER);
		$cliente->direccion = mb_convert_case($request->c_direccion, MB_CASE_UPPER);
		$cliente->referencia = mb_convert_case($request->c_referencia, MB_CASE_UPPER);
		$cliente->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Cliente modificado exitosamente!"]];
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
			return $this->error403();
		}

		// Consultamos el registro a actualizar el estatus.
		$cliente = Cliente::find($id);
		$cliente->estatus = $cliente->estatus != "A" ? "A" : "I";
		$cliente->save();

		// Enviamos mensaje de exito al usuario.
		$message	= $cliente->estatus == "A" ? "¡Cliente activado exitosamente!" : "¡Cliente inactivado exitosamente!";
		$response = ["status" => "success", "response" => ["message" => $message]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
