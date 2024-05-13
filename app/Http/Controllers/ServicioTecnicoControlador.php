<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\MapaDeZona;
use App\Models\ServicioTecnicoSolicitado;
use Illuminate\Http\Request;

class ServicioTecnicoControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 48;
	public $motivos = [
		'Instalación',
		'Avería',
		'Mantenimiento',
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
		$mes	= date('m');
		$anio	= date('Y');
		$fecha_inicio	= $anio . "-" . $mes . "-01";
		$fecha_final	= cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
		$servicios = ServicioTecnicoSolicitado::select('tb_servicios_solicitados.*', 'tb_clientes.identificacion', 'tb_clientes.nombre', 'tb_personal.nombre as personal')
			->join('tb_personal', 'tb_servicios_solicitados.cedula', 'tb_personal.cedula')
			->join('tb_mapa_zonas', 'tb_servicios_solicitados.idcodigo', 'tb_mapa_zonas.idcodigo')
			->join('tb_clientes', 'tb_mapa_zonas.idcliente', 'tb_clientes.identificacion')
			->whereBetween('fecha', [$fecha_inicio, $fecha_final])
			->get();
		return view('servicio_tecnico.index', [
			'permisos' => $permisos,
			'motivos' => $this->motivos,
			'servicios' => $servicios,
		]);
	}

	// Show the form for creating a new resource.
	public function create()
	{
	}

	// Buscar los clientes por string de busqueda.
	public function clientes(string $string)
	{
		$clientes = Cliente::select('tb_clientes.*', 'tb_mapa_zonas.idcodigo')
			->join('tb_mapa_zonas', 'tb_clientes.identificacion', 'tb_mapa_zonas.idcliente')
			->where('tb_clientes.identificacion', '=', $string)
			->orWhere('tb_clientes.nombre', 'like', '%' . $string . '%')
			->orWhere('tb_mapa_zonas.idcodigo', '=', $string)
			->get();
		if (count($clientes) == 0) $clientes = "null";
		return response($clientes, 200)->header('Content-Type', 'text/json');
	}

	// Buscar el mapa de zona por código.
	public function mapa_de_zona(string $id)
	{
		$mapa_de_zona = MapaDeZona::select('tb_clientes.identificacion', 'tb_clientes.nombre', 'tb_mapa_zonas.idcodigo')
			->join('tb_clientes', 'tb_mapa_zonas.idcliente', 'tb_clientes.identificacion')
			->where('tb_mapa_zonas.idcodigo', '=', $id)
			->first();
		if (!$mapa_de_zona) $mapa_de_zona = "null";
		return response($mapa_de_zona, 200)->header('Content-Type', 'text/json');
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
		if ($request->c_codigo == "") {
			$message = "¡Ingrese el código del cliente!";
		} else if (strlen($request->c_codigo) < 4) {
			$message = "¡El código debe tener 4 caracteres!";
		} else if ($request->c_codigo2 == "") {
			$message = "¡Ingrese el código de un cliente registrado!";
		} else if ($request->c_fecha == "") {
			$message = "¡Ingrese la fecha de la solicitud!";
		} else if ($request->c_motivo == "") {
			$message = "¡Seleccione el motivo de la solicitud!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = ServicioTecnicoSolicitado::select('idsolicitud')
			->where('idcodigo', '=', $request->c_codigo)
			->where('fecha', '=', $request->c_fecha)
			->where('motivo', '=', $request->c_motivo)
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Ya hay una solicitud similar registrada!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del departamento.
		$servicio = new ServicioTecnicoSolicitado();
		$servicio->idcodigo = $request->c_codigo;
		$servicio->fecha = $request->c_fecha;
		$servicio->motivo = $request->c_motivo;
		$servicio->descripcion = $request->c_descripcion;
		$servicio->cedula = auth()->user()->cedula;
		$servicio->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Solicitud registrada exitosamente!"]];
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
		$servicioTecnico = ServicioTecnicoSolicitado::select('tb_servicios_solicitados.*', 'tb_clientes.nombre')
			->join('tb_mapa_zonas', 'tb_servicios_solicitados.idcodigo', 'tb_mapa_zonas.idcodigo')
			->join('tb_clientes', 'tb_mapa_zonas.idcliente', 'tb_clientes.identificacion')
			->where('idsolicitud', '=', $id)
			->first();
		return response($servicioTecnico, 200)->header('Content-Type', 'text/json');
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
		if ($request->c_fecha == "") {
			$message = "¡Ingrese la fecha de la solicitud!";
		} else if ($request->c_motivo == "") {
			$message = "¡Seleccione el motivo de la solicitud!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = ServicioTecnicoSolicitado::select('idsolicitud')
			->where('idcodigo', '=', $request->c_codigo)
			->where('fecha', '=', $request->c_fecha)
			->where('motivo', '=', $request->c_motivo)
			->where('idsolicitud', '!=', $id)
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Ya hay una solicitud similar registrada!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del departamento.
		$servicio = ServicioTecnicoSolicitado::find($id);
		$servicio->fecha = $request->c_fecha;
		$servicio->motivo = $request->c_motivo;
		$servicio->descripcion = $request->c_descripcion;
		$servicio->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Solicitud actualizada exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Remove the specified resource from storage.
	public function destroy(string $id)
	{
	}
}