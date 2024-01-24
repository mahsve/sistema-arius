@extends('template')

@section('title', 'Mapas de zonas - ' . env('TITLE'))

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
			<h4 class="card-title m-0">Mapas de zonas</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('mapas-de-zonas.create')}}" class="btn btn-primary btn-sm rounded"><i data-feather="plus"></i> Agregar</a>
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
						<th class="px-2" width="70px">Código</th>
						<th class="px-2">Cliente</th>
						<th class="px-2" width="100px">Tipo cliente</th>
						<th class="px-2">Registrado</th>
						<th class="px-2">Asesor</th>
						<th class="px-2">Estatus</th>
						<th class="px-2 text-center"><i data-feather="settings" width="14px" height="14px"></i></th>
					</tr>
				</thead>

				<tbody>
					@foreach ($clients as $index => $client)
					<tr>
						<td class="px-2 text-end">{{$index + 1}}</td>
						<td class="px-2 text-center">{{$client->id_codigo}}</td>
						<td class="px-2">{{$client->nombre_completo}}</td>
						<td class="px-2">{{$client->tipo_cliente == "N" ? "Natural" : "Jurídico"}}</td>
						<td class="px-2">{{date('h:i:s A d/m/y', strtotime($client->created))}}</td>
						<td class="px-2">{{$client->nombres . " " . $client->apellidos}}</td>
						<td class="p-2">
							@if ($client->estatus == "A")
							<label class="badge badge-success"><i data-feather="check" width="14px" height="14px"></i> Activo</label>
							@elseif ($client->estatus == "R")
							<label class="badge badge-success"><i data-feather="check" width="14px" height="14px"></i> Registrado</label>
							@elseif ($client->estatus == "I")
							<label class="badge badge-success"><i data-feather="check" width="14px" height="14px"></i> Instalado</label>
							@else
							<label class="badge badge-danger"><i data-feather="x" width="14px" height="14px"></i> Inactivo</label>
							@endif
						</td>
						<td class="p-2" style="width: 20px;">
							<a href="{{route('mapas-de-zonas.edit', ['cliente' => $client->id_codigo])}}" class="btn btn-primary btn-sm rounded p-2"><i data-feather="edit"></i></a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection