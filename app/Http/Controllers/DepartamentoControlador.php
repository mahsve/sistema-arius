<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartamentoControlador extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$departamentos = Departamento::all();
		return view('departamento.index', ["departamentos" => $departamentos]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Validamos.
		if ($request->c_departamento == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del departamento"]]);
		} else if (strlen($request->c_departamento) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El departamento debe tener al menos 3 caracteres"]]);
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_departamentos')
			->select('departamento')
			->where('departamento', '=', mb_convert_case($request->c_departamento, MB_CASE_UPPER))
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este departamento ya se encuentra registrado"]]);
		}

		// Creamos el nuevo registro del departamento.
		$departamento = new Departamento();
		$departamento->departamento = mb_convert_case($request->c_departamento, MB_CASE_UPPER);
		$departamento->save();

		// return redirect('/departamentos')->with('success', '¡Departamento creado exitosamente!');
		return json_encode(["status" => "success", "response" => ["message" => "Departamento registrado exitosamente"]]);
	}

	// Display the specified resource. 
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
		$departamento = Departamento::find($id);
		return json_encode($departamento);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Validamos.
		if ($request->c_departamento == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del departamento"]]);
		} else if (strlen($request->c_departamento) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El departamento debe tener al menos 3 caracteres"]]);
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_departamentos')
			->select('departamento')
			->where('departamento', '=', mb_convert_case($request->c_departamento, MB_CASE_UPPER))
			->where('iddepartamento', '!=', $id)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este departamento ya se encuentra registrado"]]);
		}

		// Consultamos y modificamos el registro del departamento.
		$departamento = Departamento::find($id);
		$departamento->departamento = mb_convert_case($request->c_departamento, MB_CASE_UPPER);
		$departamento->save();
		
		return json_encode(["status" => "success", "response" => ["message" => "Departamento modificado exitosamente"]]);
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(string $id)
	{
		$departamento = Departamento::find($id);
		$departamento->estatus = $departamento->estatus != "A" ? "A" : "I";
		$departamento->save();

		return json_encode(["status" => "success", "response" => ["message" => ""]]);
	}
}
