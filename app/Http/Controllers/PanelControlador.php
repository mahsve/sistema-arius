<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanelControlador extends Controller
{
	public function __invoke()
	{
		if (session('usuario')) {
			return view('panel.index');
		} else {
			return redirect('/iniciar_sesion');
		}
	}
}
