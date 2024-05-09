<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ConfiguracionDis;
use App\Models\Dispositivo;
use App\Models\MapaDeZona;
use App\Models\Contacto;
use App\Models\Instaladores;
use App\Models\Personal;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spipu\Html2Pdf\Html2Pdf;

class MapaDeZonaControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 38;
	public $idservicio_disp = 17;
	public $idservicio_conf = 21;
	public $lista_contratos = [
		"Comercios" => [
			"1" => "Comercios - Cantv|Telular|Inter",
			"5" => "Comercios - Radio",
		],
		"Residencias" => [
			"2" => "Residencias - Cantv|Telular|Inter",
			"6" => "Residencias - Radio",
		],
		"Oficinas" => [
			"3" => "Oficinas - Cantv|Telular|Inter",
			"7" => "Oficinas - Radio",
		],
		"Industrias" => [
			"4" => "Industrias - Cantv|Telular|Inter",
			"8" => "Industrias - Radio",
		],
	];
	public $tipos_identificaciones = [
		"C" => "CÉDULA",
		"R" => "RIF"
	];
	public $lista_cedula = [
		"V",
		"E"
	];
	public $lista_rif = [
		"V",
		"E",
		"J",
		"P",
		"G"
	];
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
	public $tiposDispositivos = [
		"Z" => "General",
		"T"	=> "Teclado",
	];
	public $canales_reportes = [
		"Cantv",
		"Internet",
		"Telular",
		"Inter",
		"Radio",
		"IP150",
	];
	public $lineas = [
		"L1",
		"L2",
		"L3",
		"L4",
	];

	// Display a listing of the resource. 
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$mapas_de_zonas	= DB::table('tb_mapa_zonas')
			->select('tb_mapa_zonas.*', 'tb_clientes.nombre AS cliente', 'tb_personal.nombre AS asesor')
			->join('tb_clientes', 'tb_mapa_zonas.idcliente', '=', 'tb_clientes.identificacion')
			->join('tb_personal', 'tb_mapa_zonas.cedula_asesor', '=', 'tb_personal.cedula')
			->get();
		return view('mapa_de_zona.index', [
			'permisos' => $permisos,
			'mapas_de_zonas' => $mapas_de_zonas
		]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$crear_disp = $this->verificar_acceso_servicio_metodo($this->idservicio_disp, 'create'); // Buscando también si tiene permiso para registro en este submódulo [dispositivos].
		$crear_conf = $this->verificar_acceso_servicio_metodo($this->idservicio_conf, 'create'); // Buscando también si tiene permiso para registro en este submódulo [configuraciones].
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'create')) {
			return $this->error403();
		}

		// Cargamos la vista para registrar un nuevo mapa de zona con los datos necesarios.
		$dispositivos	= Dispositivo::all();
		$configuraciones = ConfiguracionDis::all();
		$personal = Personal::all();
		return view('mapa_de_zona.registrar', [
			'lista_contratos' => $this->lista_contratos,
			'tipos_identificaciones' => $this->tipos_identificaciones,
			'lista_cedula' => $this->lista_cedula,
			'lista_rif' => $this->lista_rif,
			'lista_prefijos' => $this->lista_prefijos,
			'crear_dispositivo' => $crear_disp,
			'crear_configuracion' => $crear_conf,
			'dispositivos' => $dispositivos,
			"tipos_dispositivos" => $this->tiposDispositivos,
			"configuraciones" => $configuraciones,
			'canales_reportes' => $this->canales_reportes,
			'lineas' => $this->lineas,
			'personal' => $personal,
		]);
	}

	// Consultamos el código que continua para el registro del mapa de zona según el tipo de contrato.
	public function codigo(string $id)
	{
		// Establecemos el inicio y el fin por defecto de 1000 números [1000-1999, 2000-2900]
		// [Excepciones Oficinas Radio [7000-7399]|Industria Radio [7400-7999]].
		$inicio	= $id . "000";
		$final	= $id . "999";
		if ($id == 7) { // Oficina radio [hasta el 7399].
			$final	= "7399";
		} else if ($id == 8) { // Industria radio [desde el 7400 hasta el 7999].
			$id = 7;
			$inicio	= "7400";
			$final	= "7999";
		}

		// Consultamos el ultimo código registrado según el tipo de contrato [1000, 2000, 3000, 7000];
		$resultado = DB::table('tb_mapa_zonas')
			->select('idcodigo')
			->where('idcodigo', '>=', $inicio)
			->where('idcodigo', '<=', $final)
			->orderBy('idcodigo', 'asc')
			->get();

		// Verificamos si existe un ultimo número o sumamos uno si ya existe.
		if ($resultado) {
			// Recorremos todos los código que ya se encuentran registrados y los guardamos en un arreglo.
			$lista_codigos = [];
			foreach ($resultado as $data) {
				$lista_codigos[] = intval($data->idcodigo);
			}

			// Realizamos un for desde el inicio del código hasta su tope [1000-1999].
			for ($var = intval($inicio); $var < intval($final); $var++) {
				// Verificamos si ya se encuentra en el arreglo para validar si esta disponible o ya se encuentra ocupado por otro cliente.
				if (!in_array($var, $lista_codigos)) {
					$codigo = $var;
					break; // Rompemos el for para evitar que siga si ya encontro un numero disponible.
				}
			}
		} else {
			// Si no hay un solo código registrado por este tipo de contrato, establecemos uno por defecto para iniciar.
			$codigo = $id . "000";
			if ($id == 7) { // Oficina radio [hasta el 7399].
				$codigo = "7000";
			} else if ($id == 8) { // Industria radio [desde el 7400 hasta el 7999].
				$codigo = "7400";
			}
		}

		// Retornamos el código capturado.
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
		$configuraciones	= ConfiguracionDis::select('tb_config_disp.*')
			->join('tb_detalles_conf', 'tb_config_disp.idconfiguracion', 'tb_detalles_conf.idconfiguracion')
			->where('tb_detalles_conf.iddispositivo', '=', $id)
			->get();
		if (count($configuraciones) == 0) $configuraciones = "null";
		return response($configuraciones, 200)->header('Content-Type', 'text/json');
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'create')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para registrar!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Ejecutamos una nueva transacción.
		try {
			// Llamamos la función transaction para procesar la operación como una transacción.
			DB::transaction(function () use ($request) {
				$mapa_de_zona = new MapaDeZona();

				// Detalles cliente y contrato.
				$mapa_de_zona->idcodigo = $request->m_codigo;
				$mapa_de_zona->registro = $request->m_ingreso;
				$mapa_de_zona->tipocontrato = $request->m_tipo_contrato;
				$mapa_de_zona->idcliente = $request->id_cliente;
				$mapa_de_zona->direccion = $request->c_direccion;
				$mapa_de_zona->referencia = $request->c_referencia;
				$mapa_de_zona->cedula_asesor = auth()->user()->cedula;
				$mapa_de_zona->observaciones = $request->m_observacion;
				$mapa_de_zona->monitoreo_contratado = $request->m_monitoreo;
				$mapa_de_zona->monitoreo_estatus = "A";

				// Detalles técnicos.
				if (!isset($request->omitir_datos_tecnicos)) {
					// Telefono asignado.
					$telefono_as = "";
					if ($request->m_reporta == 0) {
						$telefonox		= "(" . $request->c_prefijo_telefono_asg . ") " . $request->c_telefono_assig;
						$telefono_as	= $telefonox != "() " ? $telefonox : "";
					}

					// Guardamos los datos técnicos agregados por el usuario.
					$mapa_de_zona->panel_version = $request->m_panel_version;
					$mapa_de_zona->idteclado = $request->m_teclado;
					$mapa_de_zona->reporta_por = $request->m_reporta;
					$mapa_de_zona->telefono_asig = $telefono_as;
					$mapa_de_zona->fecha_instalacion = $request->m_instalacion;
					$mapa_de_zona->fecha_entrega = $request->m_entrega;
					$mapa_de_zona->ubicacion_panel = $request->m_ubicacion_panel;
					$mapa_de_zona->particiones_sistema = $request->m_particiones;
					$mapa_de_zona->imei = $request->m_imei;
					$mapa_de_zona->linea_principal = $request->m_linea_principal;
					$mapa_de_zona->linea_respaldo = $request->m_linea_respaldo;
				}
				// Guardamos todos los cambios.
				$mapa_de_zona->save();

				// Instalamos los tecnicos encargados de instalar.
				if (isset($request->cedula_instalador)) {
					for ($var = 0; $var < count($request->cedula_instalador); $var++) {
						$instalador = new Instaladores();
						$instalador->cedula = $request->cedula_instalador[$var];
						$instalador->idcodigo = $request->m_codigo;
						$instalador->save();
					}
				}

				// Registramos los usuarios de contacto.
				$var2 = 0;
				if (isset($request->usuario_rcedula)) {
					for ($var = 0; $var < count($request->usuario_rcedula); $var++) { // $var = contador general | $var2 = contador de campos habilitados.
						// Verificamos si ya existe en la base de datos.
						if ($request->usuario_rcedula[$var] == null) {
							// Capturamos los datos del usuario.
							$prefijo		= $request->usuario_prefijo_id[$var2];
							$cedula			= $request->usuario_cedula[$var2];
							$nombre			= $request->usuario_nombre[$var2];
							$prefijo_t	= $request->usuario_prefijotl[$var2];
							$telefono		= $request->usuarios_telefono[$var2];
							$var2++;

							// Concatenamos los valores.
							$identificacion	= $prefijo . "-" . $cedula;
							$telefono = "(" . $prefijo_t . ") " . $telefono;

							// Verificamos si existe registrado en la base de datos.
							$existe	= Cliente::find($identificacion);
							if (!$existe) {
								// Realizamos el registro del usuario en la tabla cliente.
								$usuario = new Cliente();
								$usuario->identificacion = $identificacion;
								$usuario->tipo_identificacion = "C"; // CÉDULA POR DEFECTO.
								$usuario->nombre = mb_convert_case($nombre, MB_CASE_UPPER);
								$usuario->telefono1 = $telefono;
								$usuario->direccion = "-";
								$usuario->save();
							}
						} else {
							$identificacion = $request->usuario_rcedula[$var];
						}

						// Agregamos como contacto del cliente en el mapa de zona.
						$contacto = new Contacto();
						$contacto->idcliente = $identificacion;
						$contacto->contrasena = $request->usuario_contrasena[$var];
						$contacto->nota = $request->usuarios_nota[$var];
						$contacto->idcodigo = $request->m_codigo;
						$contacto->save();
					}
				}

				// Registramos las zonas en el mapa.
				if (isset($request->zona_descripcion)) {
					for ($var = 0; $var < count($request->zona_descripcion); $var++) {
						// Agregamos la zona en el mapa de zona.
						$zona = new Zona();
						$zona->zona = mb_convert_case($request->zona_descripcion[$var], MB_CASE_UPPER);
						$zona->iddispositivo = $request->zona_equipos[$var];
						$zona->idconfiguracion = $request->zona_configuracion[$var];
						$zona->nota = $request->zona_nota[$var];
						$zona->idcodigo = $request->m_codigo;
						$zona->save();
					}
				}
			});
		} catch (\Throwable $th) {
			$response = ["status" => "error", "response" => ["message" => "¡Ocurrió un error al registrar el mapa de zona!", "error" => "Línea: " . $th->getLine() .  " - Error: " . $th->getMessage()]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Mapa de zona registrado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Display the specified resource. 
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$crear_disp = $this->verificar_acceso_servicio_metodo($this->idservicio_disp, 'create'); // Buscando también si tiene permiso para registro en este submódulo [dispositivos].
		$crear_conf = $this->verificar_acceso_servicio_metodo($this->idservicio_conf, 'create'); // Buscando también si tiene permiso para registro en este submódulo [configuraciones].
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'update')) {
			return $this->error403();
		}

		// Cargamos la vista para registrar un nuevo mapa de zona con los datos necesarios.
		$dispositivos	= Dispositivo::all();
		$configuraciones = ConfiguracionDis::all();
		$personal = Personal::all();
		$mapa	= MapaDeZona::select('tb_mapa_zonas.*', 'tb_personal.nombre AS asesor')
			->join('tb_personal', 'tb_mapa_zonas.cedula_asesor', 'tb_personal.cedula')
			->where('idcodigo', '=', $id)
			->first();
		$cliente = Cliente::find($mapa->idcliente);
		$contactos = Contacto::select('tb_contactos.*', 'tb_clientes.nombre', 'tb_clientes.telefono1')
			->join('tb_clientes', 'tb_contactos.idcliente', 'tb_clientes.identificacion')
			->where('tb_contactos.idcodigo', '=', $mapa->idcodigo)
			->get();
		$instaladores = Instaladores::select('tb_personal.*', 'tb_instalacion_tecnicos.iddetalle')
			->join('tb_personal', 'tb_instalacion_tecnicos.cedula', 'tb_personal.cedula')
			->where('tb_instalacion_tecnicos.idcodigo', '=', $mapa->idcodigo)
			->get();
		$zonas = Zona::where('idcodigo', '=', $mapa->idcodigo)->get();
		if ($zonas) {
			for ($var = 0; $var < count($zonas); $var++) {
				$zonas[$var]->configuraciones = ConfiguracionDis::select('tb_config_disp.*')
					->join('tb_detalles_conf', 'tb_config_disp.idconfiguracion', 'tb_detalles_conf.idconfiguracion')
					->where('tb_detalles_conf.iddispositivo', '=', $zonas[$var]->iddispositivo)
					->get();
			}
		}

		// Separar la identificación en tipo y el número.
		$identificacion				= explode('-', $cliente->identificacion);
		$tipo_identificacion	= $identificacion[0];
		$identificacion				= $identificacion[1];
		// Separar el teléfono 1 en prefijo y número.
		$telefono1	= explode(' ', $cliente->telefono1);
		$prefijo_t1	= substr($telefono1[0], 1, 3);
		$telefono1	= $telefono1[1];
		// Separar el teléfono 2 en prefijo y número.
		$telefono2	= "";
		$prefijo_t2	= "";
		if ($cliente->telefono2 != null) {
			$telefono2	= explode(' ', $cliente->telefono2);
			$prefijo_t2	= substr($telefono2[0], 1, 3);
			$telefono2	= $telefono2[1];
		}
		// Actualizamos los datos del objeto.
		$cliente->id	= $identificacion;
		$cliente->ti	= $tipo_identificacion;
		$cliente->pt1	= $prefijo_t1;
		$cliente->tl1	= $telefono1;
		$cliente->pt2	= $prefijo_t2;
		$cliente->tl2	= $telefono2;

		// Separar el teléfono del mapa de zona
		$telefono	= "";
		$prefijo	= "";
		if ($mapa->telefono_asig != null) {
			$telefono	= explode(' ', $mapa->telefono_asig);
			$prefijo	= substr($telefono[0], 1, 3);
			$telefono	= $telefono[1];
		}
		// Actualizamos los dato del telefono en caso de existir datos.
		$mapa->pt = $prefijo;
		$mapa->tl = $telefono;

		// Retornamos la vista con todos los datos correspondientes.
		return view('mapa_de_zona.modificar', [
			'lista_contratos' => $this->lista_contratos,
			'tipos_identificaciones' => $this->tipos_identificaciones,
			'lista_cedula' => $this->lista_cedula,
			'lista_rif' => $this->lista_rif,
			'lista_prefijos' => $this->lista_prefijos,
			'crear_dispositivo' => $crear_disp,
			'crear_configuracion' => $crear_conf,
			'dispositivos' => $dispositivos,
			"tipos_dispositivos" => $this->tiposDispositivos,
			"configuraciones" => $configuraciones,
			'canales_reportes' => $this->canales_reportes,
			'lineas' => $this->lineas,
			'personal' => $personal,
			// Datos del mapa de zona.
			"mapa" => $mapa,
			"cliente" => $cliente,
			"contactos" => $contactos,
			"zonas" => $zonas,
			"insts" => $instaladores,
		]);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'update')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para modificar!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Ejecutamos una nueva transacción.
		try {
			// Llamamos la función transaction para procesar la operación como una transacción.
			DB::transaction(function () use ($request, $id) {
				$mapa_de_zona = MapaDeZona::find($id); // Consultamos el objeto del mapa de zona.

				// Detalles cliente y contrato.
				$mapa_de_zona->direccion = $request->c_direccion;
				$mapa_de_zona->referencia = $request->c_referencia;
				$mapa_de_zona->observaciones = $request->m_observacion;
				$mapa_de_zona->monitoreo_contratado = $request->m_monitoreo;
				// $mapa_de_zona->monitoreo_estatus = "A";

				// Detalles técnicos.
				if (!isset($request->omitir_datos_tecnicos)) {
					// Telefono asignado.
					$telefono_as = "";
					if ($request->m_reporta == 0) {
						$telefonox		= "(" . $request->c_prefijo_telefono_asg . ") " . $request->c_telefono_assig;
						$telefono_as	= $telefonox != "() " ? $telefonox : "";
					}

					// Guardamos los datos técnicos agregados por el usuario.
					$mapa_de_zona->panel_version = $request->m_panel_version;
					$mapa_de_zona->idteclado = $request->m_teclado;
					$mapa_de_zona->reporta_por = $request->m_reporta;
					$mapa_de_zona->telefono_asig = $telefono_as;
					$mapa_de_zona->fecha_instalacion = $request->m_instalacion;
					$mapa_de_zona->fecha_entrega = $request->m_entrega;
					$mapa_de_zona->ubicacion_panel = $request->m_ubicacion_panel;
					$mapa_de_zona->particiones_sistema = $request->m_particiones;
					$mapa_de_zona->imei = $request->m_imei;
					$mapa_de_zona->linea_principal = $request->m_linea_principal;
					$mapa_de_zona->linea_respaldo = $request->m_linea_respaldo;
				}
				// Guardamos todos los cambios.
				$mapa_de_zona->save();

				// Eliminamos los instaladores de la base de datos que haya eliminado el usuario desde la interfaz.
				$idinstaladores = isset($request->idinstalador) ? $request->idinstalador : []; // Si no existe, quiere decir que elimino todos por lo que dejamos solo un array vacío.
				Instaladores::where('idcodigo', '=', $id)->whereNotIn('cedula', $idinstaladores)->delete();

				// Instalamos los tecnicos encargados de instalar.
				if (isset($request->cedula_instalador)) {
					for ($var = 0; $var < count($request->cedula_instalador); $var++) {
						if ($request->idinstalador[$var] == null) {
							$instalador = new Instaladores();
							$instalador->cedula = $request->cedula_instalador[$var];
							$instalador->idcodigo = $id;
							$instalador->save();
						}
					}
				}

				// Eliminamos los contactos de la base de datos que haya eliminado el usuario desde la interfaz.
				$idcontactos = isset($request->idcontacto) ? $request->idcontacto : []; // Si no existe, quiere decir que elimino todos por lo que dejamos solo un array vacío.
				Contacto::where('idcodigo', '=', $id)->whereNotIn('idcontacto', $idcontactos)->delete();

				// Verificamos primero si hay contactos registrados en el mapa de zona.
				$var2 = 0;
				if (isset($request->usuario_rcedula)) {
					for ($var = 0; $var < count($request->usuario_rcedula); $var++) { // $var = contador general | $var2 = contador de campos habilitados.
						if ($request->idcontacto[$var] == null) { // Verificamos si no tiene un ID registrado en la base de datos.
							if ($request->usuario_rcedula[$var] == null) { // Verificamos si no existe en la base de datos para registrarlo.
								// Capturamos los datos del usuario.
								$prefijo		= $request->usuario_prefijo_id[$var2];
								$cedula			= $request->usuario_cedula[$var2];
								$nombre			= $request->usuario_nombre[$var2];
								$prefijo_t	= $request->usuario_prefijotl[$var2];
								$telefono		= $request->usuarios_telefono[$var2];
								$var2++;

								// Concatenamos los valores.
								$identificacion	= $prefijo . "-" . $cedula;
								$telefono = "(" . $prefijo_t . ") " . $telefono;

								// Verificamos si existe registrado en la base de datos.
								$existe	= Cliente::find($identificacion);
								if (!$existe) {
									// Realizamos el registro del usuario en la tabla cliente.
									$usuario = new Cliente();
									$usuario->identificacion = $identificacion;
									$usuario->tipo_identificacion = "C"; // CÉDULA POR DEFECTO.
									$usuario->nombre = mb_convert_case($nombre, MB_CASE_UPPER);
									$usuario->telefono1 = $telefono;
									$usuario->direccion = "-";
									$usuario->save();
								}
							} else { // De lo contrario solo tomamos la Cédula/RIF y la guardamos en la variable identificacion.
								$identificacion = $request->usuario_rcedula[$var];
							}

							// Agregamos como contacto del cliente en el mapa de zona.
							$contacto = new Contacto();
							$contacto->idcliente = $identificacion;
							$contacto->contrasena = $request->usuario_contrasena[$var];
							$contacto->nota = $request->usuarios_nota[$var];
							$contacto->idcodigo = $id;
							$contacto->save();
						} else { // De lo contrario solo actualizamos los datos existentes.
							$contacto = Contacto::find($request->idcontacto[$var]);
							$contacto->contrasena = $request->usuario_contrasena[$var];
							$contacto->nota = $request->usuarios_nota[$var];
							$contacto->save();
						}
					}
				}

				// Eliminamos las zonas de la base de datos que haya eliminado el usuario desde la interfaz.
				$idzonas = isset($request->idzona) ? $request->idzona : []; // Si no existe, quiere decir que elimino todos por lo que dejamos solo un array vacío.
				Zona::where('idcodigo', '=', $id)->whereNotIn('idzona', $idzonas)->delete();

				// Verificamos primero si hay zonas registradas en el mapa.
				if (isset($request->idzona)) {
					for ($var = 0; $var < count($request->idzona); $var++) { // Recorremos cada uno de los registros agregados en el documento.
						if ($request->idzona[$var] == null) { // Verificamos si no tiene un ID registrado en la base de datos.
							// Procedemos a registrar una nueva zona.
							$zona = new Zona();
							$zona->zona = mb_convert_case($request->zona_descripcion[$var], MB_CASE_UPPER);
							$zona->iddispositivo = $request->zona_equipos[$var];
							$zona->idconfiguracion = $request->zona_configuracion[$var];
							$zona->nota = $request->zona_nota[$var];
							$zona->idcodigo = $id;
							$zona->save();
						} else { // De lo contrario solo actualizamos los datos existentes.
							$zona = Zona::find($request->idzona[$var]);
							$zona->zona = mb_convert_case($request->zona_descripcion[$var], MB_CASE_UPPER);
							$zona->iddispositivo = $request->zona_equipos[$var];
							$zona->idconfiguracion = $request->zona_configuracion[$var];
							$zona->nota = $request->zona_nota[$var];
							$zona->save();
						}
					}
				}
			});
		} catch (\Throwable $th) {
			$response = ["status" => "error", "response" => ["message" => "¡Ocurrió un error al registrar el mapa de zona!", "error" => "Línea: " . $th->getLine() .  " - Error: " . $th->getMessage()]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Retoramos mensaje de exito al usuario.
		$response = ["status" => "success", "response" => ["message" => "¡Mapa de zona registrado exitosamente!"]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}

	// Generar pdf.
	public function generar_pdf(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'generar_pdf')) {
			return $this->error403();
		}

		// Consultamos los datos de todo el mapa de zona.
		$mapa = MapaDeZona::find($id);
		$cliente = Cliente::find($mapa->idcliente);
		$asesor = Personal::find($mapa->cedula_asesor);
		$usuarios = Contacto::select('tb_contactos.*', 'tb_clientes.nombre', 'tb_clientes.telefono1', 'tb_clientes.telefono2')
			->join('tb_clientes', 'tb_contactos.idcliente', 'tb_clientes.identificacion')
			->where('idcodigo', '=', $id)
			->get();
		$zonas = Zona::select('tb_zonas.*', 'tb_dispositivos.dispositivo', 'tb_config_disp.configuracion')
			->join('tb_dispositivos', 'tb_zonas.iddispositivo', 'tb_dispositivos.iddispositivo')
			->join('tb_config_disp', 'tb_zonas.idconfiguracion', 'tb_config_disp.idconfiguracion')
			->where('tb_zonas.idcodigo', '=', $id)
			->get();
		$instaladores = Instaladores::select('tb_instalacion_tecnicos.*', 'tb_personal.nombre')
			->join('tb_personal', 'tb_instalacion_tecnicos.idcodigo', 'tb_personal.cedula')
			->where('idcodigo', '=', $id)
			->get();

		// Generamos el nuevo PDF.
		$pdf	= view('mapa_de_zona/pdf_mapa_de_zona', [
			"mapa" => $mapa,
			"cliente" => $cliente,
			"asesor" => $asesor,
			"usuarios" => $usuarios,
			"zonas" => $zonas,
			"instaladores" => $instaladores,
		]);
		$html2pdf = new Html2Pdf('P', 'LETTER', 'es'); // Orientación [P=Vertical|L=Horizontal] | TAMAÑO [LETTER = CARTA] | Lenguaje [es]
		$html2pdf->pdf->SetTitle('Mapa de zona');
		$html2pdf->writeHTML($pdf);
		$html2pdf->output('mapa_de_zona.pdf');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}

	// Update status.
	public function toggle(string $id)
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		if (!$this->verificar_acceso_servicio_metodo($this->idservicio, 'toggle')) {
			$response = ["status" => "error", "response" => ["message" => "¡No tiene permiso para cambiar el estatus!"]];
			return response($response, 200)->header('Content-Type', 'text/json');
		}

		// Consultamos el registro a actualizar el estatus.
		$departamento = MapaDeZona::find($id);
		$departamento->estatus = $departamento->estatus != "A" ? "A" : "I";
		$departamento->save();

		// Enviamos un mensaje de exito al usuario.
		$message	= $departamento->estatus == "A" ? "¡Estatus cambiado a activo!" : "¡Estatus cambiado a inactivo!";
		$response = ["status" => "success", "response" => ["message" => $message]];
		return response($response, 200)->header('Content-Type', 'text/json');
	}
}
