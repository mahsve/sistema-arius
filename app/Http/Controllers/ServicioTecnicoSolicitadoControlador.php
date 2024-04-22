<?php

namespace App\Http\Controllers;

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
		$modulos = [];
		$servicios = [];
		// $modulos = Modulo::all();
		// $servicios = DB::table('tb_servicios')
		// 	->select('tb_servicios.*', 'tb_modulos.modulo')
		// 	->join('tb_modulos', 'tb_servicios.idmodulo', 'tb_modulos.idmodulo')
		// 	->get();
		return view('servicio.index', [
			'permisos' => $permisos,
			"modulos" => $modulos,
			"servicios" => $servicios,
		]);
	}

	// Show the form for creating a new resource.
	public function create()
	{
	}

	// Store a newly created resource in storage.
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

	// Remove the specified resource from storage.
	public function destroy(string $id)
	{
	}
}
