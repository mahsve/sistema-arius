<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\TypePersonal;
use Illuminate\Http\Request;

class TypePersonalController extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$department	= Departments::all();
		$personalTy = TypePersonal::all();
		return view('type-personal.index', ["departments" => $department, "type_personal" => $personalTy]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		$request->validate([
			'tipo_personal' => 'required|min:3|max:255',
			'id_departamento'	=> 'required'
		]);
		$type_personal = new TypePersonal();
		$type_personal->tipo_personal	= $request->tipo_personal;
		$type_personal->id_departamento	= $request->id_departamento;
		$type_personal->save();
		return redirect('/tipo-personal')->with('success', '¡Tipo de personal creado exitosamente!');
	}

	// Display the specified resource. 
	public function show(string $id)
	{
		$type_personal = TypePersonal::find($id);
		return json_encode($type_personal);
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{

	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		$request->validate([
			'tipo_personal' => 'required|min:3|max:255',
			'id_departamento'	=> 'required'
		]);
		$type_personal = TypePersonal::find($id);
		$type_personal->tipo_personal	= $request->tipo_personal;
		$type_personal->id_departamento	= $request->id_departamento;
		$type_personal->save();
		return redirect('/tipo-personal')->with('success', '¡Tipo de personal actualizado exitosamente!');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{

	}
}
