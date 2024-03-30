@extends('plantilla')

@section('title', 'Personal - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
<script src="{{url('js/app/personal/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase m-0"><i class="fas fa-user-tie"></i> Personal</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('personal.create')}}" class="btn btn-primary btn-sm"><i class="fas fa-folder-plus me-2"></i>Agregar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover" id="data-table">
				<thead>
					<tr>
						<th class="ps-2"><i class="fas fa-id-badge"></i> Cédula</th>
						<th class="ps-2"><i class="fas fa-address-card"></i> Nombre completo</th>
						<th class="ps-2"><i class="fas fa-user"></i> Usuario</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Registrado</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Actualizado</th>
						<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
						<th class="ps-2 text-center"><i class="fas fa-toggle-on"></i></th>
						<th class="ps-2 text-center"><i class="fas fa-cogs"></i></th>
					</tr>
				</thead>

				<tbody>
					@foreach($personal as $index => $persona)
					@php
					$idrand = rand(100000,999999);
					@endphp
					<tr>
						<td class="py-1 px-2">{{$persona->cedula}}</td>
						<td class="py-1 px-2">{{$persona->nombre}}</td>
						<td class="py-1 px-2">{{$persona->usuario}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($persona->created))}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($persona->updated))}}</td>
						<td class="py-1 px-2 text-center" id="contenedor_badge{{$idrand}}">
							@if ($persona->estatus == "A")
								<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@else
								<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						<td class="py-1 px-2 text-center">
							<div class="form-check form-switch form-check-inline m-0">
								<input type="checkbox" class="form-check-input mx-auto switch_estatus" role="switch" id="switch_estatus{{$idrand}}" data-id="{{$idrand}}" value="{{$persona->cedula}}" <?= $persona->estatus == "A" ? "checked" : "" ?>>
							</div>
						</td>
						<td class="py-1 px-2" style="width: 20px;">
							<a href="{{route('personal.edit', ['personal' => $persona->cedula])}}" class="btn btn-primary btn-sm p-2"><i class="fas fa-edit"></i></a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection