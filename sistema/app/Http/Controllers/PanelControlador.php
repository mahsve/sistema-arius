<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;

class PanelControlador extends Controller
{
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
		return view('panel.index', ['tareas' => []]);
	}

	public function error403()
	{
		return view('panel.error403');
	}
}
