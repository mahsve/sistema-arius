<?php

namespace App\Http\Controllers;

use Spipu\Html2Pdf\Html2Pdf;
use App\Models\ReporteDiarioOperador;
use Illuminate\Http\Request;

class ReporteDiarioOperadorControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 43;

	// Display a listing of the resource. 
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$reportes = ReporteDiarioOperador::all();
		return view('reporte_diario_operador.index', [
			'permisos' => $permisos,
			'reportes' => $reportes
		]);
	}

	// Show the form for creating a new resource.
	public function create()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, '')) {
			return $this->error403();
		}
		return view('reporte_diario_operador.registrar');
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, '')) {
			return $this->error403();
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
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, '')) {
			return $this->error403();
		}
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
	}

	// Generar pdf.
	public function generar_pdf(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, '')) {
			return $this->error403();
		}

		// Generamos el nuevo PDF.
		$variable	= "Ejemplo";
		$pdf			= view('reporte_diario_operador.pdf_reporte_diario', ["variable" => $variable]);
		$html2pdf = new Html2Pdf('L', 'LETTER', 'es'); // Orientación [P=Vertical|L=Horizontal] | TAMAÑO [LETTER = CARTA] | Lenguaje [es]
		$html2pdf->pdf->SetTitle('Reporte diario de operador ');
		$html2pdf->writeHTML($pdf);
		$html2pdf->output('reporte_diario_operador.pdf');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}
}
