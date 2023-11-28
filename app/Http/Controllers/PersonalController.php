<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\Session;
use App\Models\TypePersonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
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
		$types = TypePersonal::all();
		return view('personal.create', ['types_of_personal' => $types]);
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		DB::transaction(function () use ($request) {
			$personal = new Personal();
			$personal->cedula = $request->cedula;
			$personal->nombres = $request->firstname;
			$personal->apellidos = $request->lastname;
			$personal->correo = $request->email;
			$personal->telefono1 = $request->phone1;
			$personal->telefono2 = $request->phone2;
			$personal->direccion = $request->address;
			$personal->id_tipo = $request->type_personal;
			$personal->save();

			$usuario = new Session();
			$usuario->cedula = $request->cedula;
			$usuario->usuario = explode(' ', $request->firstname)[0] . explode(' ', $request->lastname)[0] . rand(4, 4);
			$usuario->contrasena = password_hash($request->cedula, PASSWORD_DEFAULT);
			$usuario->save();
		});

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
		$types = TypePersonal::all();

		return view('personal.update', ['personal' => $personal, 'types_of_personal' => $types]);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		DB::transaction(function () use ($request, $id) {
			$personal = Personal::find($id);
			$personal->cedula = $request->cedula;
			$personal->nombres = $request->firstname;
			$personal->apellidos = $request->lastname;
			$personal->correo = $request->email;
			$personal->telefono1 = $request->phone1;
			$personal->telefono2 = $request->phone2;
			$personal->direccion = $request->address;
			$personal->id_tipo = $request->type_personal;
			$personal->save();
		});

		return redirect()->route('personal.index')->with('success', 'Personal actualizado exitosamente');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}
}
