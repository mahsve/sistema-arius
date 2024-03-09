<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
	// Display a listing of the resource. 
	public function index()
	{
		$clients = Client::all();
		return view('client.index', ['clients' => $clients]);
	}

	// Show the form for creating a new resource. 
	public function create()
	{
		return view('client.create');
	}

	// Store a newly created resource in storage
	public function store(Request $request)
	{
		$request->validate([
			'identification'	=> 'required|min:8|max:11',
			'kind_of_client'	=> 'required',
			'fullname'				=> 'required|min:3|max:120',
			'email'						=> 'required|min:3|max:60',
			'phone1'					=> 'required|max:11'
		]);
		$department = new Client();
		$department->identificacion	= $request->identification;
		$department->tipo_cliente	= $request->kind_of_client;
		$department->nombre_completo	= $request->fullname;
		$department->correo_electronico	= $request->email;
		$department->telefono1	= $request->phone1;
		$department->telefono2	= $request->phone2;
		$department->save();
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
		$client = Client::find($id);
		return view('client.update', ['client' => $client]);
	}

	// Update the specified resource in storage. 
	public function update(Request $request, string $id)
	{
		$request->validate([
			'fullname'	=> 'required|min:3|max:120',
			'email'			=> 'required|min:3|max:60',
			'phone1'		=> 'required|max:11'
		]);
		$department = Client::find($id);
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
