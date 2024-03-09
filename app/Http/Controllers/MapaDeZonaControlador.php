<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contacts;
use App\Models\ZoneMaps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapaDeZonaControlador extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$clients	= DB::table('tb_clientes')
			->join('tb_mapa_zonas', 'tb_clientes.identificacion', '=', 'tb_mapa_zonas.id_cliente')
			->join('tb_personal', 'tb_mapa_zonas.cedula_asesor', '=', 'tb_personal.cedula')
			->select('tb_clientes.*', 'tb_mapa_zonas.id_codigo', 'tb_personal.nombres', 'tb_personal.apellidos')
			->get();
		return view('zonemaps.index', ['clients' => $clients]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		return view('zonemaps.create');
	}

	public function search_client(string $type, string $id)
	{
		$client = Client::select('*')
			->where('tipo_cliente', '=', $type)
			->where('identificacion', '=', $id)
			->first();
		return json_encode($client);
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		DB::transaction(function () use ($request) {
			$ZoneMaps = new ZoneMaps();
			$ZoneMaps->id_codigo = $request->code_map;
			$ZoneMaps->id_cliente = $request->identification;
			$ZoneMaps->direccion = $request->address;
			$ZoneMaps->punto_referencia = $request->references;
			$ZoneMaps->cedula_asesor = session('user')->cedula;
			$ZoneMaps->observaciones = $request->observation;
			$ZoneMaps->save();

			for ($var = 0; $var < count($request->cedula_); $var++) {
				// Consultamos si ya existe el contacto registrado en la tabla de clientes.
				if ($contact = Client::find($request->cedula_[$var])) {
				} else {
					$contact = new Client();
					$contact->identificacion = $request->cedula_[$var];
					$contact->tipo_cliente = "N";
					$contact->estatus = "A";
				}

				// [Registramos|Actualizamos] los datos del contacto.
				$contact->nombre_completo = $request->fullname_[$var];
				$contact->telefono1 = $request->phone_[$var];
				$contact->save();

				// Lo agregamos como contacto del cliente en el mapa de zona.
				$contact_dt = new Contacts();
				$contact_dt->id_cliente = $request->cedula_[$var];
				$contact_dt->id_codigo = $request->code_map;
				$contact_dt->contrasena = $request->password_[$var];
				$contact_dt->observacion = $request->note_[$var];
				$contact_dt->save();
			}
		});

		return redirect()->route('mapas-de-zonas.index')->with('success', '¡Mapa de zona registrado exitosamente!');
	}

	// Display the specified resource. 
	public function show(string $id)
	{
		return "hola";
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
		$client	= DB::table('tb_clientes')
			->select('*')
			->join('tb_mapa_zonas', 'tb_clientes.identificacion', '=', 'tb_mapa_zonas.id_cliente')
			->join('tb_personal', 'tb_mapa_zonas.cedula_asesor', '=', 'tb_personal.cedula')
			->where('tb_mapa_zonas.id_codigo', $id)
			->first();

		$contacts = DB::table('tb_contactos')
			->select('*')
			->join('tb_clientes', 'tb_contactos.id_cliente', '=', 'tb_clientes.identificacion')
			->where('tb_contactos.id_codigo', $id)
			->get();

		return view('zonemaps.update', ['client' => $client, 'contacts' => $contacts]);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		DB::transaction(function () use ($request, $id) {
			// Registramos un nuevo mapa de zona.
			$ZoneMaps = ZoneMaps::find($id);
			$ZoneMaps->direccion = $request->address;
			$ZoneMaps->punto_referencia = $request->references;
			$ZoneMaps->observaciones = $request->observation;
			$ZoneMaps->save();

			for ($var = 0; $var < count($request->cedula_); $var++) {
				// Consultamos si ya existe el contacto registrado en la tabla de clientes.
				if ($contact = Client::find($request->cedula_[$var])) {
				} else {
					$contact = new Client();
					$contact->identificacion = $request->cedula_[$var];
					$contact->tipo_cliente = "N";
					$contact->estatus = "A";
				}

				// [Registramos|Actualizamos] los datos del contacto.
				$contact->nombre_completo = $request->fullname_[$var];
				$contact->telefono1 = $request->phone_[$var];
				$contact->save();

				// Verificamos si es un nuevo registro o una modificación de la tabla contactos.
				if ($request->idcontact_[$var] != "0") {
					$contact_dt = Contacts::find($request->idcontact_[$var]);
				} else {
					$contact_dt = new Contacts();
					$contact_dt->id_cliente = $request->cedula_[$var];
					$contact_dt->id_codigo = $id;
				}

				// Lo agregamos como contacto del cliente en el mapa de zona.
				$contact_dt->contrasena = $request->password_[$var];
				$contact_dt->observacion = $request->note_[$var];
				$contact_dt->save();
			}
		});

		return redirect()->route('mapas-de-zonas.index')->with('success', '¡Mapa de zona actualizado exitosamente!');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}
}
