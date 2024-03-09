<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoControlador extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$department = Departamento::all();
		return view('deparment.index', ["departments" => $department]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		$request->validate([
			'departamento' => 'required|min:3|max:255'
		]);
		$department = new Departamento();
		$department->departamento	= $request->departamento;
		$department->save();
		return redirect('/departamentos')->with('success', '¡Departamento creado exitosamente!');
	}

	// Display the specified resource. 
	public function show(string $id)
	{
		$department = Departamento::find($id);
		return json_encode($department);
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{

	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		$request->validate([
			'departamento' => 'required|min:3|max:255'
		]);
		$department = Departamento::find($id);
		$department->departamento	= $request->departamento;
		$department->save();
		return redirect('/departamentos')->with('success', '¡Departamento actualizado exitosamente!');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{

	}
}
