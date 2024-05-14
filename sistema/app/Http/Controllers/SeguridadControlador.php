<?php

namespace App\Http\Controllers;

use App\Models\RolModulo;
use App\Models\RolServicio;

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

	public function verificar_acceso_servicio_metodo(string $idservicio, string $metodo = null)
	{
		$idrol = auth()->user()->idrol;
		return RolServicio::select('tb_rol_servicio.idservicio', 'tb_servicios.menu_url')
			->join('tb_servicios', 'tb_rol_servicio.idservicio', 'tb_servicios.idservicio')
			->where('tb_rol_servicio.idrol', '=', $idrol)
			->where('tb_servicios.menu_url', '=', $metodo)
			->Where('tb_servicios.idservicio_raiz', '=', $idservicio)
			->first();
	}

	public function verificar_acceso_servicio_full(string $idservicio)
	{
		$idrol = auth()->user()->idrol;
		$resultados = RolServicio::select('tb_servicios.idservicio', 'tb_servicios.servicio', 'tb_servicios.menu_url', 'tb_servicios.idservicio_raiz')
			->join('tb_servicios', 'tb_rol_servicio.idservicio', 'tb_servicios.idservicio')
			->where('tb_rol_servicio.idrol', '=', $idrol)
			->whereRaw("(`tb_servicios`.`idservicio`=? or `tb_servicios`.`idservicio_raiz`=?)", [$idservicio, $idservicio])
			->get();

		// Recorremos los resultados encontrados y los ordenamos en un arreglo asociativo con el metodo como indice para acceder a el mas facilmente.
		$servicios = [];
		foreach ($resultados as $servicio) {
			$metodo = $servicio->menu_url;
			if ($servicio->idservicio_raiz == null) $metodo = "index";

			// Creamos una nueva posición en el arreglo utilizando el nombre del metodo como indice y dentro el nombre del servicio.
			$servicios[$metodo] = $servicio->servicio;
		}

		// Retornamos los servicios conseguidos al usuario.
		$servicios = json_decode(json_encode($servicios, JSON_FORCE_OBJECT));
		return $servicios;
	}

	public function error403()
	{
		return view('error403');
	}
}
