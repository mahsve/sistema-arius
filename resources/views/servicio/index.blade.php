@extends('plantilla')

@section('title', 'Servicios - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
<script src="{{url('js/app/servicio/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase m-0"><i class="fas fa-briefcase"></i> Servicios</h4>
		</div>
		<div class="col-6 text-end">
			<button type="button" class="btn btn-primary btn-sm" id="btn_nuevo_servicio"><i class="fas fa-folder-plus me-2"></i>Agregar</button>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table id="data-table" class="table table-hover border-bottom m-0">
				<thead>
					<tr>
						<th class="ps-2"><i class="fas fa-briefcase"></i> Servicio</th>
						<th class="ps-2"><i class="fas fa-briefcase"></i> Módulo</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Creado</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Actualizado</th>
						<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
						<th class="ps-2 text-center"><i class="fas fa-toggle-on"></i></th>
						<th class="ps-2 text-center"><i class="fas fa-cogs"></i></th>
					</tr>
				</thead>
				<tbody>
					@foreach($servicios as $index => $servicio)
					@php
					$idrand = rand(100000,999999);
					@endphp
					<tr>
						<td class="py-1 px-2">{{$servicio->servicio}}</td>
						<td class="py-1 px-2">{{$servicio->modulo}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($servicio->created))}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($servicio->updated))}}</td>
						<td class="py-1 px-2 text-center" id="contenedor_badge{{$idrand}}">
							@if ($servicio->estatus == "A")
								<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@else
								<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						<td class="py-1 px-2 text-center">
							<div class="form-check form-switch form-check-inline m-0">
								<input type="checkbox" class="form-check-input mx-auto switch_estatus" role="switch" id="switch_estatus{{$idrand}}" data-id="{{$idrand}}" value="{{$servicio->idservicio}}" <?= $servicio->estatus == "A" ? "checked" : "" ?>>
							</div>
						</td>
						<td class="py-1 px-2" style="width: 20px;">
							<button type="button" class="btn btn-primary btn-sm p-2 btn_editar" data-id="{{$servicio->idservicio}}"><i class="fas fa-edit"></i></button>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_registrar" tabindex="-1" aria-labelledby="modal_registrar_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_label"><i class="fas fa-folder-plus"></i> Registrar servicio</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
			<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('servicios.store')}}">
					@csrf
					<div class="form-group">
						<label for="c_modulo_r"><i class="fas fa-hotel"></i> Módulo</label>
						<select class="form-control text-uppercase" name="c_modulo" id="c_modulo_r" required>
							<option value="">Seleccione el módulo</option>
							@foreach ($modulos as $modulo)
							<option value="{{$modulo->idmodulo}}">{{$modulo->modulo}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group mb-3">
						<label for="c_servicio_r"><i class="fas fa-briefcase"></i> Servicio</label>
						<input type="text" class="form-control text-uppercase" name="c_servicio" id="c_servicio_r" placeholder="Ingrese el nombre del servicio" minlength="3" required>
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

<div class="modal fade" id="modal_modificar" tabindex="-1" aria-labelledby="modal_modificar_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_modificar_label"><i class="fas fa-folder-open"></i> Modificar servicio</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_actualizacion" id="formulario_actualizacion" method="POST" action="">
					@csrf
					@method('PATCH')
					<div class="form-group">
						<label for="c_modulo"><i class="fas fa-hotel"></i> Módulo</label>
						<select class="form-control text-uppercase" name="c_modulo" id="c_modulo_m" required>
							<option value="">Seleccione el módulo</option>
							@foreach ($modulos as $modulo)
							<option value="{{$modulo->idmodulo}}">{{$modulo->modulo}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="c_servicio_m"><i class="fas fa-briefcase"></i> Servicio</label>
						<input type="text" class="form-control text-uppercase" name="c_servicio" id="c_servicio_m" placeholder="Ingrese el nombre del servicio" minlength="3" required>
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
@endsection