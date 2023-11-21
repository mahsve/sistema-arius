<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
	public function __invoke()
	{
		if (session('user')) {
			return view('dashboard.index');
		} else {
			return redirect('/iniciar-sesion');
		}
	}
}
