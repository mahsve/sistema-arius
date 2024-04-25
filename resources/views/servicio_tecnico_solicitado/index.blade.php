@extends('plantilla')

@section('title', 'Servicios técnicos solicitados - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
<script src="{{url('js/app/servicio_tecnico_solicitado/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-12 col-md-7 col-lg-6 text-start">
			<h4 class="card-title text-uppercase mb-3 my-md-2"><i class="fas fa-tools"></i> Servicios técnicos solicitados</h4>
		</div>
		<div class="col-12 col-md-5 col-lg-6 text-end">
			@if (isset($permisos->create))
			<div class="form-row justify-content-end">
				<div class="col-12 col-md-8 col-lg-6">
					<button type="button" class="btn btn-primary btn-sm w-100" id="btn_abrir_buscar_cliente"><i class="fas fa-folder-plus me-2"></i>Agregar solicitud</button>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table id="data-table" class="table table-hover border-bottom m-0">
				<thead>
					<tr>
						<th class="ps-2" width="40px"><i class="fas fa-barcode"></i> Código</th>
						<th class="ps-2"><i class="fas fa-id-badge"></i> Identificación</th>
						<th class="ps-2"><i class="fas fa-address-card"></i> Cliente</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Fecha</th>
						<th class="ps-2"><i class="fas fa-sticky-note"></i> Motivo</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Atendido</th>
						<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
						@if (isset($permisos->update) or isset($permisos->toggle))
						<th class="ps-2 text-center"><i class="fas fa-cogs"></i></th>
						@endif
					</tr>
				</thead>

				<tbody>
					@foreach ($servicios as $index => $servicio)
					@php
					$idrand = rand(100000,999999);
					@endphp
					<tr>
						<td class="py-1 px-2 text-center">{{$servicio->idcodigo}}</td>
						<td class="py-1 px-2">{{$servicio->identificacion}}</td>
						<td class="py-1 px-2">{{$servicio->nombre}}</td>
						<td class="py-1 px-2">{{date('d/m/Y', strtotime($servicio->fecha))}}</td>
						<td class="py-1 px-2"><span class="text-truncate" style="max-width: 250px;">{{$servicio->motivo}}</span></td>
						<td class="py-1 px-2">{{date('d/m/Y', strtotime($servicio->fecha))}}</td>
						<td class="py-1 px-2 text-center" id="contenedor_badge{{$idrand}}">
							@if ($servicio->estatus == "A")
							<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@else
							<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						@if (isset($permisos->update))
						<td class="py-1 px-2" style="width: 20px;">
							<button type="button" class="btn btn-primary btn-sm btn-icon btn_editar" data-id="{{$servicio->idservicio}}"><i class="fas fa-edit"></i></button>
						</td>
						@endif
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

@if (isset($permisos->create))
<div class="modal fade" id="modal_buscar_cliente" tabindex="-1" aria-labelledby="modal_bcliente_label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title fs-5" id="modal_bcliente_label"><i class="fas fa-search"></i> Buscar cliente</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body pt-3 pb-4">
				<div class="input-group input-group-sm mb-3">
					<input type="text" class="form-control h-100" id="input_buscar_cliente" placeholder="Buscar cliente">
					<button type="button" class="btn btn-primary btn-sm" id="btn_buscar_cliente"><i class="fas fa-search me-2"></i> Buscando</button>
				</div>

				<div class="table-responsive border rounded">
					<table id="tabla_clientes" class="table table-hover m-0">
						<thead>
							<tr>
								<th class="ps-2" width="60px"><i class="fas fa-barcode"></i> Código</th>
								<th class="ps-2"><i class="fas fa-id-badge"></i> Identificación</th>
								<th class="ps-2"><i class="fas fa-address-card"></i> Cliente</th>
								<th class="ps-2"><i class="fas fa-phone-alt"></i> Teléfono</th>
								<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
								<th class="px-2 text-center"><i class="fas fa-cogs"></i></th>
							</tr>
						</thead>

						<tbody>
							<!-- Javascript -->
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_registrar" tabindex="-1" aria-labelledby="modal_registrar_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_label"><i class="fas fa-folder-plus"></i> Registrar solicitud técnica</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('servicios_tecnico_solicitados.store')}}">
					@csrf
					<div class="form-group mb-3">
						<label for="c_cliente_r" class="required"><i class="fas fa-user"></i> Cliente</label>
						<input type="text" class="form-control text-uppercase" name="c_cliente" id="c_cliente_r" readonly>
					</div>
					<div class="form-group mb-3">
						<label for="c_fecha_r" class="required"><i class="fas fa-calendar-day"></i> Fecha</label>
						<input type="date" class="form-control text-uppercase" name="c_fecha" id="c_fecha_r">
					</div>
					<div class="form-group">
						<label for="c_motivo_r" class="required"><i class="fas fa-sticky-note"></i> Motivo de la solicitud</label>
						<textarea class="form-control text-uppercase" name="c_motivo" id="c_motivo_r" rows="3" placeholder="Descripción de la solicitud"></textarea>
					</div>
					<input type="hidden" class="form-control text-uppercase" name="c_codigo" id="c_codigo_r">
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_registrar"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif

@if (isset($permisos->update))
<div class="modal fade" id="modal_modificar" tabindex="-1" aria-labelledby="modal_modificar_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_modificar_label"><i class="fas fa-folder-open"></i> Modificar departamento</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_actualizacion" id="formulario_actualizacion" method="POST" action="">
					@csrf
					@method('PATCH')
					<div class="form-group">
						<label for="c_departamento_m" class="required"><i class="fas fa-hotel"></i> Departamento</label>
						<input type="text" class="form-control text-uppercase" name="c_departamento" id="c_departamento_m" placeholder="Ingrese el nombre del departamento" minlength="3" required>
					</div>
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_modificar"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif
@endsection