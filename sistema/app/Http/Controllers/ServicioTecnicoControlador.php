<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\MapaDeZona;
use App\Models\Personal;
use App\Models\ServicioTecnicoSolicitado;
use App\Models\Visita;
use App\Models\VisitaTecnico;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;

class ServicioTecnicoControlador extends Controller
{
	use SeguridadControlador;
	use RegistroBitacoraControlador;

	// Atributos de la clase.
	public $idservicio = 43;
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
		$fecha_final	= $anio . "-" . $mes . "-" . cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
		if (isset($_GET["fecha_inicio"]) and !empty($_GET["fecha_inicio"]) and isset($_GET["fecha_tope"]) and !empty($_GET["fecha_tope"])) {
			$fecha_inicio	= $_GET["fecha_inicio"];
			$fecha_final	= $_GET["fecha_tope"];
		}

		$personal = Personal::all();
		$servicios = ServicioTecnicoSolicitado::select('tb_servicios_solicitados.*', 'tb_clientes.identificacion', 'tb_clientes.nombre', 'tb_personal.nombre as personal')
			->join('tb_personal', 'tb_servicios_solicitados.cedula', 'tb_personal.cedula')
			->join('tb_mapa_zonas', 'tb_servicios_solicitados.idcodigo', 'tb_mapa_zonas.idcodigo')
			->join('tb_clientes', 'tb_mapa_zonas.idcliente', 'tb_clientes.identificacion')
			->whereBetween('fecha', [$fecha_inicio, $fecha_final])
			->get();
		return view('servicio_tecnico.index', [
			'permisos' => $permisos,
			'motivos' => $this->motivos,
			'personal' => $personal,
			'fecha_inicio' => $fecha_inicio,
			'fecha_final' => $fecha_final,
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

	// Generar pdf.
	public function generar_pdf()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'generar_pdf')) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$mes	= date('m');
		$anio	= date('Y');
		$fecha_inicio	= $anio . "-" . $mes . "-01";
		$fecha_final	= $anio . "-" . $mes . "-" . cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
		if (isset($_GET["fecha_inicio"]) and !empty($_GET["fecha_inicio"]) and isset($_GET["fecha_tope"]) and !empty($_GET["fecha_tope"])) {
			$fecha_inicio	= $_GET["fecha_inicio"];
			$fecha_final	= $_GET["fecha_tope"];
		}
		$servicios = ServicioTecnicoSolicitado::select('tb_servicios_solicitados.*', 'tb_clientes.identificacion', 'tb_clientes.nombre', 'tb_personal.nombre as personal')
			->join('tb_personal', 'tb_servicios_solicitados.cedula', 'tb_personal.cedula')
			->join('tb_mapa_zonas', 'tb_servicios_solicitados.idcodigo', 'tb_mapa_zonas.idcodigo')
			->join('tb_clientes', 'tb_mapa_zonas.idcliente', 'tb_clientes.identificacion')
			->whereBetween('fecha', [$fecha_inicio, $fecha_final])
			->get();

		// Generamos el nuevo PDF.
		$pdf			= view('servicio_tecnico.pdf_servicio_tecnico', ['motivos' => $this->motivos, "servicios" => $servicios]);
		$html2pdf = new Html2Pdf('L', 'LETTER', 'es'); // Orientación [P=Vertical|L=Horizontal] | TAMAÑO [LETTER = CARTA] | Lenguaje [es]
		$html2pdf->pdf->SetTitle('Reporte diario de operador ');
		$html2pdf->writeHTML($pdf);
		$html2pdf->output("servicio_tecnico_" . $mes . "_" . $anio . ".pdf");
	}

	// Remove the specified resource from storage.
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(Request $request, string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'toggle')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para cambiar el estatus!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos el registro a actualizar el estatus.
		$servicio = ServicioTecnicoSolicitado::find($id);
		$servicio->estatus = $servicio->estatus != "A" ? "A" : "C";
		$servicio->save();

		// Registramos las visitas en el mapa.
		$visita = new Visita();
		$visita->idcodigo = $servicio->idcodigo;
		$visita->fecha = $request->c_fecha;
		$visita->servicioprestado = $request->c_servicio;
		$visita->pendientes = $request->c_pendiente;
		$visita->save();

		// Registramos los tecnicos.
		for ($var = 0; $var < count($request->c_tecnicos); $var++) {
			$visita_tec = new VisitaTecnico();
			$visita_tec->cedula = $request->c_tecnicos[$var];
			$visita_tec->idvisita = $visita->idvisita;
			$visita_tec->save();
		}

		// Enviamos un mensaje de exito al usuario.
		$message	= $servicio->estatus == "A" ? "¡Solicitud abierta exitosamente!" : "¡Solicitud cerrada exitosamente!";
		$response = ["status" => "success", "response" => ["message" => $message, "data" => ["estatus" => $servicio->estatus]]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
