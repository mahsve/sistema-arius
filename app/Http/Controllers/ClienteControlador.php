<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteControlador extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$clientes = Cliente::all();
		return view('cliente.index', ['clientes' => $clientes]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		return view('cliente.registrar');
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		// Validaciones
		$request->validate([
			'identification'	=> 'required|min:8|max:11',
			'kind_of_client'	=> 'required',
			'fullname'				=> 'required|min:3|max:120',
			'email'						=> 'required|min:3|max:60',
			'phone1'					=> 'required|max:11'
		]);

		$cliente = new Cliente();
		$cliente->identificacion	= $request->identification;
		$cliente->tipo_cliente	= $request->kind_of_client;
		$cliente->nombre_completo	= $request->fullname;
		$cliente->correo_electronico	= $request->email;
		$cliente->telefono1	= $request->phone1;
		$cliente->telefono2	= $request->phone2;
		$cliente->save();
		return redirect()->route('clientes.index')->with('success', '¡Cliente registrado exitosamente!');
	}

	// Display the specified resource. 
	public function show(string $id)
	{
		return "hola";
	}

	// Show the form for editing the specified resource. 
	public function edit(string $id)
	{
		$client = Cliente::find($id);
		return view('client.modificar', ['client' => $client]);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		$request->validate([
			'fullname'	=> 'required|min:3|max:120',
			'email'			=> 'required|min:3|max:60',
			'phone1'		=> 'required|max:11'
		]);
		$department = Cliente::find($id);
		$department->nombre_completo	= $request->fullname;
		$department->correo_electronico	= $request->email;
		$department->telefono1	= $request->phone1;
		$department->telefono2	= $request->phone2;
		$department->save();
		return redirect()->route('clientes.index')->with('success', '¡Cliente modificado exitosamente!');
	}

	// Remove the specified resource from storage. 
	public function destroy(string $id)
	{
	}
}
