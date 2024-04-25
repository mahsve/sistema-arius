<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\MapaDeZona;
use App\Models\ServicioTecnicoSolicitado;
use Illuminate\Http\Request;

class ServicioTecnicoSolicitadoControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 48;

	// Display a listing of the resource.
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$servicios = ServicioTecnicoSolicitado::select('tb_servicios_solicitados.*', 'tb_clientes.identificacion', 'tb_clientes.nombre')
			->join('tb_mapa_zonas', 'tb_servicios_solicitados.idcodigo', 'tb_mapa_zonas.idcodigo')
			->join('tb_clientes', 'tb_mapa_zonas.idcliente', 'tb_clientes.identificacion')
			->get();
		return view('servicio_tecnico_solicitado.index', [
			'permisos' => $permisos,
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
		if ($request->c_fecha == "") {
			$message = "¡Selecccione la fecha de la solicitud!";
		} else if ($request->c_motivo == "") {
			$message = "¡Ingrese el motivo de la solicitud del servicio!";
		} else if (strlen($request->c_motivo) < 10) {
			$message = "¡El motivo de la solicitud debe tener al menos 10 caracteres!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		// $existente = DB::table('tb_departamentos')
		// 	->select('departamento')
		// 	->where('departamento', '=', mb_convert_case($request->c_departamento, MB_CASE_UPPER))
		// 	->first();
		// if ($existente) {
		// 	$response = ["status" => "error", "response" => ["message" => "¡Esta departamento ya se encuentra registrado!"]];
		// 	return response($response, 200)->header('Content-Type', 'text/json');
		// }

		// Creamos el nuevo registro del departamento.
		$servicio = new ServicioTecnicoSolicitado();
		$servicio->idcodigo = $request->c_codigo;
		$servicio->fecha = $request->c_fecha;
		$servicio->motivo = $request->c_motivo;
		$servicio->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Departamento registrado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Display the specified resource.
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource.
	public function edit(string $id)
	{
	}

	// Update the specified resource in storage.
	public function update(Request $request, string $id)
	{
	}

	// Remove the specified resource from storage.
	public function destroy(string $id)
	{
	}
}
