<?php

namespace App\Http\Controllers;

use App\Models\EventoMonitoreo;
use App\Models\Personal;
use Spipu\Html2Pdf\Html2Pdf;
use App\Models\ReporteDiarioOperador;
use Illuminate\Http\Request;

class MonitoreoControlador extends Controller
{
	use SeguridadControlador;
	use RegistroBitacoraControlador;

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
		$personal	= Personal::all();
		$reportes = ReporteDiarioOperador::select('tb_reportes_diarios.*', 'tb_personal.nombre as operador')
			->join('tb_personal', 'tb_reportes_diarios.cedula', 'tb_personal.cedula')
			->get();
		return view('monitoreo.index', [
			'permisos' => $permisos,
			'reportes' => $reportes,
			'personal' => $personal,
		]);
	}

	// Show the form for creating a new resource.
	public function create()
	{
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
		if ($request->c_fecha == "") {
			$message = "¡Ingrese la fecha del registro!";
		} else if ($request->c_operador == "") {
			$message = "¡Seleccione el operador responsable!";
		} else if ($request->c_turno == "") {
			$message = "¡Seleccione el turno de trabajo!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = ReporteDiarioOperador::select('idreporte')
			->where('fecha', '=', $request->c_fecha)
			->where('cedula', '=', $request->c_operador)
			->where('turno', '=', $request->c_turno)
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Ya tiene una ficha de reporte diario registrada!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del departamento.
		$monitoreo = new ReporteDiarioOperador();
		$monitoreo->fecha = $request->c_fecha;
		$monitoreo->cedula = $request->c_operador;
		$monitoreo->turno = $request->c_turno;
		$monitoreo->servidormensajeria = mb_convert_case($request->c_mensajeria, MB_CASE_UPPER);
		$monitoreo->radio = mb_convert_case($request->c_radio, MB_CASE_UPPER);
		$monitoreo->lineasreportes = mb_convert_case($request->c_lineas, MB_CASE_UPPER);
		$monitoreo->observaciones = $request->c_observacion;
		$monitoreo->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Ficha de reporte diario registrada exitosamente!", "data" => ["id" => $monitoreo->idreporte]]];
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

		// Cargamos la vista para gestionar el reporte con los datos necesarios.
		$reporte = ReporteDiarioOperador::select('tb_reportes_diarios.*', 'tb_personal.nombre as operador')
			->join('tb_personal', 'tb_reportes_diarios.cedula', 'tb_personal.cedula')
			->where('tb_reportes_diarios', '=', $id)
			->first();
		$eventos = EventoMonitoreo::select('tb_reportes_detalles.*', 'tb_clientes.nombre as cliente')
			->join('tb_mapa_zonas', 'tb_reportes_detalles.idcodigo', 'tb_mapa_zonas.idcodigo')
			->join('tb_clientes', 'tb_mapa_zonas.idcliente', 'tb_clientes.identificacion')
			->where('tb_reportes_diarios.idreporte', '=', $reporte->idreporte)
			->get();

		// Verificamos que este todavía abierto para poder editar.
		if ($reporte->estatus != "A") {
			return redirect()->route('monitoreo.index');
		}

		// Cargamos el HTML con todos los datos correspondientes.
		return view('monitoreo.gestionar', [
			'reporte' => $reporte,
			'eventos' => $eventos,
		]);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'update')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para modificar!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del departamento.
		$monitoreo = ReporteDiarioOperador::find($id);
		$monitoreo->servidormensajeria = mb_convert_case($request->c_mensajeria, MB_CASE_UPPER);
		$monitoreo->radio = mb_convert_case($request->c_radio, MB_CASE_UPPER);
		$monitoreo->lineasreportes = mb_convert_case($request->c_lineas, MB_CASE_UPPER);
		$monitoreo->observaciones = $request->c_observacion;
		$monitoreo->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Ficha de reporte diario modificada exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Create event.
	public function create_event(Request $request, string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'update')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para modificar!"]];
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
		} else if ($request->c_hora == "") {
			$message = "¡Ingrese la hora del evento ocurrido!";
		} else if ($request->c_evento == "") {
			$message = "¡Ingrese la descripción del evento!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = EventoMonitoreo::select('iddetalle')
			->where('idreporte', '=', $id)
			->where('idcodigo', '=', $request->c_codigo2)
			->where('hora', '=', $request->c_hora)
			->where('evento', '=', mb_convert_case($request->c_evento, MB_CASE_UPPER))
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este evento ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del departamento.
		$evento = new EventoMonitoreo();
		$evento->idreporte = $id;
		$evento->idcodigo = $request->c_codigo2;
		$evento->hora = $request->c_hora;
		$evento->evento = mb_convert_case($request->c_evento, MB_CASE_UPPER);
		$evento->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Evento registrado exitosamente!", "data" => ["id" => $evento->iddetalle]]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Consult event.
	public function consult_event(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'update')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para modificar!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos el registro a modificar.
		$evento = EventoMonitoreo::select('tb_reportes_detalles.*', 'tb_clientes.identificacion', 'tb_clientes.nombre')
			->join('tb_mapa_zonas', 'tb_reportes_detalles.idcodigo', 'tb_mapa_zonas.idcodigo')
			->join('tb_clientes', 'tb_mapa_zonas.idcliente', 'tb_clientes.identificacion')
			->where('iddetalle', '=', $id)
			->first();
		return response($evento, 200)->header('Content-Type', 'text/json');
	}

	// Consult event.
	public function update_event(Request $request, string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'update')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para modificar!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos.
		$message = "";
		if ($request->c_hora == "") {
			$message = "¡Ingrese la hora del evento ocurrido!";
		} else if ($request->c_evento == "") {
			$message = "¡Ingrese la descripción del evento!";
		}

		// Verificamos si ocurrió algún error en la válidación.
		if ($message != "") {
			$response = ["status" => "error", "response" => ["message" => $message]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Validamos que no este ya registrado.
		$existente = EventoMonitoreo::select('iddetalle')
			->where('idreporte', '=', $request->idreporte)
			->where('idcodigo', '=', $request->c_codigo)
			->where('hora', '=', $request->c_hora)
			->where('evento', '=', mb_convert_case($request->c_evento, MB_CASE_UPPER))
			->where('iddetalle', '!=', $id)
			->first();
		if ($existente) {
			$response = ["status" => "error", "response" => ["message" => "¡Este evento ya se encuentra registrado!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Creamos el nuevo registro del departamento.
		$evento = EventoMonitoreo::find($id);
		$evento->hora = $request->c_hora;
		$evento->evento = mb_convert_case($request->c_evento, MB_CASE_UPPER);
		$evento->save();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Evento modificado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// delete event.
	public function delete_event(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'update')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para modificar!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Procedemos a eliminar el evento de la base de datos.
		EventoMonitoreo::where('iddetalle', '=', $id)->delete();

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Evento eliminado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Generar pdf.
	public function generar_pdf(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'generar_pdf')) {
			return $this->error403();
		}

		// Consultamos los datos del reporte.
		$reporte = ReporteDiarioOperador::select('tb_reportes_diarios.*', 'tb_personal.nombre as operador')
			->join('tb_personal', 'tb_reportes_diarios.cedula', 'tb_personal.cedula')
			->first();
		$eventos = EventoMonitoreo::select('tb_reportes_detalles.*', 'tb_clientes.nombre as cliente')
			->join('tb_mapa_zonas', 'tb_reportes_detalles.idcodigo', 'tb_mapa_zonas.idcodigo')
			->join('tb_clientes', 'tb_mapa_zonas.idcliente', 'tb_clientes.identificacion')
			->where('idreporte', '=', $reporte->idreporte)
			->get();

		// Generamos el nuevo PDF.
		$pdf			= view('monitoreo.pdf_reporte_diario', ["reporte" => $reporte, "eventos" => $eventos]);
		$html2pdf = new Html2Pdf('L', 'LETTER', 'es'); // Orientación [P=Vertical|L=Horizontal] | TAMAÑO [LETTER = CARTA] | Lenguaje [es]
		$html2pdf->pdf->SetTitle('Reporte diario de operador ');
		$html2pdf->writeHTML($pdf);
		$html2pdf->output('monitoreo.pdf');
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
		$monitoreo = ReporteDiarioOperador::find($id);
		$monitoreo->estatus = $monitoreo->estatus != "A" ? "A" : "C";
		$monitoreo->save();

		// Enviamos un mensaje de exito al usuario.
		$message	= $monitoreo->estatus == "A" ? "¡Reporte abierto exitosamente!" : "¡Reporte cerrado exitosamente!";
		$response = ["status" => "success", "response" => ["message" => $message, "data" => ["estatus" => $monitoreo->estatus]]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
