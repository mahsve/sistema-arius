<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Personal;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class PersonalControlador extends Controller
{
	use SeguridadControlador;

	// ID de los servicios.
	public $idservicio = 13;
	public $idservicio_dep = 5; // ID del submódulo departamento.
	public $idservicio_car = 9; // ID del submódulo cargo.

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

	// Display a listing of the resource. 
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$personal = DB::table('tb_personal')
			->select('tb_personal.*', 'tb_usuarios.usuario', 'tb_roles.rol')
			->leftjoin('tb_usuarios', 'tb_personal.cedula', '=', 'tb_usuarios.cedula')
			->leftjoin('tb_roles', 'tb_usuarios.idrol', '=', 'tb_roles.idrol')
			->get();
		return view('personal.index', [
			'permisos' => $permisos,
			'personal' => $personal,
		]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$crear_dep = $this->verificar_acceso_servicio_metodo($this->idservicio_dep, 'create'); // Buscando también si tiene permiso para registro en este submódulo [departamento].
		$crear_car = $this->verificar_acceso_servicio_metodo($this->idservicio_car, 'create'); // Buscando también si tiene permiso para registro en este submódulo [cargo].
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'create')) {
			return $this->error403();
		}

		// Cargamos la vista para registrar un nuevo personal con los datos necesarios.
		$departamentos = Departamento::all();
		return view('personal.registrar', [
			'crear_departamento' => $crear_dep,
			'crear_cargo' => $crear_car,
			'lista_cedula' => $this->lista_cedula,
			'lista_prefijos' => $this->lista_prefijos,
			'departamentos' => $departamentos,
		]);
	}

	// Función para consultar los cargos de un departamento.
	public function cargos(string $id)
	{
		// Consultamos los cargos del departamento seleccionado.
		$cargos = DB::table('tb_cargos')->where('iddepartamento', '=', $id)->get();
		return response($cargos, 200)->header('Content-Type', 'text/json');
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
			$message = "¡Ingrese el número de Cédula!";
		} else if (strlen($request->c_identificacion) < 8) {
			$message = "¡La cédula está incompleta!";
		} else if ($request->c_nombre_completo == "") {
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
		} else if ($request->c_departamento == "") {
			$message = "¡Seleccione el departamento!";
		} else if ($request->c_cargo == "") {
			$message = "¡Seleccione el cargo!";
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
		$identificacion	= $request->c_prefijo_identificacion . "-" . $request->c_identificacion;
		$telefono1			= "(" . $request->c_prefijo_telefono1 . ") " . $request->c_telefono1;
		$telefonox			= "(" . $request->c_prefijo_telefono2 . ") " . $request->c_telefono2;
		$telefono2			= $telefonox != "() " ? $telefonox : "";

		// Validamos que no este ya registrado.
		if (Personal::find($identificacion)) {
			$response = ["status" => "error", "response" => ["message" => "¡Este personal ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Registramos el trabajador.
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

		// Enviamos un mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Personal registrado exitosamente!"]];
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
			return $this->error403();
		}

		// Cargamos la vista para modificar un personal con los datos necesarios.
		$personal = Personal::select('tb_personal.*', 'tb_cargos.iddepartamento')
			->join('tb_cargos', 'tb_personal.idcargo', 'tb_cargos.idcargo')
			->where('tb_personal.cedula', '=', $id)
			->first();
		$departamentos = Departamento::all();
		$cargos = Cargo::select('*')->where('iddepartamento', '=', $personal->iddepartamento)->get();
		if ($personal->cedula != session('personal')->cedula) {
			return view('personal.modificar', [
				'personal' => $personal,
				'lista_cedula' => $this->lista_cedula,
				'lista_prefijos' => $this->lista_prefijos,
				'departamentos' => $departamentos,
				'cargos' => $cargos,
			]);
		} else {
			return redirect()->route('personal.index');
		}
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
		} else if ($request->c_departamento == "") {
			$message = "¡Seleccione el departamento!";
		} else if ($request->c_cargo == "") {
			$message = "¡Seleccione el cargo!";
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
		$personal = Personal::find($id);
		$personal->nombre = mb_convert_case($request->c_nombre_completo, MB_CASE_UPPER);
		$personal->telefono1 = $telefono1;
		$personal->telefono2 = $telefono2;
		$personal->correo = mb_convert_case($request->c_correo_electronico, MB_CASE_UPPER);
		$personal->direccion = mb_convert_case($request->c_direccion, MB_CASE_UPPER);
		$personal->referencia = mb_convert_case($request->c_referencia, MB_CASE_UPPER);
		$personal->idcargo = $request->c_cargo;
		$personal->save();

		// Enviamos un mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Personal modificado exitosamente!"]];
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
		$personal = Personal::find($id);
		$personal->estatus = $personal->estatus != "A" ? "A" : "I";
		$personal->save();

		// Enviamos mensaje de exito al usuario.
		$message	= $personal->estatus == "A" ? "¡Estatus cambiado a activo!" : "¡Estatus cambiado a inactivo!";
		$response = ["status" => "success", "response" => ["message" => $message]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
