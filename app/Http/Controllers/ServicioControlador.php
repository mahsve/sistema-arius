<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicioControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 29;
	public $idservicio_mod = 25;

	// Display a listing of the resource. 
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		$crear_mod = $this->verificar_acceso_servicio_metodo($this->idservicio_mod, 'create'); // Buscando también si tiene permiso para registro en este submódulo [modulos].
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$modulos = Modulo::select()->orderBy('orden', 'ASC')->get();
		$servicios = DB::table('tb_servicios')->select(
			'tb_servicios.*',
			'tb_modulos.modulo',
			DB::raw("IF(submodulo_main.servicio IS NULL, tb_servicios.servicio, submodulo_main.servicio) as 'submodulo'")
		)
			->join('tb_modulos', 'tb_servicios.idmodulo', 'tb_modulos.idmodulo')
			->leftJoin('tb_servicios as submodulo_main', 'tb_servicios.idservicio_raiz', 'submodulo_main.idservicio')
			->orderBy('tb_modulos.modulo', 'ASC')
			->orderBy('submodulo', 'ASC')
			->orderBy('tb_servicios.idservicio_raiz', 'ASC')
			->get();
		return view('servicio.index', [
			'permisos' => $permisos,
			'crear_modulo' => $crear_mod,
			"modulos" => $modulos,
			"servicios" => $servicios,
		]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
	}

	// Función para consultar los submódulos de un módulo.
	public function submodulos(string $id)
	{
		// Consultamos los submódulos del módulo seleccionado.
		$submodulos = Servicio::select()
			->where('idmodulo', '=', $id)
			->whereNull('idservicio_raiz')
			->orderBy('orden', 'ASC')
			->get();
		return response($submodulos, 200)->header('Content-Type', 'text/json');
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
		if ($request->c_modulo == "") {
			$message = "¡Seleccione el módulo!";
		} else if ($request->c_tipo_servicio == "operacion" and $request->c_submodulo == "") { // Verifica que el submodulo no este vacío.
			// Solo válida en caso de  ser un tipo de servicio "operación" que depende de un servicio pricipal como clientes, departamentos, etc.
			$message = "¡Seleccione el submódulo!";
		} else if ($request->c_servicio == "") {
			$message = "¡Ingrese el nombre del servicio!";
		} else if (strlen($request->c_servicio) < 3) {
			$message = "¡El servicio debe tener al menos 3 caracteres!";
		} else if ($request->c_tipo_servicio == "submodulo" and $request->c_enlace == "") {
			$message = "¡Ingrese el enlace del servicio!";
		} else if ($request->c_tipo_servicio == "submodulo" and strlen($request->c_enlace) < 3) {
			$message = "¡El enlace debe tener al menos 3 caracteres!";
		} else if ($request->c_tipo_servicio == "operacion" and $request->c_metodo == "") {
			$message = "¡Ingrese el método en el controlador del servicio!";
		} else if ($request->c_tipo_servicio == "operacion" and strlen($request->c_metodo) < 3) {
			$message = "¡El método debe tener al menos 3 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos la base de datos según el tipo de servicio para evitar repitencias.
		if ($request->c_tipo_servicio == "submodulo") {
			$existente = DB::table('tb_servicios')
				->select('idservicio')
				->where('servicio', '=', mb_convert_case($request->c_servicio, MB_CASE_UPPER))
				->where('idmodulo', '=', $request->c_modulo)
				->first();
		} else if ($request->c_tipo_servicio == "operacion") {
			$existente = DB::table('tb_servicios')
				->select('idservicio')
				->where('servicio', '=', mb_convert_case($request->c_servicio, MB_CASE_UPPER))
				->where('idmodulo', '=', $request->c_modulo)
				->where('idservicio_raiz', '=', $request->c_submodulo)
				->first();
		}

		// Verificamos si existe un servició igual en la base de datos dependiendo de las caracteristicas.
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este servicio ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del servicio.
		$servicio = new Servicio();
		$servicio->idmodulo = $request->c_modulo;
		$servicio->servicio = mb_convert_case($request->c_servicio, MB_CASE_UPPER); // Transformamos a mayuscula.
		if ($request->c_tipo_servicio == "submodulo") {
			// Consultamos el total registrado para asignar el orden en la ultima posición de este nuevo registro.
			$total = count(Servicio::select()->where('idmodulo', '=', $request->c_modulo)->whereNull('idservicio_raiz')->orderBy('orden', 'ASC')->get()) + 1;
			$servicio->orden = $total;
			$servicio->menu_url = mb_convert_case($request->c_enlace, MB_CASE_LOWER);
		} else if ($request->c_tipo_servicio == "operacion") {
			$servicio->menu_url = mb_convert_case($request->c_metodo, MB_CASE_LOWER);
			$servicio->idservicio_raiz = $request->c_submodulo;
		}
		$servicio->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Servicio registrado exitosamente!"]];
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
		$servicio = Servicio::find($id);
		// Consulta servicios primarios como clientes, departamentos, ect. que esten relacionadas directamente con los módulos.
		$servicio->submodulos = Servicio::select()
			->where('idmodulo', '=', $servicio->idmodulo)
			->whereNull('idservicio_raiz')
			->orderBy('orden', 'ASC')
			->get();
		return response($servicio, 200)->header('Content-Type', 'text/json');
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
		if ($request->c_modulo == "") {
			$message = "¡Seleccione el módulo!";
		} else if ($request->c_tipo_servicio == "operacion" and $request->c_submodulo == "") { // Verifica que el submodulo no este vacío.
			// Solo válida en caso de  ser un tipo de servicio "operación" que depende de un servicio pricipal como clientes, departamentos, etc.
			$message = "¡Seleccione el submódulo!";
		} else if ($request->c_servicio == "") {
			$message = "¡Ingrese el nombre del servicio!";
		} else if (strlen($request->c_servicio) < 3) {
			$message = "¡El servicio debe tener al menos 3 caracteres!";
		} else if ($request->c_tipo_servicio == "submodulo" and $request->c_enlace == "") {
			$message = "¡Ingrese el enlace del servicio!";
		} else if ($request->c_tipo_servicio == "submodulo" and strlen($request->c_enlace) < 3) {
			$message = "¡El enlace debe tener al menos 3 caracteres!";
		} else if ($request->c_tipo_servicio == "operacion" and $request->c_metodo == "") {
			$message = "¡Ingrese el método en el controlador del servicio!";
		} else if ($request->c_tipo_servicio == "operacion" and strlen($request->c_metodo) < 3) {
			$message = "¡El método debe tener al menos 3 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos la base de datos según el tipo de servicio para evitar repitencias.
		if ($request->c_tipo_servicio == "submodulo") {
			$existente = DB::table('tb_servicios')
				->select('idservicio')
				->where('servicio', '=', mb_convert_case($request->c_servicio, MB_CASE_UPPER))
				->where('idmodulo', '=', $request->c_modulo)
				->where('idservicio', '!=', $id)
				->first();
		} else if ($request->c_tipo_servicio == "operacion") {
			$existente = DB::table('tb_servicios')
				->select('idservicio')
				->where('servicio', '=', mb_convert_case($request->c_servicio, MB_CASE_UPPER))
				->where('idmodulo', '=', $request->c_modulo)
				->where('idservicio_raiz', '=', $request->c_submodulo)
				->where('idservicio', '!=', $id)
				->first();
		}

		// Verificamos si existe un servició igual en la base de datos dependiendo de las caracteristicas.
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este servicio ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos y modificamos el registro del servicio.
		$servicio = Servicio::find($id);
		$servicio->idmodulo = $request->c_modulo;
		$servicio->servicio = mb_convert_case($request->c_servicio, MB_CASE_UPPER); // Transformamos a mayuscula.
		if ($request->c_tipo_servicio == "submodulo") {
			// Consultamos el total registrado para asignar el orden en la ultima posición de este nuevo registro.
			$total						= $servicio->orden == null ? count(Servicio::select()->where('idmodulo', '=', $request->c_modulo)->whereNull('idservicio_raiz')->orderBy('orden', 'ASC')->get()) + 1 : 0;
			$servicio->orden	= $servicio->orden != null ? $servicio->orden : $total;
			$servicio->menu_url = mb_convert_case($request->c_enlace, MB_CASE_LOWER);
			$servicio->idservicio_raiz = null;
		} else if ($request->c_tipo_servicio == "operacion") {
			$servicio->orden = null;
			$servicio->menu_url = mb_convert_case($request->c_metodo, MB_CASE_LOWER);
			$servicio->idservicio_raiz = $request->c_submodulo;
		}
		$servicio->save();

		// Enviamos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Servicio modificado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}

	// Update services's order [submóduls].
	public function order(Request $request)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'sortable')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para cambiar el orden!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Ejecutamos una nueva transacción.
		try {
			// Recorremos los módulos y actualizamos su orden según lo descrito por el usuario.
			DB::transaction(function () use ($request) {
				for ($i = 0; $i < count($request->modulo); $i++) {
					$submodulo = Servicio::find($request->modulo[$i]);
					$submodulo->orden = $request->orden[$i];
					$submodulo->save();
				}
			});
		} catch (\Throwable $th) {
			$response = ["status" => "error", "response" => ["message" => "¡Ocurrió un error al actualizar el orden de los módulos!", "error" => $th->getMessage()]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Enviamos un mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Orden actualizado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
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
		$servicio = Servicio::find($id);
		$servicio->estatus = $servicio->estatus != "A" ? "A" : "I";
		$servicio->save();

		// Enviamos un mensaje de exito al usuario.
		$message	= $servicio->estatus == "A" ? "¡Estatus cambiado a activo!" : "¡Estatus cambiado a inactivo!";
		$response = ["status" => "success", "response" => ["message" => $message]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
