<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$department	= Departments::all();
		$personalTy = Position::all();
		return view('position.index', ["departments" => $department, "position" => $personalTy]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		$request->validate([
			'cargo'						=> 'required|min:3|max:255',
			'id_departamento'	=> 'required'
		]);
		$position = new Position();
		$position->cargo						= $request->cargo;
		$position->id_departamento	= $request->id_departamento;
		$position->save();
		return redirect('/cargo')->with('success', '¡Cargo creado exitosamente!');
	}

	// Display the specified resource. 
	public function show(string $id)
	{
		$position = Position::find($id);
		return json_encode($position);
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		$request->validate([
			'cargo'						=> 'required|min:3|max:255',
			'id_departamento'	=> 'required'
		]);
		$position = Position::find($id);
		$position->cargo		= $request->cargo;
		$position->id_departamento	= $request->id_departamento;
		$position->save();
		return redirect('/cargo')->with('success', '¡Cargo actualizado exitosamente!');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}
}
