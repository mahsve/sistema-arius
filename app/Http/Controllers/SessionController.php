<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
	public function show_login()
	{
		return view('session.login');
	}

	public function login(Request $request)
	{
		// Consultamos la información del usuario.
		$user	= Session::where('usuario', $request->username)->first();

		// Validamos si encontró el usuario solicitado.
		if (!$user) return redirect('iniciar-sesion')->with('error', 'El usuario no se encuentra registrado');

		// Comprobamos que la contraseña sea correcta.
		if (!password_verify($request->password, $user->contrasena)) return redirect('iniciar-sesion')->with('error', 'La contraseña ingresada es incorrecta');
		
		// Guardamos la sesión y redireccionamos al panel principal.
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
