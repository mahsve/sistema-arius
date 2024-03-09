@extends('template')

@section('title', 'Clientes - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script>
	setTimeout(() => {
		let table = new DataTable('#data-table', {
			language: {
				url: '{{url("js/datatable-languaje-ES.json")}}',
			},
		});
	}, 500);
</script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Clientes</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('clientes.create')}}" class="btn btn-primary btn-sm rounded"><i data-feather="plus"></i> Agregar</a>
		</div>
	</div>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
	<i data-feather="check"></i> {{session('success')}}
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table id="data-table" class="table table-hover m-0">
				<thead>
					<tr>
						<th class="px-2 text-center" width="50px">N°</th>
						<th class="px-2" width="100px">Tipo cliente</th>
						<th class="px-2" width="70px">Identificación</th>
						<th class="px-2">Cliente</th>
						<th class="px-2">Teléfono</th>
						<th class="px-2">Correo electrónico</th>
						<th class="px-2">Registrado</th>
						<th class="px-2 text-center"><i data-feather="settings" width="14px" height="14px"></i></th>
					</tr>
				</thead>

				<tbody>
					@foreach ($clients as $index => $client)
					<tr>
						<td class="px-2 text-end">{{$index + 1}}</td>
						<td class="px-2">{{$client->tipo_cliente == "N" ? "Natural" : "Jurídico"}}</td>
						<td class="px-2">{{$client->identificacion}}</td>
						<td class="px-2">{{$client->nombre_completo}}</td>
						<td class="px-2">{{$client->telefono1}}</td>
						<td class="px-2">{{$client->correo_electronico}}</td>
						<td class="px-2">{{date('h:i:s A d/m/y', strtotime($client->created))}}</td>
						<td class="p-2" style="width: 20px;">
							<a href="{{route('clientes.edit', ['cliente' => $client->identificacion])}}" class="btn btn-primary btn-sm rounded p-2"><i data-feather="edit"></i></a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection