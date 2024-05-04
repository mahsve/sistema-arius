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
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-server"></i> Servicios</h4>
		</div>
		<div class="col-6 text-end">
			@if (isset($permisos->create))
			<button type="button" class="btn btn-primary btn-sm" id="btn_nuevo_servicio"><i class="fas fa-folder-plus me-2"></i>Agregar</button>
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
						<th class="ps-2"><i class="fas fa-sitemap"></i> Módulo</th>
						<th class="ps-2"><i class="fas fa-server"></i> Servicio</th>
						<th class="ps-2"><i class="fas fa-laptop-code"></i> Tipo</th>
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
					@foreach($servicios as $index => $servicio)
					@php
					$idrand = rand(100000,999999);
					@endphp
					<tr>
						<td class="py-1 px-2"><b>{{$servicio->modulo}}</b></td>
						<td class="py-1 px-2">
							@if($servicio->idservicio_raiz == null)
							<b>{{$servicio->servicio}}</b>
							@else
							<b>{{$servicio->submodulo}}</b> - {{$servicio->servicio}}
							@endif
						</td>
						<td class="py-1 px-2">
							@if($servicio->idservicio_raiz == null)
							<span class="badge badge-primary"><i class="fas fa-sitemap"></i> Submódulo</span>
							@else
							<span class="badge badge-info"><i class="fas fa-laptop"></i> Operación</span>
							@endif
						</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($servicio->created))}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($servicio->updated))}}</td>
						<td class="py-1 px-2 text-center" id="contenedor_badge{{$idrand}}">
							@if ($servicio->estatus == "A")
							<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@else
							<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						@if (isset($permisos->toggle))
						<td class="py-1 px-2 text-center">
							<div class="form-check form-switch form-check-inline m-0">
								<input type="checkbox" class="form-check-input mx-auto switch_estatus" role="switch" id="switch_estatus{{$idrand}}" data-id="{{$idrand}}" value="{{$servicio->idservicio}}" <?= $servicio->estatus == "A" ? "checked" : "" ?>>
							</div>
						</td>
						@endif
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
					<div class="form-group mb-3">
						<label class="required"><i class="fas fa-laptop-code"></i> Tipo de servicio: </label>
						<div class="d-flex">
							<div class="form-check form-check-inline d-flex align-items-center m-0 me-3">
								<input class="form-check-input m-0 me-2" type="radio" name="c_tipo_servicio" id="c_submodulo_r" value="submodulo" checked>
								<label class="form-check-label m-0" for="c_submodulo_r">Submódulo</label>
							</div>
							<div class="form-check form-check-inline d-flex align-items-center m-0 me-3">
								<input class="form-check-input m-0 me-2" type="radio" name="c_tipo_servicio" id="c_operacion_r" value="operacion">
								<label class="form-check-label m-0" for="c_operacion_r">Operación</label>
							</div>
						</div>
					</div>
					<div class="form-group mb-3">
						<div class="row align-items-center">
							<div class="col">
								<label for="c_modulo_r" class="required"><i class="fas fa-sitemap"></i> Módulo</label>
							</div>
							@if (isset($crear_modulo))
							<div class="col text-end">
								<button type="button" class="btn btn-sm btn-primary btn-auxilar ms-auto btn_nuevo_mod" data-form="create"><i class="fas fa-plus"></i></button>
							</div>
							@endif
						</div>
						<select class="form-control text-uppercase" name="c_modulo" id="c_modulo_r">
							<option value="">Seleccione el módulo</option>
							@foreach ($modulos as $modulo)
							<option value="{{$modulo->idmodulo}}">{{$modulo->modulo}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group mb-3" id="contenedor_submodulos_r">
						<label for="c_submodulos_r" class="required"><i class="fas fa-sitemap"></i> Submódulos</label>
						<select class="form-control text-uppercase" name="c_submodulo" id="c_submodulos_r">
							<option value="">Seleccione el submódulo</option>
						</select>
					</div>
					<div class="form-group mb-3">
						<label for="c_servicio_r" class="required"><i class="fas fa-server"></i> Servicio</label>
						<input type="text" class="form-control text-uppercase" name="c_servicio" id="c_servicio_r" placeholder="Ingrese el nombre del servicio">
					</div>
					<div class="form-group mb-3" id="contenedor_enlace_r">
						<label for="c_enlace_r" class="required"><i class="fas fa-link"></i> Enlace del submódulo</label>
						<div class="input-group">
							<span class="input-group-text">{{url('')}}/</span>
							<input type="text" class="form-control text-lowercase" name="c_enlace" id="c_enlace_r" placeholder="Ingrese el enlace">
						</div>
					</div>
					<div class="form-group mb-3" id="contenedor_metodo_r">
						<label for="c_metodo_r" class="required"><i class="fas fa-database"></i> Método del controlador</label>
						<input type="text" class="form-control text-lowercase" name="c_metodo" id="c_metodo_r" placeholder="Ej: create, update, toggle">
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
				<h1 class="modal-title text-uppercase fs-5" id="modal_modificar_label"><i class="fas fa-folder-open"></i> Modificar servicio</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_actualizacion" id="formulario_actualizacion" method="POST" action="">
					@csrf
					@method('PATCH')
					<div class="form-group mb-3">
						<label class="required"><i class="fas fa-laptop-code"></i> Tipo de servicio: </label>
						<div class="d-flex">
							<div class="form-check form-check-inline d-flex align-items-center m-0 me-3">
								<input class="form-check-input m-0 me-2" type="radio" name="c_tipo_servicio" id="c_submodulo_m" value="submodulo">
								<label class="form-check-label m-0" for="c_submodulo_m">Submódulo</label>
							</div>
							<div class="form-check form-check-inline d-flex align-items-center m-0 me-3">
								<input class="form-check-input m-0 me-2" type="radio" name="c_tipo_servicio" id="c_operacion_m" value="operacion">
								<label class="form-check-label m-0" for="c_operacion_m">Operación</label>
							</div>
						</div>
					</div>
					<div class="form-group mb-3">
						<div class="row align-items-center">
							<div class="col">
								<label for="c_modulo_m" class="required"><i class="fas fa-sitemap"></i> Módulo</label>
							</div>
							@if (isset($crear_modulo))
							<div class="col text-end">
								<button type="button" class="btn btn-sm btn-primary btn-auxilar ms-auto btn_nuevo_mod" data-form="update"><i class="fas fa-plus"></i></button>
							</div>
							@endif
						</div>
						<select class="form-control text-uppercase" name="c_modulo" id="c_modulo_m">
							<option value="">Seleccione el módulo</option>
							@foreach ($modulos as $modulo)
							<option value="{{$modulo->idmodulo}}">{{$modulo->modulo}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group mb-3" id="contenedor_submodulos_m">
						<label for="c_submodulos_m" class="required"><i class="fas fa-sitemap"></i> Submódulos</label>
						<select class="form-control text-uppercase" name="c_submodulo" id="c_submodulos_m">
							<option value="">Seleccione el submódulo</option>
						</select>
					</div>
					<div class="form-group mb-3">
						<label for="c_servicio_m" class="required"><i class="fas fa-server"></i> Servicio</label>
						<input type="text" class="form-control text-uppercase" name="c_servicio" id="c_servicio_m" placeholder="Ingrese el nombre del servicio">
					</div>
					<div class="form-group mb-3" id="contenedor_enlace_m">
						<label for="c_enlace_m" class="required"><i class="fas fa-link"></i> Enlace del submódulo</label>
						<div class="input-group">
							<span class="input-group-text">{{url('')}}/</span>
							<input type="text" class="form-control text-lowercase" name="c_enlace" id="c_enlace_m" placeholder="Ingrese el enlace">
						</div>
					</div>
					<div class="form-group mb-3" id="contenedor_metodo_m">
						<label for="c_metodo_m" class="required"><i class="fas fa-database"></i> Método del controlador</label>
						<input type="text" class="form-control text-lowercase" name="c_metodo" id="c_metodo_m" placeholder="Ej: create, update, toggle">
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

@if (isset($crear_modulo))
<div class="modal fade" id="modal_registrar_mod" tabindex="-1" aria-labelledby="modal_registrar_mod_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_mod_label"><i class="fas fa-paste"></i> Registro rápido</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro_mod" id="formulario_registro_mod" method="POST" action="{{route('modulos.store')}}">
					@csrf
					<div class="form-group mb-3">
						<label for="c_modulo_aux" class="required"><i class="fas fa-sitemap"></i> Módulo</label>
						<input type="text" class="form-control text-uppercase" name="c_modulo" id="c_modulo_aux" placeholder="Ingrese el nombre del módulo">
					</div>
					<div class="form-group mb-3">
						<label for="c_icono_aux" class="d-flex justify-content-between">
							<span><i class="fas fa-icons"></i> Icono</span>
							<a href="https://fontawesome.com/v5/search?o=r&m=free" class="fw-bold text-dark" style="text-decoration: none;" target="blank">
								<i class="fas fa-icons"></i> Ver lista <i class="fas fa-external-link-alt"></i>
							</a>
						</label>
						<input type="text" class="form-control text-lowercase" name="c_icono" id="c_icono_aux" placeholder="Ejemplo: fas fa-icons">
					</div>
					<div class="form-group mb-3">
						<label><i class="fas fa-desktop"></i> Vista previa</label>
						<div class="text-center border rounded p-3" style="height: 82px;">
							<i id="preview_aux" style="font-size: 3rem;"></i>
						</div>
					</div>
					<input type="hidden" name="modulo" id="btn_click_mod" value="servicios">
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_registrar_mod"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif
@endsection