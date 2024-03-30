<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanelControlador extends Controller
{
	public function __invoke()
	{
		return view('panel.index', ['tareas' => []]);
	}
}
