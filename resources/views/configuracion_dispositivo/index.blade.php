@extends('plantilla')

@section('title', 'Configuraciones - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
<script src="{{url('js/app/configuracion_dispositivo/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-laptop-code"></i> Configuraciones</h4>
		</div>
		<div class="col-6 text-end">
			@if (isset($permisos->create))
			<button type="button" class="btn btn-primary btn-sm" id="btn_nuevo_configuracion"><i class="fas fa-folder-plus me-2"></i>Agregar</button>
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
						<th class="ps-2"><i class="fas fa-laptop-code"></i> Configuración</th>
						<th class="ps-2"><i class="fas fa-video"></i> Dispositivo</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Creado</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Actualizado</th>
						<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
						@if (isset($permisos->toggle))
						<th class="ps-2 text-center"><i class="fas fa-toggle-on"></i></th>
						@endif
						@if (isset($permisos->update))
						<th class="ps-2 text-center"><i class="fas fa-cogs"></i></th>
						@endif
					</tr>
				</thead>

				<tbody>
					@foreach($configuraciones as $index => $configuracion)
					@php
					$idrand = rand(100000,999999);
					@endphp
					<tr>
						<td class="py-1 px-2">{{$configuracion->configuracion}}</td>
						<td class="py-1 px-2">{{$configuracion->dispositivo}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($configuracion->created))}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($configuracion->updated))}}</td>
						<td class="py-1 px-2 text-center" id="contenedor_badge{{$idrand}}">
							@if ($configuracion->estatus == "A")
							<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@else
							<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						@if (isset($permisos->toggle))
						<td class="py-1 px-2 text-center">
							<div class="form-check form-switch form-check-inline m-0">
								<input type="checkbox" class="form-check-input mx-auto switch_estatus" role="switch" id="switch_estatus{{$idrand}}" data-id="{{$idrand}}" value="{{$configuracion->idconfiguracion}}" <?= $configuracion->estatus == "A" ? "checked" : "" ?>>
							</div>
						</td>
						@endif
						@if (isset($permisos->update))
						<td class="py-1 px-2" style="width: 20px;">
							<button type="button" class="btn btn-primary btn-sm btn-icon btn_editar" data-id="{{$configuracion->idconfiguracion}}"><i class="fas fa-edit"></i></button>
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
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_label"><i class="fas fa-folder-plus"></i> Registrar configuración</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('dispositivo_cog.store')}}">
					@csrf
					<div class="form-group">
						<label for="c_dispositivo_r" class="required"><i class="fas fa-video"></i> Dispositivo</label>
						<select class="form-control text-uppercase" name="c_dispositivo" id="c_dispositivo_r" required>
							<option value="">Seleccione el dispositivo</option>
							@foreach ($dispositivos as $dispositivo)
							<option value="{{$dispositivo->iddispositivo}}">{{$dispositivo->dispositivo}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group mb-3">
						<label for="c_configuracion_r" class="required"><i class="fas fa-laptop-code"></i> Configuración</label>
						<input type="text" class="form-control text-uppercase" name="c_configuracion" id="c_configuracion_r" placeholder="Ingrese el nombre del configuración" minlength="3" required>
					</div>
					<div class="form-group mb-3">
						<label for="c_descripcion_r"><i class="fas fa-sticky-note"></i> Descripción</label>
						<textarea class="form-control text-uppercase" name="c_descripcion" id="c_descripcion_r" placeholder="Ingrese la descripción del configuración (Opcional)" rows="3"></textarea>
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

@if (isset($permisos->update))
<div class="modal fade" id="modal_modificar" tabindex="-1" aria-labelledby="modal_modificar_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_modificar_label"><i class="fas fa-folder-open"></i> Modificar configuracion</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_actualizacion" id="formulario_actualizacion" method="POST" action="">
					@csrf
					@method('PATCH')
					<div class="form-group">
						<label for="c_dispositivo"><i class="fas fa-video"></i> Dispositivo</label>
						<select class="form-control text-uppercase" name="c_dispositivo" id="c_dispositivo_m" required>
							<option value="">Seleccione el dispositivo</option>
							@foreach ($dispositivos as $dispositivo)
							<option value="{{$dispositivo->iddispositivo}}">{{$dispositivo->dispositivo}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="c_configuracion_m"><i class="fas fa-laptop-code"></i> Configuración</label>
						<input type="text" class="form-control text-uppercase" name="c_configuracion" id="c_configuracion_m" placeholder="Ingrese el nombre del configuración" minlength="3" required>
					</div>
					<div class="form-group mb-3">
						<label for="c_descripcion_m"><i class="fas fa-sticky-note"></i> Descripción</label>
						<textarea class="form-control text-uppercase" name="c_descripcion" id="c_descripcion_m" placeholder="Ingrese la descripción del configuración (Opcional)" rows="3"></textarea>
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