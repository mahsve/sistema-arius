<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionDis;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracionDisControlador extends Controller
{
	// Display a listing of the resource.
	public function index()
	{
		$dispositivos = Dispositivo::all();
		$configuraciones = DB::table('tb_config_disp')
			->select('tb_config_disp.*', 'tb_dispositivos.dispositivo')
			->join('tb_dispositivos', 'tb_config_disp.iddispositivo', 'tb_dispositivos.iddispositivo')
			->get();
		return view('configuracion_dispositivo.index', ["configuraciones" => $configuraciones, 'dispositivos' => $dispositivos]);
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
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el dispositivo"]]);
		} else if ($request->c_configuracion == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre de la configuración"]]);
		} else if (strlen($request->c_configuracion) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "La configuración debe tener al menos 3 caracteres"]]);
		}

		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existente = DB::table('tb_config_disp')
			->select('idconfiguracion')
			->where('configuracion', '=', mb_convert_case($request->c_configuracion, MB_CASE_UPPER))
			->where('iddispositivo', '=', $request->c_dispositivo)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Esta configuración ya se encuentra registrado"]]);
		}

		// Creamos el nuevo registro de la configuracion.
		$configuracion = new ConfiguracionDis();
		$configuracion->configuracion = mb_convert_case($request->c_configuracion, MB_CASE_UPPER); // Transformamos a mayuscula.
		$configuracion->iddispositivo = $request->c_dispositivo;
		$configuracion->descripcion = $request->c_descripcion;
		$configuracion->save();
		
		return json_encode(["status" => "success", "response" => ["message" => "Configuración registrada exitosamente"]]);
	}

	// Display the specified resource.
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource.
	public function edit(string $id)
	{
		$configuracion = ConfiguracionDis::find($id);
		return json_encode($configuracion);
	}

	// Update the specified resource in storage.
	public function update(Request $request, string $id)
	{
		// Validamos.
		if ($request->c_dispositivo == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Seleccione el dispositivo"]]);
		} else if ($request->c_configuracion == "") {
			return json_encode(["status" => "error", "response" => ["message" => "Ingrese el nombre de la configuración"]]);
		} else if (strlen($request->c_configuracion) < 3) {
			return json_encode(["status" => "error", "response" => ["message" => "La configuración debe tener al menos 3 caracteres"]]);
		}

		// Verificamos primero si ya se encuentra registrado en la base de datos.
		$existente = DB::table('tb_config_disp')
			->select('idconfiguracion')
			->where('configuracion', '=', mb_convert_case($request->c_configuracion, MB_CASE_UPPER))
			->where('iddispositivo', '=', $request->c_dispositivo)
			->where('idconfiguracion', '!=', $id)
			->first();
		if ($existente) {
			return json_encode(["status" => "error", "response" => ["message" => "Esta configuración ya se encuentra registrado"]]);
		}

		// Consultamos y modificamos el registro de la configuración.
		$configuracion = ConfiguracionDis::find($id);
		$configuracion->configuracion = mb_convert_case($request->c_configuracion, MB_CASE_UPPER);
		$configuracion->iddispositivo = $request->c_dispositivo;
		$configuracion->descripcion = $request->c_descripcion;
		$configuracion->save();
		
		return json_encode(["status" => "success", "response" => ["message" => "Configuración modificada exitosamente"]]);
	}

	// Remove the specified resource from storage.
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(string $id)
	{
		$configuracion = ConfiguracionDis::find($id);
		$configuracion->estatus = $configuracion->estatus != "A" ? "A" : "I";
		$configuracion->save();

		return json_encode(["status" => "success", "response" => ["message" => ""]]);
	}
}
