<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SesionControlador extends Controller
{
	public function formulario_iniciar_sesion()
	{
		return view('sesion.index');
	}

	public function iniciar_sesion(Request $request)
	{
		// Consultamos la información del usuario en la base de datos.
		$usuario	= DB::table('tb_usuarios')
			->join('tb_personal', 'tb_usuarios.cedula', '=', 'tb_personal.cedula')
			->select('tb_personal.*', 'tb_usuarios.usuario', 'tb_usuarios.idusuario', 'tb_usuarios.estatus', 'tb_usuarios.contrasena')
			->where('tb_usuarios.usuario', $request->usuario)->first();

		// Validamos si encontró el usuario solicitado.
		if (!$usuario) return redirect('iniciar_sesion')->with('error', '¡El usuario no se encuentra registrado!');

		// Comprobamos que la contraseña sea correcta.
		if (!password_verify($request->contrasena, $usuario->contrasena)) return redirect('iniciar_sesion')->with('error', '¡La contraseña ingresada es incorrecta!');

		// Guardamos la sesión y redireccionamos al panel principal.
		session(['usuario' => $usuario]);
		return redirect('/');
	}

	public function cerrar_sesion()
	{
		session(['usuario' => null]);
		return redirect('/');
	}

	public function showRecover()
	{
		return view('session.recover');
	}
}
