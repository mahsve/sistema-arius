<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanelControlador extends Controller
{
	public function __invoke()
	{
		if (session('usuario')) {
			// Capturamos el ID del usuario.
			$idusuario	= session('usuario')->idusuario;

			// Consultamos las tareas.
			$Tarea	= new TareaControlador();
			$tareas	= $Tarea->tareas($idusuario);

			// return $tareas;
			return view('panel.index', ['tareas' => $tareas]);
		} else {
			return redirect('/iniciar_sesion');
		}
	}
}
