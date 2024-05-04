@extends('plantilla')

@section('title', 'Usuarios - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
<script src="{{url('js/app/usuario/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-users"></i> Usuarios</h4>
		</div>
		<div class="col-6 text-end">
			@if (isset($permisos->create))
			<button type="button" class="btn btn-primary btn-sm" id="btn_nuevo_usuario"><i class="fas fa-folder-plus me-2"></i>Agregar</button>
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
						<th class="ps-2"><i class="fas fa-user"></i> Usuario</th>
						<th class="ps-2"><i class="fas fa-id-badge"></i> Ced.</th>
						<th class="ps-2"><i class="fas fa-address-card"></i> Nombre completo</th>
						<th class="ps-2"><i class="fas fa-id-card-alt"></i> Rol</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Creado</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Actualizado</th>
						<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
						@if (isset($permisos->update) or isset($permisos->toggle))
						<th class="ps-2 text-center"><i class="fas fa-cogs"></i></th>
						@endif
					</tr>
				</thead>

				<tbody>
					@foreach($usuarios as $index => $usuario)
					@php
					$idrand = rand(100000,999999);
					@endphp
					<tr>
						<td class="py-1 px-2"><b>{{$usuario->usuario}}</b></td>
						<td class="py-1 px-2">{{$usuario->cedula}}</td>
						<td class="py-1 px-2">{{$usuario->nombre}}</td>
						<td class="py-1 px-2">{{$usuario->rol}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($usuario->created))}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($usuario->updated))}}</td>
						<td class="py-1 px-2 text-center" id="contenedor_badge{{$idrand}}">
							@if ($usuario->estatus == "A")
							<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@elseif ($usuario->estatus == "P")
							<span class="badge badge-info"><i class="fas fa-clock"></i> Pendiente</span>
							@elseif ($usuario->estatus == "B")
							<span class="badge badge-danger"><i class="fas fa-ban"></i> Bloqueado</span>
							@else
							<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						@if (isset($permisos->update) or isset($permisos->toggle))
						<td class="py-1 px-2" style="width: 20px;">
							<?php if ($usuario->idusuario != auth()->user()->idusuario) { ?>
								@if (isset($permisos->update))
								<button type="button" class="btn btn-primary btn-sm btn-icon btn_editar" data-id="{{$usuario->idusuario}}"><i class="fas fa-edit"></i></button>
								@endif
								@if (isset($permisos->toggle))
								<button type="button" class="btn btn-warning btn-sm btn-icon btn_estatus" data-id="{{$usuario->idusuario}}" data-rand="{{$idrand}}"><i class="fas fa-toggle-on"></i></button>
								@endif
							<?php } ?>
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
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_label"><i class="fas fa-folder-plus"></i> Registrar usuario</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('usuarios.store')}}">
					@csrf
					<div class="form-group mb-3">
						<label for="c_cedula_r" class="required"><i class="fas fa-user-tie"></i> Personal</label>
						<select class="form-control text-uppercase" name="c_cedula" id="c_cedula_r">
							<option value="">Seleccione la persona</option>
							@foreach($personal as $persona)
							<option value="{{$persona->cedula}}">{{$persona->nombre}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group mb-3">
						<label for="c_rol_r" class="required"><i class="fas fa-id-card-alt"></i> Rol</label>
						<select class="form-control text-uppercase" name="c_rol" id="c_rol_r">
							<option value="">Seleccione el rol</option>
							@foreach($roles as $rol)
							<option value="{{$rol->idrol}}">{{$rol->rol}}</option>
							@endforeach
						</select>
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
				<h1 class="modal-title text-uppercase fs-5" id="modal_modificar_label"><i class="fas fa-folder-open"></i> Modificar usuario</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_actualizacion" id="formulario_actualizacion" method="POST" action="">
					@csrf
					@method('PATCH')
					<div class="form-group mb-3">
						<label for="c_personal_m"><i class="fas fa-user-tie"></i> Personal</label>
						<input type="text" class="form-control text-uppercase" id="c_personal_m" readonly>
					</div>
					<div class="form-group mb-3">
						<label for="c_rol_m" class="required"><i class="fas fa-id-card-alt"></i> Rol</label>
						<select class="form-control text-uppercase" name="c_rol" id="c_rol_m">
							<option value="">Seleccione el rol</option>
							@foreach($roles as $rol)
							<option value="{{$rol->idrol}}">{{$rol->rol}}</option>
							@endforeach
						</select>
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

@if (isset($permisos->toggle))
<div class="modal fade" id="modal_estatus" tabindex="-1" aria-labelledby="modal_estatus_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_estatus_label"><i class="fas fa-folder-open"></i> Cambiar estatus del usuario</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_estatus" id="formulario_estatus" method="POST" action="">
					@csrf
					@method('PUT')
					<div class="form-group mb-3">
						<label for="c_personal_s"><i class="fas fa-user-tie"></i> Personal</label>
						<input type="text" class="form-control text-uppercase" id="c_personal_s" readonly>
					</div>
					<div class="form-group mb-3">
						<label for="c_estatus_s" class="required"><i class="fas fa-id-card-alt"></i> Cambiar estatus</label>
						<select class="form-control text-uppercase" name="c_estatus" id="c_estatus_s">
							<option value="">Seleccione una opción</option>
							<option value="1">Restablecer usuario</option>
							<option value="2">Habilitar/Deshabilitar</option>
						</select>
					</div>
					<input type="hidden" id="idrand_s">
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_estatus"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif
@endsection