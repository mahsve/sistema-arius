<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

// use Illuminate\Validation\Validator;

class ClienteControlador extends Controller
{
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
		$clientes = Cliente::all();
		return view('cliente.index', ['clientes' => $clientes, 'tipos_identificaciones' => $this->tipos_identificaciones]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		return view('cliente.registrar', ['tipos_identificaciones' => $this->tipos_identificaciones, 'lista_cedula' => $this->lista_cedula, 'lista_rif' => $this->lista_rif, 'lista_prefijos' => $this->lista_prefijos]);
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Validaciones
		$request->validate([
		]);

		// Validamos.
		if ($request->c_identificacion == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el número de " . ($request->c_tipo_identificacion == "C" ? "Cédula" : "RIF")]]);
		} else if ($request->c_tipo_identificacion == "C" and strlen($request->c_identificacion) < 7) {
			return json_encode(["status" => "error", "response" => ["message" => "La cédula está incompleta"]]);
		} else if ($request->c_tipo_identificacion == "R" and strlen($request->c_identificacion) != 10) {
			return json_encode(["status" => "error", "response" => ["message" => "El RIF está incompleto"]]);
		} else if ($request->c_nombre_completo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el Nombre/Razon social del cliente"]]);
		} else if ($request->c_nombre_completo != "" and strlen($request->c_nombre_completo) < 10) {
			return json_encode(["status" => "error", "response" => ["message" => "El Nombre/Razon social debe tener al menos 10 caracteres"]]);
		} else if ($request->c_prefijo_telefono1 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el código del primer teléfono"]]);
		} else if ($request->c_telefono1 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el número del primer teléfono"]]);
		} else if (strlen($request->c_telefono1) < 8) {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el número del primer teléfono completo"]]);
		} else if ($request->c_telefono2 != "" and $request->c_prefijo_telefono2 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el código del segundo teléfono"]]);
		} else if ($request->c_prefijo_telefono2 != "" and $request->c_telefono2 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el número del segundo teléfono"]]);
		} else if ($request->c_prefijo_telefono2 != "" and $request->c_telefono2 != "" and strlen($request->c_telefono2) < 8) {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el número del segundo teléfono completo"]]);
		} else if ($request->c_correo_electronico == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el correo electrónico"]]);
		} else if (filter_var($request->c_correo_electronico, FILTER_VALIDATE_EMAIL) === false) {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese un correo electrónico válido"]]);
		} else if ($request->c_direccion == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese la dirección física"]]);
		}

		// Concatenamos la identificación y los teléfonos.
		$identificacion = $request->c_prefijo_identificacion . "-" . $request->c_identificacion;
		$telefono1 = "(" . $request->c_prefijo_telefono1 . ") " . $request->c_telefono1;
		$telefono2 = $request->c_telefono2 != "" ? "(" . $request->c_prefijo_telefono2 . ") " . $request->c_telefono2 : NULL;

		// Validamos que no este ya registrado.
		if (Cliente::find($identificacion)) {
			return json_encode(["status" => "error", "response" => ["message" => "Este cliente ya se encuentra registrado"]]);
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
			$cliente = Cliente::select('*')
				->where('identificacion', '=', $identificacion)
				->first();
			return response($cliente, 200)->header('Content-Type', 'text/json');
		} else {
			return json_encode(["status" => "success", "response" => ["message" => "Cliente registrado exitosamente"]]);
		}
	}

	// Display the specified resource. 
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
		$cliente = Cliente::find($id);
		return view('cliente.modificar', ['cliente' => $cliente, 'tipos_identificaciones' => $this->tipos_identificaciones, 'lista_cedula' => $this->lista_cedula, 'lista_rif' => $this->lista_rif, 'lista_prefijos' => $this->lista_prefijos]);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Validamos.
		if ($request->c_nombre_completo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el Nombre/Razon social del cliente"]], JSON_UNESCAPED_UNICODE);
		} else if ($request->c_nombre_completo != "" and strlen($request->c_nombre_completo) < 10) {
			return json_encode(["status" => "error", "response" => ["message" => "El Nombre/Razon social debe tener al menos 10 caracteres"]], JSON_UNESCAPED_UNICODE);
		} else if ($request->c_prefijo_telefono1 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el código del primer teléfono"]], JSON_UNESCAPED_UNICODE);
		} else if ($request->c_telefono1 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el número del primer teléfono"]], JSON_UNESCAPED_UNICODE);
		} else if (strlen($request->c_telefono1) < 8) {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el número del primer teléfono completo"]], JSON_UNESCAPED_UNICODE);
		} else if ($request->c_telefono2 != "" and $request->c_prefijo_telefono2 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el código del segundo teléfono"]], JSON_UNESCAPED_UNICODE);
		} else if ($request->c_prefijo_telefono2 != "" and $request->c_telefono2 == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el número del segundo teléfono"]], JSON_UNESCAPED_UNICODE);
		} else if ($request->c_prefijo_telefono2 != "" and $request->c_telefono2 != "" and strlen($request->c_telefono2) < 8) {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el número del segundo teléfono completo"]], JSON_UNESCAPED_UNICODE);
		} else if ($request->c_correo_electronico == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el correo electrónico"]], JSON_UNESCAPED_UNICODE);
		} else if (filter_var($request->c_correo_electronico, FILTER_VALIDATE_EMAIL) === false) {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese un correo electrónico válido"]], JSON_UNESCAPED_UNICODE);
		} else if ($request->c_direccion == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese la dirección física"]], JSON_UNESCAPED_UNICODE);
		}

		// Concatenamos los telefonos.
		$telefono1 = "(" . $request->c_prefijo_telefono1 . ") " . $request->c_telefono1;
		$telefono2 = $request->c_telefono2 != "" ? "(" . $request->c_prefijo_telefono2 . ") " . $request->c_telefono2 : NULL;

		// Consultamos y modificamos el registro del cliente.
		$cliente = Cliente::find($id);
		$cliente->nombre = mb_convert_case($request->c_nombre_completo, MB_CASE_UPPER);
		$cliente->telefono1 = $telefono1;
		$cliente->telefono2 = $telefono2;
		$cliente->correo = mb_convert_case($request->c_correo_electronico, MB_CASE_UPPER);
		$cliente->direccion = mb_convert_case($request->c_direccion, MB_CASE_UPPER);
		$cliente->referencia = mb_convert_case($request->c_referencia, MB_CASE_UPPER);
		$cliente->save();

		return json_encode(["status" => "success", "response" => ["message" => "Cliente modificado exitosamente"]]);
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(string $id)
	{
		$cliente = Cliente::find($id);
		$cliente->estatus = $cliente->estatus != "A" ? "A" : "I";
		$cliente->save();

		return json_encode(["status" => "success", "response" => ["message" => ""]]);
	}
}
