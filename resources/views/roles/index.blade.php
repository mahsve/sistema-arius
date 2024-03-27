@extends('plantilla')

@section('title', 'Roles - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase m-0">Roles</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('personal.create')}}" class="btn btn-primary btn-sm "><i data-feather="plus"></i> Agregar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover" id="data-table">
				<thead>
					<tr>
						<th class="text-center">N°</th>
						<th>Cédula</th>
						<th>Personal</th>
						<th>Usuario</th>
						<th>Creado</th>
						<th>Actualizado</th>
						<th>Estatus</th>
						<th class="text-center"><i data-feather="settings" width="14px" height="14px"></i></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection