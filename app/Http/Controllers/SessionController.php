<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
	public function show_login()
	{
		return view('session.login');
	}

	public function login(Request $request)
	{
		// Consultamos la información del usuario.
		$user	= DB::table('tb_usuarios')
			->join('tb_personal', 'tb_usuarios.cedula', '=', 'tb_personal.cedula')
			->select('tb_personal.*', 'tb_usuarios.usuario', 'tb_usuarios.id_usuario', 'tb_usuarios.estatus', 'tb_usuarios.contrasena')
			->where('usuario', $request->username)->first();

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
