<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TypePersonalController extends Controller
{
	public function index()
	{
		return view('type-personal.index');
	}
}
