<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BitacoraControlador extends Controller
{
	use SeguridadControlador;

	// Atributos de la clase.
	public $idservicio = 9;

	public function __invoke()
	{
		// Verificamos primeramente si tiene acceso al metodo del controlador.
		$permisos = $this->verificar_acceso_servicio_full($this->idservicio);
		if (!isset($permisos->index)) {
			return $this->error403();
		}

		// Consultamos los datos necesarios y cargamos la vista.
		return view('bitacora.index', [
			'permisos' => $permisos,
		]);
	}
}
