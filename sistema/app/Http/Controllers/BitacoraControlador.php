<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 37;

	// Mostrar el historial en el HTML.
	public function index()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$mes	= date('m');
		$anio	= date('Y');
		$fecha_inicio	= $anio . "-" . $mes . "-01";
		$fecha_final	= $anio . "-" . $mes . "-" . cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
		if (isset($_GET["fecha_inicio"]) and !empty($_GET["fecha_inicio"]) and isset($_GET["fecha_tope"]) and !empty($_GET["fecha_tope"])) {
			$fecha_inicio	= $_GET["fecha_inicio"];
			$fecha_final	= $_GET["fecha_tope"];
		}

		// Consultamos los datos necesarios y cargamos la vista.
		$historial	= Bitacora::select('tb_bitacora.*', 'tb_usuarios.usuario', 'tb_personal.nombre')
			->join('tb_usuarios', 'tb_bitacora.idusuario', 'tb_usuarios.idusuario')
			->join('tb_personal', 'tb_usuarios.cedula', 'tb_personal.cedula')
			->whereBetween('tb_bitacora.fecha', [$fecha_inicio, $fecha_final])
			->get();
		return view('bitacora.index', [
			'permisos' => $permisos,
			'fecha_inicio' => $fecha_inicio,
			'fecha_final' => $fecha_final,
			'historial' => $historial,
		]);
	}

	// Consulta los detalles de un movimiento para mostrarlos mas detalladamente.
	public function consult(string $id)
	{
		// Consultamos el registro a modificar.
		$bitacora = Bitacora::select('tb_bitacora.*', 'tb_usuarios.usuario', 'tb_personal.nombre')
			->join('tb_usuarios', 'tb_bitacora.idusuario', 'tb_usuarios.idusuario')
			->join('tb_personal', 'tb_usuarios.cedula', 'tb_personal.cedula')
			->where('tb_bitacora.idbitacora', '=', $id)
			->first();
		return response($bitacora, 200)->header('Content-Type', 'text/json');
	}
}
