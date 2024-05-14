<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TareaControlador extends Controller
{
	// Display a listing of the resource.
	public function index()
	{
	}

	public function tareas(string $id)
	{
		$tareas	= DB::table('tb_notas')
			->select('*')
			->where('idusuario', '=', $id)
			->get();
		return $tareas;
	}

	// Show the form for creating a new resource.
	public function create()
	{
	}

	// Store a newly created resource in storage.
	public function store(Request $request)
	{
		$tarea = new Tarea();
		$tarea->titulo = $request->c_titulo;
		$tarea->fechatope	= $request->c_fecha;
		$tarea->descripcion	= $request->c_descripcion;
		$tarea->idusuario	= $request->c_descripcio;
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
