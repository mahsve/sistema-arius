<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\Dispositivo;
use App\Models\MapaDeZona;
use GuzzleHttp\Client;
use Hamcrest\Type\IsObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapaDeZonaControlador extends Controller
{
	public $lista_contratos = ["1" => "Domicilio", "2" => "Oficina", "3" => "Empresas", "7" => "Almacen"];
	public $tipos_identificaciones = ["C" => "CÉDULA", "R" => "RIF"];
	public $lista_cedula = ["V", "E"];
	public $lista_rif = ["V", "E", "J", "P", "G"];
	public $lista_prefijos = [
		"Móvil" => [
			"412",
			"414",
			"416",
			"424",
			"426",
		],
		"Hogar" => [
			"212",
			"232",
			"234",
			"235",
			"236",
			"237",
			"238",
			"239",
			"241",
			"242",
			"243",
			"244",
			"245",
			"246",
			"247",
			"248",
			"249",
			"251",
			"252",
			"253",
			"254",
			"255",
			"256",
			"257",
			"258",
			"259",
			"260",
			"261",
			"262",
			"263",
			"264",
			"265",
			"266",
			"267",
			"268",
			"269",
			"270",
			"271",
			"272",
			"273",
			"274",
			"275",
			"276",
			"277",
			"278",
			"279",
			"281",
			"282",
			"283",
			"285",
			"286",
			"287",
			"288",
			"291",
			"292",
			"293",
			"294",
			"295",
		],
	];

	// Display a listing of the resource. 
	public function index()
	{
		$mapas_de_zonas	= DB::table('tb_clientes')
			->join('tb_mapa_zonas', 'tb_clientes.identificacion', '=', 'tb_mapa_zonas.idcliente')
			->join('tb_personal', 'tb_mapa_zonas.cedula_asesor', '=', 'tb_personal.cedula')
			->select('tb_clientes.*', 'tb_mapa_zonas.idcodigo', 'tb_personal.nombre')
			->get();
		return view('mapa_de_zona.index', ['mapas_de_zonas' => $mapas_de_zonas]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		$dispositivos	= Dispositivo::all();
		return view('mapa_de_zona.registrar', [
			'lista_contratos' => $this->lista_contratos,
			'tipos_identificaciones' => $this->tipos_identificaciones,
			'lista_cedula' => $this->lista_cedula,
			'lista_rif' => $this->lista_rif,
			'lista_prefijos' => $this->lista_prefijos,
			'dispositivos' => $dispositivos,
		]);
	}

	// Consultamos el código que continua para el registro del mapa de zona según el tipo de contrato.
	public function codigo(string $id)
	{
		// Consultamos el ultimo código registrado según el tipo de contrato [1000, 2000, 3000, 7000];
		$codigo = DB::table('tb_mapa_zonas')->select('idcodigo')->where('idcodigo', 'like', $id . '%')->orderBy('idcodigo', 'desc')->first();
		// Verificamos si existe un ultimo número o sumamos uno si ya existe.
		if ($codigo) {
			$codigo = intval($codigo) + 1;
		} else {
			$codigo = $id . "001";
		}
		return json_encode($codigo);
	}

	// Buscar los clientes por string de busqueda.
	public function clientes(string $string)
	{
		$clientes = Cliente::select('*')
			->where('identificacion', '=', $string)
			->orWhere('nombre', 'like', '%' . $string . '%')
			->get();
		if (count($clientes) == 0) $clientes = "null";
		return response($clientes, 200)->header('Content-Type', 'text/json');
	}

	// Buscar los clientes por string de busqueda.
	public function cliente(string $id)
	{
		$cliente = Cliente::find($id);
		if (!$cliente) $cliente = "null";
		return response($cliente, 200)->header('Content-Type', 'text/json');
	}

	// Buscar los clientes por string de busqueda.
	public function cedula(string $id)
	{
		$usuario = Cliente::select('*')
			->where('identificacion', '=', $id)
			->first();
		if (!$usuario) $usuario = "null";
		return response($usuario, 200)->header('Content-Type', 'text/json');
	}

	// Buscara configuraciones del dispositivo seleccionado.
	public function configuraciones(string $id)
	{
		$configuraciones	= DB::table('tb_config_disp')
			->select('*')
			->where('iddispositivo', '=', $id)
			->get();
			if (count($configuraciones) == 0) $configuraciones = "null";
		return response($configuraciones, 200)->header('Content-Type', 'text/json');
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// DB::transaction(function () use ($request, $id) {
		// 	$MapaDeZona = new MapaDeZona();
		// 	$MapaDeZona->idcliente = $id;
		// 	$MapaDeZona->idcodigo = $request->code_map;
		// 	$MapaDeZona->direccion = $request->address;
		// 	$MapaDeZona->punto_referencia = $request->references;
		// 	$MapaDeZona->cedula_asesor = session('user')->cedula;
		// 	$MapaDeZona->observaciones = $request->observation;
		// 	$MapaDeZona->save();

		// 	// for ($var = 0; $var < count($request->cedula_); $var++) {
		// 	// 	// Consultamos si ya existe el contacto registrado en la tabla de clientes.
		// 	// 	if ($contact = Cliente::find($request->cedula_[$var])) {
		// 	// 	} else {
		// 	// 		$contact = new Client();
		// 	// 		$contact->identificacion = $request->cedula_[$var];
		// 	// 		$contact->tipo_cliente = "N";
		// 	// 		$contact->estatus = "A";
		// 	// 	}

		// 	// 	// [Registramos|Actualizamos] los datos del contacto.
		// 	// 	$contact->nombre_completo = $request->fullname_[$var];
		// 	// 	$contact->telefono1 = $request->phone_[$var];
		// 	// 	$contact->save();

		// 	// 	// Lo agregamos como contacto del cliente en el mapa de zona.
		// 	// 	$contact_dt = new Contacts();
		// 	// 	$contact_dt->id_cliente = $request->cedula_[$var];
		// 	// 	$contact_dt->id_codigo = $request->code_map;
		// 	// 	$contact_dt->contrasena = $request->password_[$var];
		// 	// 	$contact_dt->observacion = $request->note_[$var];
		// 	// 	$contact_dt->save();
		// 	// }
		// });

		// return redirect()->route('mapa_de_zonaindex')->with('success', '¡Mapa de zona registrado exitosamente!');
	}

	// Display the specified resource. 
	public function show(string $id)
	{
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
			$MapaDeZona = MapaDeZona::find($id);
			$MapaDeZona->direccion = $request->address;
			$MapaDeZona->punto_referencia = $request->references;
			$MapaDeZona->observaciones = $request->observation;
			$MapaDeZona->save();

			/*
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
			*/
		});

		return redirect()->route('mapa_de_zonaindex')->with('success', '¡Mapa de zona actualizado exitosamente!');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}
}
