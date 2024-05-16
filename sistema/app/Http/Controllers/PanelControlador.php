<?php

namespace App\Http\Controllers;

use App\Models\MapaDeZona;
use App\Models\Personal;
use App\Models\ServicioTecnicoSolicitado;
use Illuminate\Http\Request;

class PanelControlador extends Controller
{
	use SeguridadControlador;

	public $idservicio_cliente = 38;
	public $idservicio_servicio = 43;

	public function dashboard()
	{
		// Al culminar el inicio de sesión y cargar el panel por primera vez, consultamos los datos del usuario y los guardamos en una sesión.
		if (!isset(session('personal')->cedula)) {
			$personal = Personal::select('tb_personal.*', 'tb_cargos.iddepartamento')
				->join('tb_cargos', 'tb_personal.idcargo', 'tb_cargos.idcargo')
				->where('cedula', '=', auth()->user()->cedula)
				->first();
			session(['personal' => $personal]);
		}

		$vista_clientes = $this->verificar_acceso_servicio_full($this->idservicio_cliente); // Buscando también si tiene permiso para registro en este submódulo [departamento].
		$vista_servicio = $this->verificar_acceso_servicio_full($this->idservicio_servicio); // Buscando también si tiene permiso para registro en este submódulo [departamento].
		$clientes_activos = MapaDeZona::where('estatus', '=', 'A')->get();
		$clientes_inactivos = MapaDeZona::where('estatus', '=', 'I')->get();
		$monitoreo_activos = MapaDeZona::where('monitoreo_estatus', '=', 'A')->get();
		$monitoreo_inactivos = MapaDeZona::where('monitoreo_estatus', '=', 'I')->get();
		$servicios_abiertos = ServicioTecnicoSolicitado::where('estatus', '=', 'A')->get();
		return view('panel.index', [
			'vista_clientes' => $vista_clientes,
			'vista_servicio' => $vista_servicio,
			'clientes_activos' => $clientes_activos,
			'clientes_inactivos' => $clientes_inactivos,
			'monitoreo_activos' => $monitoreo_activos,
			'monitoreo_inactivos' => $monitoreo_inactivos,
			'servicios_abiertos' => $servicios_abiertos,
		]);
	}

	public function error403()
	{
		return view('panel.error403');
	}
}
