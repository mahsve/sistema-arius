<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolControlador extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$roles = Rol::all();
		return view('roles.index', ["roles" => $roles]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Validamos.
		if ($request->c_rol == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del módulo"]]);
		} else if (strlen($request->c_rol) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El módulo debe tener al menos 3 caracteres"]]);
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_roles')
			->select('rol')
			->where('rol', '=', mb_convert_case($request->c_rol, MB_CASE_UPPER))
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este módulo ya se encuentra registrado"]]);
		}

		// Creamos el nuevo registro del módulo.
		$rol = new Rol();
		$rol->rol = mb_convert_case($request->c_rol, MB_CASE_UPPER);
		$rol->save();

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
		$rol = Rol::find($id);
		return json_encode($rol);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Validamos.
		if ($request->c_rol == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del módulo"]]);
		} else if (strlen($request->c_rol) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El módulo debe tener al menos 3 caracteres"]]);
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_roles')
			->select('rol')
			->where('rol', '=', mb_convert_case($request->c_rol, MB_CASE_UPPER))
			->where('idrol', '!=', $id)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este módulo ya se encuentra registrado"]]);
		}

		// Consultamos y modificamos el registro del módulo.
		$rol = Rol::find($id);
		$rol->rol = mb_convert_case($request->c_rol, MB_CASE_UPPER);
		$rol->save();

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
		$rol = Rol::find($id);
		$rol->estatus = $rol->estatus != "A" ? "A" : "I";
		$rol->save();

		return json_encode(["status" => "success", "response" => ["message" => ""]]);
	}
}
