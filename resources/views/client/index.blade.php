@extends('template')

@section('title', 'Clientes - ' . env('TITLE'))

@section('content')
<div class="mb-4">
	<div class="row">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Clientes</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{url('registrar-cliente')}}" class="btn btn-primary btn-sm rounded"><i data-feather="plus"></i> Agregar</a>
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
						<th>User</th>
						<th>Product</th>
						<th>Sale</th>
						<th>Status</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($clients as $client)
					<tr>
						<td>1</td>
						<td>Jacob</td>
						<td>Photoshop</td>
						<td class="text-danger"> 28.76% <i class="ti-arrow-down"></i></td>
						<td><label class="badge badge-danger">Pending</label></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection