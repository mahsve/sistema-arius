@extends('plantilla')

@section('title', 'Clientes - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script>
	const table = new DataTable('#data-table', {
		language: {
			url: '{{url("js/datatable/datatable-languaje-ES.json")}}',
		},
	});
	table.on('draw.dt', function () {
		let elemento1 = document.querySelector('table#data-table').parentElement;
		elemento1.classList.add("table-responsive","p-0","mb-3");
	});
</script>
<script id="contenedor_script_variables">
	const url_ = '{{url('/')}}';
	const token_ = '{{csrf_token()}}';

	// Una vez cargado en las constantes, se elimina la etiqueta script por temas de seguridad.
	document.getElementById('contenedor_script_variables').remove();
</script>
<script src="{{url('js/app/cliente/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase m-0"><i class="fas fa-users"></i> Clientes</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('clientes.create')}}" class="btn btn-primary btn-sm"><i class="fas fa-folder-plus me-2"></i>Agregar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table id="data-table" class="table table-hover border-bottom m-0">
				<thead>
					<tr>
						<th class="ps-2"><i class="fas fa-id-badge"></i> Tipo ID</th>
						<th class="ps-2"><i class="fas fa-id-badge"></i> Identificación</th>
						<th class="ps-2"><i class="fas fa-address-card"></i> Cliente</th>
						<th class="ps-2"><i class="fas fa-phone-alt"></i> Teléfono</th>
						<th class="ps-2"><i class="fas fa-envelope"></i> Correo electrónico</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Registrado</th>
						<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
						<th class="ps-2 text-center"><i class="fas fa-toggle-on"></i></th>
						<th class="ps-2 text-center"><i class="fas fa-cogs"></i></th>
					</tr>
				</thead>

				<tbody>
					@foreach ($clientes as $index => $cliente)
					@php
					$idrand = rand(100000,999999);
					@endphp
					<tr>
						<td class="py-1 px-2">{{$tipos_identificaciones[$cliente->tipo_identificacion]}}</td>
						<td class="py-1 px-2">{{$cliente->identificacion}}</td>
						<td class="py-1 px-2">{{$cliente->nombre_completo}}</td>
						<td class="py-1 px-2">{{$cliente->telefono1}}</td>
						<td class="py-1 px-2">{{$cliente->correo_electronico}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($cliente->created))}}</td>
						<td class="py-1 px-2 text-center" id="contenedor_badge{{$idrand}}">
							@if ($cliente->estatus == "A")
								<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@else
								<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						<td class="py-1 px-2 text-center">
							<div class="form-check form-switch form-check-inline m-0">
								<input type="checkbox" class="form-check-input mx-auto switch_estatus" role="switch" id="switch_estatus{{$idrand}}" data-id="{{$idrand}}" value="{{$cliente->identificacion}}" <?= $cliente->estatus == "A" ? "checked" : "" ?>>
							</div>
						</td>
						<td class="py-1 px-2" style="width: 20px;">
							<a href="{{route('clientes.edit', ['id' => $cliente->identificacion])}}" class="btn btn-primary btn-sm p-2"><i class="fas fa-edit"></i></a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection