<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoreoController extends Controller
{
	public function index()
	{
		return view('monitoreo');
	}
}
