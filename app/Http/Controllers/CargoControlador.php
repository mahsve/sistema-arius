<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CargoControlador extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$departamentos	= Departamento::all();
		$cargos					= DB::table('tb_cargos')
			->select('tb_cargos.*', 'tb_departamentos.departamento')
			->join('tb_departamentos', 'tb_cargos.iddepartamento', 'tb_departamentos.iddepartamento')
			->get();
		return view('cargo.index', ["departamentos" => $departamentos, "cargos" => $cargos]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existe	= DB::table('tb_cargos')
			->select('idcargo')
			->where('cargo', '=', mb_convert_case($request->cargo, MB_CASE_UPPER))
			->where('iddepartamento', '=', $request->iddepartamento)
			->first();
		if ($existe) return redirect('/cargos')->with('error', '¡El cargo ya se encuentra registrado!');

		// Validamos los campos del formulario en el backend también.
		$request->validate([
			'cargo'						=> 'required|min:3|max:255',
			'iddepartamento'	=> 'required'
		]);

		// Realizamos el registro en la base de datos.
		$cargo	= new Cargo();
		$cargo->cargo						= mb_convert_case($request->cargo, MB_CASE_UPPER); // Transformamos a mayuscula.
		$cargo->iddepartamento	= $request->iddepartamento;
		$cargo->save();
		return redirect('/cargos')->with('success', '¡Cargo creado exitosamente!');
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
		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existe	= DB::table('tb_cargos')
			->select('idcargo')
			->where('cargo', '=', mb_convert_case($request->cargo, MB_CASE_UPPER))
			->where('iddepartamento', '=', $request->iddepartamento)
			->where('idcargo', '!=', $id)
			->first();
		if ($existe) return redirect('/cargos')->with('error', '¡El cargo ya se encuentra registrado!');

		// Validaciones.
		$request->validate([
			'cargo'						=> 'required|min:3|max:255',
			'iddepartamento'	=> 'required'
		]);

		$cargo = Cargo::find($id);
		$cargo->cargo						= mb_convert_case($request->cargo, MB_CASE_UPPER);
		$cargo->iddepartamento	= $request->iddepartamento;
		$cargo->save();
		return redirect('/cargos')->with('success', '¡Cargo actualizado exitosamente!');
	}

	public function estatus(string $id)
	{
		$cargo = Cargo::find($id);
		$cargo->estatus = ($cargo->estatus != "A" ? "A" : "I");
		$cargo->save();
		return json_encode('exito');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}
}
