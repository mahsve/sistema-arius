<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BitacoraControlador extends Controller
{
	public function __invoke()
	{
		return view('bitacora.index');
	}
}
