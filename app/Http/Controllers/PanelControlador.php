<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;

class PanelControlador extends Controller
{
	public function dashboard()
	{
		// Al culminar el inicio de sesión y cargar el panel por primera vez, consultamos los datos del usuario y los guardamos en una sesión.
		if (!isset(session()->personal)) {
			$personal = Personal::find(auth()->user()->cedula);
			session(['personal' => $personal]);
		}
		return view('panel.index', ['tareas' => []]);
	}

	public function error403()
	{
		return view('panel.error403');
	}
}
