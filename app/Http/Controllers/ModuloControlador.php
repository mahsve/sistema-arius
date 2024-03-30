<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuloControlador extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$modulos = Modulo::all();
		return view('modulo.index', ["modulos" => $modulos]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Validamos.
		if ($request->c_modulo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del módulo"]]);
		} else if (strlen($request->c_modulo) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El módulo debe tener al menos 3 caracteres"]]);
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_modulos')
			->select('modulo')
			->where('modulo', '=', mb_convert_case($request->c_modulo, MB_CASE_UPPER))
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este módulo ya se encuentra registrado"]]);
		}

		// Creamos el nuevo registro del módulo.
		$modulo = new Modulo();
		$modulo->modulo = mb_convert_case($request->c_modulo, MB_CASE_UPPER);
		$modulo->save();

		// Enviamos mensaje de existo al usuario.
		return json_encode(["status" => "success", "response" => ["message" => "Módulo registrado exitosamente"]]);
	}

	// Display the specified resource. 
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
		$modulo = Modulo::find($id);
		return json_encode($modulo);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Validamos.
		if ($request->c_modulo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del módulo"]]);
		} else if (strlen($request->c_modulo) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El módulo debe tener al menos 3 caracteres"]]);
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_modulos')
			->select('modulo')
			->where('modulo', '=', mb_convert_case($request->c_modulo, MB_CASE_UPPER))
			->where('idmodulo', '!=', $id)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este módulo ya se encuentra registrado"]]);
		}

		// Consultamos y modificamos el registro del módulo.
		$modulo = Modulo::find($id);
		$modulo->modulo = mb_convert_case($request->c_modulo, MB_CASE_UPPER);
		$modulo->save();

		// Enviamos mensaje de existo al usuario.
		return json_encode(["status" => "success", "response" => ["message" => "Módulo modificado exitosamente"]]);
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(string $id)
	{
		$modulo = Modulo::find($id);
		$modulo->estatus = $modulo->estatus != "A" ? "A" : "I";
		$modulo->save();

		return json_encode(["status" => "success", "response" => ["message" => ""]]);
	}
}
