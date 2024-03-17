@extends('plantilla')

@section('title', 'Mapas de zonas - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script id="contenedor_script_variables">
	const url_ = '{{url('/')}}';
	const token_ = '{{csrf_token()}}';

	// Una vez cargado en las constantes, se elimina la etiqueta script por temas de seguridad.
	document.getElementById('contenedor_script_variables').remove();
</script>
<script src="{{url('js/app/mapa_de_zona/index.js')}}"></script>
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
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase m-0"><i class="fas fa-map-marked-alt"></i> Mapas de zonas</h4>
		</div>
		<div class="col-6 text-end">
			<button type="button" class="btn btn-primary btn-sm" id="btn_nuevo_mapa"><i class="fas fa-folder-plus me-2"></i>Agregar</button>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table id="data-table" class="table table-hover m-0">
				<thead>
					<tr>
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
					@foreach ($mapas_de_zonas as $index => $mapa_de_zona)
					<tr>
						<td class="px-2 text-center">{{$mapa_de_zona->id_codigo}}</td>
						<td class="px-2">{{$mapa_de_zona->nombre_completo}}</td>
						<td class="px-2">{{$mapa_de_zona->tipo_cliente == "N" ? "Natural" : "Jurídico"}}</td>
						<td class="px-2">{{date('h:i:s A d/m/y', strtotime($mapa_de_zona->created))}}</td>
						<td class="px-2">{{$mapa_de_zona->nombres . " " . $mapa_de_zona->apellidos}}</td>
						<td class="p-2">
							@if ($mapa_de_zona->estatus == "A")
								<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@elseif ($mapa_de_zona->estatus == "R")
								<span class="badge badge-success"><i class="fas fa-check"></i> Registrado</span>
							@elseif ($mapa_de_zona->estatus == "I")
								<span class="badge badge-success"><i class="fas fa-check"></i> Instalado</span>
							@else
								<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						<td class="p-2" style="width: 20px;">
							<a href="{{route('mapas_de_zonas.modificar', ['id' => $mapa_de_zona->idcodigo])}}" class="btn btn-primary btn-sm  p-2"><i data-feather="edit"></i></a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_clientes" tabindex="-1" aria-labelledby="modal_clientes_label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title fs-5" id="modal_clientes_label">Seleccione un cliente</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<div class="table-responsive">
					<table id="tabla_clientes" class="table table-hover m-0">
						<thead>
							<tr>
								<th class="px-2" width="70px">Identificación</th>
								<th class="px-2">Cliente</th>
								<th class="px-2 text-center"><i class="fas fa-cogs"></i></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php // {{route('mapas_de_zonas.create')}} ?>
@endsection