@extends('plantilla')

@section('title', 'Monitoreo - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
<script src="{{url('js/app/monitoreo/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-12 col-md-7 col-lg-6 text-start">
			<h4 class="card-title text-uppercase mb-3 my-md-2"><i class="fas fa-desktop"></i> Monitoreo</h4>
		</div>
		<div class="col-12 col-md-5 col-lg-6 text-end">
			@if (isset($permisos->create))
			<div class="form-row justify-content-end">
				<div class="col-12 col-md-8 col-lg-6">
					<button type="button" class="btn btn-primary btn-sm w-100" id="btn_nueva_ficha"><i class="fas fa-folder-plus me-2"></i>Agregar</button>
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
						<th class="ps-2" width="40px"><i class="fas fa-barcode"></i> N° de reporte</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Fecha</th>
						<th class="ps-2"><i class="fas fa-address-card"></i> Turno</th>
						<th class="ps-2"><i class="fas fa-address-card"></i> Operador</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Registrado</th>
						<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
						@if (isset($permisos->update) or isset($permisos->toggle) or isset($permisos->generar_pdf))
						<th class="ps-2 text-center"><i class="fas fa-cogs"></i></th>
						@endif
					</tr>
				</thead>

				<tbody>
					@foreach ($reportes as $index => $reporte)
					@php
					$idrand = rand(100000,999999);
					@endphp
					<tr>
						<td class="py-1 px-2 text-center">#{{str_pad($reporte->idreporte, 8, "0", STR_PAD_LEFT)}}</td>
						<td class="py-1 px-2">{{$reporte->fecha}}</td>
						<td class="py-1 px-2">{{$reporte->turno}}</td>
						<td class="py-1 px-2">{{$reporte->operador}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($reporte->created))}}</td>
						<td class="py-1 px-2 text-center" id="contenedor_badge{{$idrand}}">
							@if ($reporte->estatus == "A")
							<span class="badge badge-success"><i class="fas fa-folder-open"></i> Abierto</span>
							@else
							<span class="badge badge-info"><i class="fas fa-folder"></i> Cerrado</span>
							@endif
						</td>
						@if (isset($permisos->update) or isset($permisos->generar_pdf) or isset($permisos->toggle))
						<td class="py-1 px-2" width="63px">
							@if (isset($permisos->update) and $reporte->estatus == "A")
							<a href="{{route('monitoreo.edit', ['id' => $reporte->idreporte])}}" class="btn btn-primary btn-sm btn-icon" id="btn_edit_{{$idrand}}"><i class="fas fa-edit"></i></a>
							@endif
							@if (isset($permisos->generar_pdf))
							<a href="{{route('monitoreo.pdf', ['id' => $reporte->idreporte])}}" class="btn btn-primary btn-sm btn-icon" id="btn_print_{{$idrand}}" target="blank"><i class="fas fa-print"></i></a>
							@endif
							@if (isset($permisos->toggle) and $reporte->estatus == "A")
							<button class="btn btn-warning btn-sm btn-icon btn_cerrar" data-id="{{$reporte->idreporte}}" data-rand="{{$idrand}}"><i class="fas fa-ban"></i></button>
							@endif
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
<div class="modal fade" id="modal_registrar" tabindex="-1" aria-labelledby="modal_registrar_label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_label"><i class="fas fa-folder-plus"></i> Registrar reporte diario</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('monitoreo.store')}}">
					@csrf
					<div class="form-row">
						<div class="col-12 col-md-4">
							<div class="form-group mb-3">
								<label for="c_fecha_r" class="required"><i class="fas fa-calendar-day"></i> Fecha</label>
								<input type="date" class="form-control text-uppercase" name="c_fecha" id="c_fecha_r" value="{{date('Y-m-d')}}">
							</div>
						</div>
						<div class="col-12 col-md-4">
							<div class="form-group mb-3">
								<label for="c_operador_r" class="required"><i class="fas fa-users-cog"></i> Operador</label>
								<select class="form-control text-uppercase" name="c_operador" id="c_operador_r">
									<option value="">Seleccione el operador</option>
									@foreach($personal as $operador)
									<option value="{{$operador->cedula}}" <?= auth()->user()->cedula == $operador->cedula ? "selected" : "" ?>>{{$operador->nombre}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-12 col-md-4">
							<div class="form-group mb-3">
								<label for="c_turno_r" class="required"><i class="fas fa-business-time"></i> Turno</label>
								<select class="form-control text-uppercase" name="c_turno" id="c_turno_r">
									<option value="">Seleccione el turno</option>
									<option value="7:00 AM - 7:00 PM">7:00 AM - 7:00 PM</option>
									<option value="7:00 PM - 7:00 AM">7:00 AM - 7:00 PM</option>
								</select>
							</div>
						</div>
						<div class="col-12 col-md-4">
							<div class="form-group mb-3">
								<label for="c_mensajeria_r"><i class="fas fa-envelope"></i> Servicio mensajería</label>
								<input type="text" class="form-control text-uppercase" name="c_mensajeria" id="c_mensajeria_r">
							</div>
						</div>
						<div class="col-12 col-md-4">
							<div class="form-group mb-3">
								<label for="c_radio_r"><i class="fas fa-broadcast-tower"></i> Radio</label>
								<input type="text" class="form-control text-uppercase" name="c_radio" id="c_radio_r">
							</div>
						</div>
						<div class="col-12 col-md-4">
							<div class="form-group mb-3">
								<label for="c_lineas_r" class="text-truncate"><i class="fas fa-sim-card"></i> Lineas de reporte: L1, L2, L3, L4</label>
								<input type="text" class="form-control text-uppercase" name="c_lineas" id="c_lineas_r">
							</div>
						</div>
						<div class="col-12">
							<div class="form-group mb-3">
								<label for="c_observacion_r"><i class="fas fa-sticky-note"></i> Observación</label>
								<textarea class="form-control" name="c_observacion" id="c_observacion_r"></textarea>
							</div>
						</div>
					</div>
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
@endsection