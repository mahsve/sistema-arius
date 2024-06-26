@extends('plantilla')

@section('title', 'Roles - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
<script src="{{url('js/app/rol/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-id-card-alt"></i> Roles</h4>
		</div>
		<div class="col-6 text-end">
			@if (isset($permisos->create))
			<button type="button" class="btn btn-primary btn-sm" id="btn_nuevo_rol"><i class="fas fa-folder-plus me-2"></i>Agregar</button>
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
						<th class="ps-2"><i class="fas fa-id-card-alt"></i> Rol</th>
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
					@foreach($roles as $index => $rol)
					@php
					$idrand = rand(100000,999999);
					@endphp
					<tr>
						<td class="py-1 px-2"><b>{{$rol->rol}}</b></td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($rol->created))}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($rol->updated))}}</td>
						<td class="py-1 px-2 text-center" id="contenedor_badge{{$idrand}}">
							@if ($rol->estatus == "A")
							<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@else
							<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						@if (isset($permisos->toggle))
						<td class="py-1 px-2 text-center">
							@if (auth()->user()->idrol != $rol->idrol)
							<div class="form-check form-switch form-check-inline m-0">
								<input type="checkbox" class="form-check-input mx-auto switch_estatus" role="switch" id="switch_estatus{{$idrand}}" data-id="{{$idrand}}" value="{{$rol->idrol}}" <?= $rol->estatus == "A" ? "checked" : "" ?>>
							</div>
							@endif
						</td>
						@endif
						@if (isset($permisos->update))
						<td class="py-1 px-2" style="width: 20px;">
							<button type="button" class="btn btn-primary btn-sm btn-icon btn_editar" data-id="{{$rol->idrol}}"><i class="fas fa-edit"></i></button>
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
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_label"><i class="fas fa-folder-plus"></i> Registrar rol</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3" style="max-height: 500px; overflow: auto;">
				<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('roles.store')}}">
					@csrf
					<div class="form-group mb-3">
						<label for="c_rol_r"><i class="fas fa-id-card-alt"></i> Rol</label>
						<input type="text" class="form-control text-uppercase" name="c_rol" id="c_rol_r" placeholder="Ingrese el nombre del rol" minlength="3" required>
					</div>
					<div class="form-row">
						@foreach($modulos as $modulo)
						<div class="col-12 col-lg-6">
							<div class="card mb-2">
								<div class="card-header d-flex align-items-center justify-content-between border-bottom-0 ps-3 py-2 pe-2 m-0">
									<div class="form-check form-check-inline m-0 d-flex align-items-center">
										<input class="form-check-input m-0 r_modulo" type="checkbox" role="switch" name="modulo[]" id="r_modulo_{{$modulo->idmodulo}}" value="{{$modulo->idmodulo}}">
										<label class="form-check-label ms-2" for="r_modulo_{{$modulo->idmodulo}}"><b>{{$modulo->modulo}}</b></label>
									</div>
									<a class="btn btn-secondary btn-sm" data-bs-toggle="collapse" href="#r_collapse_servicios_{{$modulo->idmodulo}}" role="button" aria-expanded="false" aria-controls="r_collapse_servicios_{{$modulo->idmodulo}}"><i class="fas fa-eye"></i></a>
								</div>

								<div class="collapse" id="r_collapse_servicios_{{$modulo->idmodulo}}">
									<div class="card-body p-3">
										<div class="form-check form-check-inline m-0 d-flex align-items-center border-bottom">
											<input class="form-check-input m-0
												r_marcar_todos
												r_modulo_servicios_{{$modulo->idmodulo}}
												r_modulo_submodulo_{{$modulo->idmodulo}}
												r_servicios" type="checkbox" role="switch" id="r_marcar_todos_{{$modulo->idmodulo}}" data-modulo="{{$modulo->idmodulo}}">
											<label class="form-check-label ms-2" for="r_marcar_todos_{{$modulo->idmodulo}}"><b class="me-2">Marcar todas</b><i class="fas fa-check-double"></i></label>
										</div>

										@foreach($modulo->servicios as $servicio)
										<?php
										if ($servicio->idservicio_raiz == null) {
											$tipo_servicio = "submodulo";
											$idservicio_raiz = $servicio->idservicio;
										} else {
											$tipo_servicio = "operacion";
											$idservicio_raiz = $servicio->idservicio_raiz;
										}
										?>
										<div class="form-check form-check-inline m-0 d-flex align-items-center {{$servicio->idservicio_raiz > 0 ? 'ms-4' : ''}}">
											<input class="form-check-input m-0
												r_modulo_servicios_{{$modulo->idmodulo}}
												r_modulo_{{$tipo_servicio}}_{{$modulo->idmodulo}}
												r_servicios
												r_{{$tipo_servicio}}
												r_{{$tipo_servicio}}_{{$idservicio_raiz}}" type="checkbox" role="switch" name="servicio[]" id="r_servicio_{{$servicio->idservicio}}" value="{{$servicio->idservicio}}" data-modulo="{{$modulo->idmodulo}}" data-submodulo="{{$idservicio_raiz}}">
											<label class="form-check-label ms-2" for="r_servicio_{{$servicio->idservicio}}"><b>{{$servicio->servicio}}</b></label>
										</div>
										@endforeach
									</div>
								</div>
							</div>
						</div>
						@endforeach
					</div>
					<div class="text-end mt-3">
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
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_modificar_label"><i class="fas fa-folder-open"></i> Modificar rol</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3" style="max-height: 500px; overflow: auto;">
				<form class="forms-sample" name="formulario_actualizacion" id="formulario_actualizacion" method="POST" action="">
					@csrf
					@method('PATCH')
					<div class="form-group">
						<label for="c_rol_m"><i class="fas fa-id-card-alt"></i> Rol</label>
						<input type="text" class="form-control text-uppercase" name="c_rol" id="c_rol_m" placeholder="Ingrese el nombre del rol" minlength="3" required>
					</div>
					<div class="form-row">
						@foreach($modulos as $modulo)
						<div class="col-12 col-lg-6">
							<div class="card mb-2">
								<div class="card-header d-flex align-items-center justify-content-between border-bottom-0 ps-3 py-2 pe-2 m-0">
									<div class="form-check form-check-inline m-0 d-flex align-items-center">
										<input class="form-check-input m-0 m_modulo" type="checkbox" role="switch" name="modulo[]" id="m_modulo_{{$modulo->idmodulo}}" value="{{$modulo->idmodulo}}">
										<label class="form-check-label ms-2" for="m_modulo_{{$modulo->idmodulo}}"><b>{{$modulo->modulo}}</b></label>
									</div>
									<a class="btn btn-secondary btn-sm" data-bs-toggle="collapse" href="#m_collapse_servicios_{{$modulo->idmodulo}}" role="button" aria-expanded="false" aria-controls="m_collapse_servicios_{{$modulo->idmodulo}}"><i class="fas fa-eye"></i></a>
								</div>

								<div class="collapse" id="m_collapse_servicios_{{$modulo->idmodulo}}">
									<div class="card-body p-3">
										<div class="form-check form-check-inline m-0 d-flex align-items-center border-bottom">
											<input class="form-check-input m-0
												m_marcar_todos
												m_modulo_servicios_{{$modulo->idmodulo}}
												m_modulo_submodulo_{{$modulo->idmodulo}}
												m_servicios" type="checkbox" role="switch" id="m_marcar_todos_{{$modulo->idmodulo}}" data-modulo="{{$modulo->idmodulo}}">
											<label class="form-check-label ms-2" for="m_marcar_todos_{{$modulo->idmodulo}}"><b class="me-2">Marcar todas</b><i class="fas fa-check-double"></i></label>
										</div>

										@foreach($modulo->servicios as $servicio)
										<?php
										if ($servicio->idservicio_raiz == null) {
											$tipo_servicio = "submodulo";
											$idservicio_raiz = $servicio->idservicio;
										} else {
											$tipo_servicio = "operacion";
											$idservicio_raiz = $servicio->idservicio_raiz;
										}
										?>
										<div class="form-check form-check-inline m-0 d-flex align-items-center {{$servicio->idservicio_raiz > 0 ? 'ms-4' : ''}}">
											<input class="form-check-input m-0
												m_modulo_servicios_{{$modulo->idmodulo}}
												m_modulo_{{$tipo_servicio}}_{{$modulo->idmodulo}}
												m_servicios
												m_{{$tipo_servicio}}
												m_{{$tipo_servicio}}_{{$idservicio_raiz}}" type="checkbox" role="switch" name="servicio[]" id="m_servicio_{{$servicio->idservicio}}" value="{{$servicio->idservicio}}" data-modulo="{{$modulo->idmodulo}}" data-submodulo="{{$idservicio_raiz}}">
											<label class="form-check-label ms-2" for="m_servicio_{{$servicio->idservicio}}"><b>{{$servicio->servicio}}</b></label>
										</div>
										@endforeach
									</div>
								</div>
							</div>
						</div>
						@endforeach
					</div>
					<div class="text-end mt-3">
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