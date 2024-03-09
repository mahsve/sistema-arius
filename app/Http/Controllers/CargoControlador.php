<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoControlador extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$departamentos	= Departamento::all();
		$cargos					= Cargo::all();
		return view('cargo.index', ["departamentos" => $departamentos, "cargos" => $cargos]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Validaciones.
		$request->validate([
			'cargo'						=> 'required|min:3|max:255',
			'id_departamento'	=> 'required'
		]);

		$cargo	= new Cargo();
		$cargo->cargo						= $request->cargo;
		$cargo->id_departamento	= $request->id_departamento;
		$cargo->save();
		return redirect('/cargo')->with('success', '¡Cargo creado exitosamente!');
	}

	// Display the specified resource. 
	public function show(string $id)
	{
		$cargo	= Cargo::find($id);
		return json_encode($cargo);
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Validaciones.
		$request->validate([
			'cargo'						=> 'required|min:3|max:255',
			'id_departamento'	=> 'required'
		]);

		$cargo = Cargo::find($id);
		$cargo->cargo						= $request->cargo;
		$cargo->id_departamento	= $request->id_departamento;
		$cargo->save();
		return redirect('/cargo')->with('success', '¡Cargo actualizado exitosamente!');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}
}
