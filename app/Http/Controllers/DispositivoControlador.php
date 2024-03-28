<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispositivoControlador extends Controller
{
	// Display a listing of the resource.
	public function index()
	{
		$dispositivos = Dispositivo::all();
		return view('dispositivo.index', ["dispositivos" => $dispositivos]);
	}

	// Show the form for creating a new resource.
	public function create()
	{
	}

	// Store a newly created resource in storage.
	public function store(Request $request)
	{
		// Validamos.
		if ($request->c_dispositivo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del dispositivo"]]);
		} else if (strlen($request->c_dispositivo) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El dispositivo debe tener al menos 3 caracteres"]]);
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_dispositivos')
			->select('dispositivo')
			->where('dispositivo', '=', mb_convert_case($request->c_dispositivo, MB_CASE_UPPER))
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este dispositivo ya se encuentra registrado"]]);
		}

		// Creamos el nuevo registro del dispositivo.
		$dispositivo = new Dispositivo();
		$dispositivo->dispositivo = mb_convert_case($request->c_dispositivo, MB_CASE_UPPER);
		$dispositivo->save();

		// Enviamos mensaje de exito al usuario.
		return json_encode(["status" => "success", "response" => ["message" => "Dispositivo registrado exitosamente"]]);
	}

	// Display the specified resource.
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource.
	public function edit(string $id)
	{
		$dispositivo = Dispositivo::find($id);
		return json_encode($dispositivo);
	}

	// Update the specified resource in storage.
	public function update(Request $request, string $id)
	{
		// Validamos.
		if ($request->c_dispositivo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre del dispositivo"]]);
		} else if (strlen($request->c_dispositivo) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "El dispositivo debe tener al menos 3 caracteres"]]);
		}

		// Validamos que no este ya registrado.
		$existente = DB::table('tb_dispositivos')
			->select('dispositivo')
			->where('dispositivo', '=', mb_convert_case($request->c_dispositivo, MB_CASE_UPPER))
			->where('iddispositivo', '!=', $id)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Este dispositivo ya se encuentra registrado"]]);
		}

		// Consultamos y modificamos el registro del dispositivo.
		$dispositivo = Dispositivo::find($id);
		$dispositivo->dispositivo = mb_convert_case($request->c_dispositivo, MB_CASE_UPPER);
		$dispositivo->save();
		
		return json_encode(["status" => "success", "response" => ["message" => "Dispositivo modificado exitosamente"]]);
	}

	// Remove the specified resource from storage.
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(string $id)
	{
		$dispositivo = Dispositivo::find($id);
		$dispositivo->estatus = $dispositivo->estatus != "A" ? "A" : "I";
		$dispositivo->save();

		return json_encode(["status" => "success", "response" => ["message" => ""]]);
	}
}
