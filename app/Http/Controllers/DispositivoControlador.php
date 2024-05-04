<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionDis;
use App\Models\DetallesDisConf;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispositivoControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 17;
	public $idservicio_conf = 21; // ID del submódulo configuración.
	public $tiposDispositivos = [
		"Z" => "General",
		"T"	=> "Teclado",
	];

	// Display a listing of the resource.
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		$crear_conf = $this->verificar_acceso_servicio_metodo($this->idservicio_conf, 'create'); // Buscando también si tiene permiso para registro en este submódulo [configuración].
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$dispositivos = Dispositivo::all();
		$configuraciones = ConfiguracionDis::all();
		return view('dispositivo.index', [
			'permisos' => $permisos,
			'crear_configuracion' => $crear_conf,
			"dispositivos" => $dispositivos,
			"tipos_dispositivos" => $this->tiposDispositivos,
			"configuraciones" => $configuraciones,
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
		if ($request->c_tipo == "") {
			$message = "¡Seleccione el tipo de dispositivo!";
		} else if ($request->c_dispositivo == "") {
			$message = "¡Ingrese el nombre del dispositivo!";
		} else if (strlen($request->c_dispositivo) < 2) {
			$message = "¡El dispositivo debe tener al menos 2 caracteres!";
		} else if ($request->c_tipo == "Z" and !isset($request->configuraciones)) {
			$message = "¡Marque al menos una de las configuraciones a agregar!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_dispositivos')
			->select('dispositivo')
			->where('tipo', '=', $request->c_tipo)
			->where('dispositivo', '=', mb_convert_case($request->c_dispositivo, MB_CASE_UPPER))
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este dispositivo ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Ejecutamos una nueva transacción.
		try {
			DB::transaction(function () use ($request) {
				// Creamos el nuevo registro del dispositivo.
				$dispositivo = new Dispositivo();
				$dispositivo->tipo = $request->c_tipo;
				$dispositivo->dispositivo = mb_convert_case($request->c_dispositivo, MB_CASE_UPPER);
				$dispositivo->save();

				// Agregamos las configuraciones seleccionadas en el dispostivio.
				if ($request->c_tipo == "Z") {
					for ($i = 0; $i < count($request->configuraciones); $i++) {
						$dconfiguracion = new DetallesDisConf();
						$dconfiguracion->iddispositivo = $dispositivo->iddispositivo;
						$dconfiguracion->idconfiguracion = $request->configuraciones[$i];
						$dconfiguracion->save();
					}
				}
			});
		} catch (\Throwable $th) {
			$response = ["status" => "error", "response" => ["message" => "¡Ocurrió un error al registrar el dispostivo!", "error" => $th->getMessage()]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Dispositivo registrado exitosamente!"]];
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
		$dispositivo = Dispositivo::find($id);
		$configuraciones = DetallesDisConf::where('iddispositivo', '=', $dispositivo->iddispositivo)->get();
		$dispositivo->configuraciones = $configuraciones;
		return response($dispositivo, 200)->header('Content-Type', 'text/json');
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
		if ($request->c_tipo == "") {
			$message = "¡Seleccione el tipo de dispositivo!";
		} else if ($request->c_dispositivo == "") {
			$message = "¡Ingrese el nombre del dispositivo!";
		} else if (strlen($request->c_dispositivo) < 2) {
			$message = "¡El dispositivo debe tener al menos 2 caracteres!";
		} else if ($request->c_tipo == "Z" and !isset($request->configuraciones)) {
			$message = "¡Marque al menos una de las configuraciones a agregar!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_dispositivos')
			->select('dispositivo')
			->where('tipo', '=', $request->c_tipo)
			->where('dispositivo', '=', mb_convert_case($request->c_dispositivo, MB_CASE_UPPER))
			->where('iddispositivo', '!=', $id)
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este dispositivo ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Ejecutamos una nueva transacción.
		try {
			// Consultamos y modificamos el registro del dispositivo.
			$dispositivo = Dispositivo::find($id);
			$dispositivo->tipo = $request->c_tipo;
			$dispositivo->dispositivo = mb_convert_case($request->c_dispositivo, MB_CASE_UPPER);
			$dispositivo->save();

			// Agregamos las configuraciones seleccionadas en el dispostivio.
			if ($request->c_tipo == "Z") {
				for ($i = 0; $i < count($request->configuraciones); $i++) {
					$existe = DetallesDisConf::where('iddispositivo', '=', $id)->where('idconfiguracion', '=', $request->configuraciones[$i])->first();
					if ($existe == null) { // No esta registrado.
						$dconfiguracion = new DetallesDisConf();
						$dconfiguracion->iddispositivo = $dispositivo->iddispositivo;
						$dconfiguracion->idconfiguracion = $request->configuraciones[$i];
						$dconfiguracion->save();
					}
				}

				// Elimine los que no se encuentran en el arreglo pero si en la base de datos.
				DetallesDisConf::where('iddispositivo', '=', $id)->whereNotIn('idconfiguracion', $request->configuraciones)->delete();
			}
		} catch (\Throwable $th) {
			$response = ["status" => "error", "response" => ["message" => "¡Ocurrió un error al registrar el dispostivo!", "error" => $th->getMessage()]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Dispositivo modificado exitosamente!"]];
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
		$dispositivo = Dispositivo::find($id);
		$dispositivo->estatus = $dispositivo->estatus != "A" ? "A" : "I";
		$dispositivo->save();

		// Enviamos un mensaje de exito al usuario.
		$message	= $dispositivo->estatus == "A" ? "¡Estatus cambiado a activo!" : "¡Estatus cambiado a inactivo!";
		$response = ["status" => "success", "response" => ["message" => $message]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
