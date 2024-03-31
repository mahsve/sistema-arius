<?php

namespace App\Http\Controllers;

use Spipu\Html2Pdf\Html2Pdf;
use App\Models\ReporteDiario;
use Illuminate\Http\Request;

class ReporteDiarioControlador extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$reportes = ReporteDiario::all();
		return view('reporte_diario.index', ['reportes' => $reportes]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		return view('reporte_diario.registrar');
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
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

	// Generar pdf.
	public function generar_pdf(string $id)
	{
		$variable	= "Ejemplo";

		// Generamos el nuevo PDF.
		$pdf			= view('pdfs.reporte_diario', ["variable" => $variable]);
		$html2pdf = new Html2Pdf();
		$html2pdf->pdf->SetTitle('Reporte diario de operador ' . $id);
		$html2pdf->writeHTML($pdf);
		$html2pdf->output('reporte_diario.pdf');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(string $id)
	{
	}
}
