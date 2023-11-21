<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
	public function showLogin()
	{
		return view('session.login');
	}

	public function login(Request $request)
	{
		// Consultamos la información del usuario.
		$user	= Session::where('usuario', $request->username)->first();
		if (!$user) return redirect()->route('show-login')->with('error', 'El usuario no se encuentra registrado');
		if (!password_verify($request->password, $user->contrasena)) return redirect()->route('show-login')->with('error', 'La contraseña ingresada es incorrecta');
		session(['user' => $user]);
		return redirect('/');
	}

	public function logout()
	{
		session(['user' => null]);
		return redirect('/');
	}

	public function showRecover()
	{
		return view('session.recover');
	}
}
