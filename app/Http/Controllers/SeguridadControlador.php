<?php

namespace App\Http\Controllers;

use App\Models\RolModulo;
use App\Models\RolServicio;
use Illuminate\Http\Request;

trait SeguridadControlador
{
	public function verificar_acceso_modulo(string $idmodulo)
	{
		$idrol = auth()->user()->idrol;
		return RolModulo::select('tb_rol_modulos.idmodulo')
			->join('tb_modulos', 'tb_rol_modulos.idmodulo', 'tb_modulos.idmodulo')
			->where('tb_rol_modulos.idrol', '=', $idrol)
			->where('tb_modulos.idmodulo', '=',)
			->first();
	}

	public function verificar_acceso_servicio(string $idservicio)
	{
		$idrol = auth()->user()->idrol;
		return RolServicio::select('tb_rol_servicio.idservicio')
			->join('tb_servicios', 'tb_rol_servicio.idservicio', 'tb_servicios.idservicio')
			->where('tb_rol_servicio.idrol', '=', $idrol)
			->where('tb_servicios.idservicio', '=', $idservicio)
			->first();
	}

	public function redireccionar_419()
	{
		return route('dashboard.419');
	}
}
