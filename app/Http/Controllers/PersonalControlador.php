<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\Session;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class PersonalControlador extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$personals = DB::table('tb_personal')
			->join('tb_usuarios', 'tb_personal.cedula', '=', 'tb_usuarios.cedula')
			->select('tb_personal.*', 'tb_usuarios.usuario')
			->get();
		return view('personal.index', ['personals' => $personals]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		$types = Position::all();
		return view('personal.create', ['positions' => $types]);
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		try {
			DB::transaction(function () use ($request) {
				$personal = new Personal();
				$personal->cedula = $request->cedula;
				$personal->nombres = $request->firstname;
				$personal->apellidos = $request->lastname;
				$personal->correo = $request->email;
				$personal->telefono1 = $request->phone1;
				$personal->telefono2 = $request->phone2;
				$personal->direccion = $request->address;
				$personal->id_cargo = $request->id_position;
				$personal->save();
	
				$usuario = new Session();
				$usuario->cedula = $request->cedula;
				$usuario->usuario = explode(' ', $request->firstname)[0] . explode(' ', $request->lastname)[0] . rand(4, 4);
				$usuario->contrasena = password_hash($request->cedula, PASSWORD_DEFAULT);
				$usuario->save();
			});
		} catch (\Throwable $th) {
			return redirect()->route('personal.index')->with('error', 'Ocurrió un error al registrar el personal');
		}

		return redirect()->route('personal.index')->with('success', 'Personal creado exitosamente');
	}

	// Display the specified resource. 
	public function show(string $id)
	{
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
		$personal = Personal::find($id);
		$types = Position::all();

		return view('personal.update', ['personal' => $personal, 'positions' => $types]);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		try {
			DB::transaction(function () use ($request, $id) {
				$personal = Personal::find($id);
				$personal->cedula = $request->cedula;
				$personal->nombres = $request->firstname;
				$personal->apellidos = $request->lastname;
				$personal->correo = $request->email;
				$personal->telefono1 = $request->phone1;
				$personal->telefono2 = $request->phone2;
				$personal->direccion = $request->address;
				$personal->id_cargo = $request->id_position;
				$personal->save();
			});
		} catch (\Throwable $th) {
			return redirect()->route('personal.index')->with('error', 'Ocurrió un error al actualizar el personal');
		}

		return redirect()->route('personal.index')->with('success', 'Personal actualizado exitosamente');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}
}
