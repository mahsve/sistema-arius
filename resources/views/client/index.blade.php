@extends('template')

@section('title', 'Clientes - ' . env('TITLE'))

@section('styles')
<link href="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js"></script>
<script>
	let table = new DataTable('#data-table', {
		language: {
			url: '{{url("js/datatable-languaje-ES.json")}}',
		},
	});
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

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table id="data-table" class="table table-hover m-0">
				<thead>
					<tr>
						<th>N°</th>
						<th>Nombres</th>
						<th>Product</th>
						<th>Status</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($clients as $index => $client)
					<tr>
						<td>{{$index + 1}}</td>
						<td>{{$client->nombre}}</td>
						<td>Photoshop</td>
						<td><label class="badge badge-danger">Pending</label></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection