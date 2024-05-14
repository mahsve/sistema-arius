@extends('plantilla')

@section('title', 'Bitácora - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
<script src="{{url('js/app/bitacora/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-12 col-md-7 col-lg-6 text-start">
			<h4 class="card-title text-uppercase mb-3 my-md-2"><i class="fas fa-history"></i> Bitácora</h4>
		</div>
		<div class="col-12 col-md-5 col-lg-6 text-end">
			<div class="form-row justify-content-end">
				<div class="col-12 col-md-6 col-lg-4">
					<button type="button" class="btn btn-primary btn-sm w-100 ms-1" data-bs-toggle="collapse" data-bs-target="#collapse-filtro" aria-expanded="false" aria-controls="collapse-filtro"><i class="fas fa-filter"></i></button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Filtro -->
<div class="collapse" id="collapse-filtro">
	<div class="form-row justify-content-end align-items-end">
		<div class="col-5 col-md-3 col-xl-2">
			<div class="form-group mb-3">
				<label for="c_cliente_m"><i class="fas fa-calendar-day"></i> Fecha inicio</label>
				<input type="date" class="form-control text-uppercase" name="fecha_inicio" id="fecha_inicio" value="{{$fecha_inicio}}">
			</div>
		</div>
		<div class="col-5 col-md-3 col-xl-2">
			<div class="form-group mb-3">
				<label for="c_cliente_m"><i class="fas fa-calendar-day"></i> Fecha tope</label>
				<input type="date" class="form-control text-uppercase" name="fecha_final" id="fecha_final" value="{{$fecha_final}}">
			</div>
		</div>
		<div class="col-2 col-md-2 col-xl-1">
			<div class="form-group mb-3">
				<button type="button" class="btn btn-primary btn-sm w-100" id="btn_buscar_por_fecha"><i class="fas fa-search"></i></button>
			</div>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table id="data-table" class="table table-hover border-bottom m-0">
				<thead>
					<tr>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Fecha</th>
						<th class="ps-2"><i class="fas fa-laptop"></i> IP</th>
						<th class="ps-2"><i class="fab fa-chrome"></i> Navegador</th>
						<th class="ps-2"><i class="fas fa-user"></i> Usuario</th>
						<th class="ps-2"><i class="fas fa-address-card"></i> Personal</th>
						<th class="ps-2"><i class="fas fa-exchange-alt"></i> Acción</th>
						<th class="ps-2"><i class="fas fa-history"></i> Historial</th>
						<th class="ps-2 text-center"><i class="fas fa-cogs"></i></th>
					</tr>
				</thead>

				<tbody>
					@foreach($historial as $index => $registro)
					<tr>
						<td class="py-3 px-2">{{date('d/m/y h:i:s A', strtotime($registro->fecha))}}</td>
						<td class="py-3 px-2">{{$registro->ip}}</td>
						<td class="py-3 px-2"><span class="d-block text-truncate" style="max-width: 150px;">{{$registro->navegador}}</span></td>
						<td class="py-3 px-2">{{$registro->usuario}}</td>
						<td class="py-3 px-2">{{$registro->nombre}}</td>
						<td class="py-3 px-2">{{$registro->operacion}}</td>
						<td class="py-3 px-2"><span class="d-block text-truncate" style="max-width: 150px;">{{$registro->descripcion}}</span></td>
						<td class="py-1 px-2" style="width: 20px;">
							<button type="button" class="btn btn-primary btn-sm btn-icon btn_detalles" data-id="{{$registro->idbitacora}}"><i class="fas fa-external-link-square-alt"></i></button>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_detalles" tabindex="-1" aria-labelledby="modal_detalles_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_detalles_label"><i class="fas fa-history"></i> Detalle registro</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3" style="overflow-y: auto; max-height: 500px;">
				<div id="mostrar-detalles-bitacora">
					<!-- javascript -->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection