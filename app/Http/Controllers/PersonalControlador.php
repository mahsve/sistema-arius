<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Personal;
use App\Models\Cargo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class PersonalControlador extends Controller
{
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

	// Display a listing of the resource. 
	public function index()
	{
		$personal = DB::table('tb_personal')
			->join('tb_usuarios', 'tb_personal.cedula', '=', 'tb_usuarios.cedula')
			->select('tb_personal.*', 'tb_usuarios.usuario')
			->get();
		return view('personal.index', ['personal' => $personal]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		$departamentos = Departamento::all();
		return view('personal.registrar', ['lista_cedula' => $this->lista_cedula, 'lista_prefijos' => $this->lista_prefijos, 'departamentos' => $departamentos]);
	}

	public function consultar_cargos(string $id)
	{
		$cargos = DB::table('tb_cargos')
			->where('iddepartamento', '=', $id)
			->get();
		return json_encode($cargos);
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Validamos.
		if ($request->c_identificacion == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el número de Cédula"]]);
		} else if (strlen($request->c_identificacion) != 8) {
			return json_encode(["status" => "error", "response" => ["message" => "La cédula está incompleta"]]);
		} else if ($request->c_nombre_completo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre completo"]]);
		} else if ($request->c_nombre_completo != "" and strlen($request->c_nombre_completo) < 10) {
			return json_encode(["status" => "error", "response" => ["message" => "El nombre debe tener al menos 10 caracteres"]]);
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
		} else if ($request->c_departamento == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el departamento"]]);
		} else if ($request->c_cargo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el cargo"]]);
		} else if ($request->c_direccion == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese la dirección física"]]);
		}

		// Concatenamos la identificación y los teléfonos.
		$identificacion = $request->c_prefijo_identificacion . "-" . $request->c_identificacion;
		$telefono1 = "(" . $request->c_prefijo_telefono1 . ") " . $request->c_telefono1;
		$telefono2 = $request->c_telefono2 != "" ? "(" . $request->c_prefijo_telefono2 . ") " . $request->c_telefono2 : NULL;

		// Validamos que no este ya registrado.
		if (Personal::find($identificacion)) {
			return json_encode(["status" => "error", "response" => ["message" => "Este personal ya se encuentra registrado"]]);
		}

		// Ejecutamos una nueva transacción.
		try {
			DB::transaction(function () use ($request, $identificacion, $telefono1, $telefono2) {
				$personal = new Personal();
				$personal->cedula = $identificacion;
				$personal->nombre = mb_convert_case($request->c_nombre_completo, MB_CASE_UPPER);
				$personal->telefono1 = $telefono1;
				$personal->telefono2 = $telefono2;
				$personal->correo = mb_convert_case($request->c_correo_electronico, MB_CASE_UPPER);
				$personal->direccion = mb_convert_case($request->c_direccion, MB_CASE_UPPER);
				$personal->referencia = mb_convert_case($request->c_referencia, MB_CASE_UPPER);
				$personal->idcargo = $request->c_cargo;
				$personal->save();
	
				$usuario = new Usuario();
				$usuario->cedula = $identificacion;
				$usuario->usuario = explode(' ', $request->c_nombre_completo)[0] . rand(100000, 999999);
				$usuario->contrasena = password_hash($request->c_identificacion, PASSWORD_DEFAULT);
				$usuario->save();
			});
		} catch (\Throwable $th) {
			return json_encode(["status" => "error", "response" => ["message" => "Ocurrió un error al registrar el personal", "error" => $th]]);
		}
		return json_encode(["status" => "success", "response" => ["message" => "Personal registrado exitosamente"]]);
	}

	// Display the specified resource. 
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
		$personal = Personal::find($id);
		$departamentos = Departamento::all();
		$cargo = Cargo::find($personal->idcargo);
		$cargos = DB::table('tb_cargos')
			->where('iddepartamento', '=', $cargo->iddepartamento)
			->get();
		return view('personal.modificar', ['personal' => $personal, "cargo_" => $cargo, 'lista_cedula' => $this->lista_cedula, 'lista_prefijos' => $this->lista_prefijos, 'departamentos' => $departamentos, "cargos" => $cargos]);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Validamos.
		if ($request->c_nombre_completo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre completo"]]);
		} else if ($request->c_nombre_completo != "" and strlen($request->c_nombre_completo) < 10) {
			return json_encode(["status" => "error", "response" => ["message" => "El nombre debe tener al menos 10 caracteres"]]);
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
		} else if ($request->c_departamento == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el departamento"]]);
		} else if ($request->c_cargo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el cargo"]]);
		} else if ($request->c_direccion == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese la dirección física"]]);
		}

		// Concatenamos la identificación y los teléfonos.
		$telefono1 = "(" . $request->c_prefijo_telefono1 . ") " . $request->c_telefono1;
		$telefono2 = $request->c_telefono2 != "" ? "(" . $request->c_prefijo_telefono2 . ") " . $request->c_telefono2 : NULL;

		// Ejecutamos una nueva transacción.
		try {
			DB::transaction(function () use ($request, $id, $telefono1, $telefono2) {
				$personal = Personal::find($id);
				$personal->cedula = $id;
				$personal->nombre_completo = mb_convert_case($request->c_nombre_completo, MB_CASE_UPPER);
				$personal->telefono1 = $telefono1;
				$personal->telefono2 = $telefono2;
				$personal->correo_electronico = mb_convert_case($request->c_correo_electronico, MB_CASE_UPPER);
				$personal->direccion = mb_convert_case($request->c_direccion, MB_CASE_UPPER);
				$personal->puntoreferencia = mb_convert_case($request->c_referencia, MB_CASE_UPPER);
				$personal->idcargo = $request->c_cargo;
				$personal->save();
			});
		} catch (\Throwable $th) {
			return json_encode(["status" => "error", "response" => ["message" => "Ocurrió un error al modificar el personal", "error" => $th]]);
		}
		return json_encode(["status" => "success", "response" => ["message" => "Personal modificado exitosamente"]]);
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(string $id)
	{
		$personal = Personal::find($id);
		$personal->estatus = $personal->estatus != "A" ? "A" : "I";
		$personal->save();

		return json_encode(["status" => "success", "response" => ["message" => ""]]);
	}
}
