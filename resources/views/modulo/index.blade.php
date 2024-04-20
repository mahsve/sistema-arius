@extends('plantilla')

@section('title', 'Módulos - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('libraries/sortable/sortable.min.js')}}"></script>
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
<script src="{{url('js/app/modulo/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase m-0"><i class="fas fa-briefcase"></i> Módulos</h4>
		</div>
		<div class="col-6 text-end">
			<button type="button" class="btn btn-primary btn-sm" id="btn_organizar_modulos"><i class="fas fa-arrows-alt me-2"></i>Organizar</button>
			<button type="button" class="btn btn-primary btn-sm" id="btn_nuevo_modulo"><i class="fas fa-folder-plus me-2"></i>Agregar</button>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table id="data-table" class="table table-hover border-bottom m-0">
				<thead>
					<tr>
						<th class="ps-2" width="50"><i class="fas fa-sort-amount-down"></i> Órden</th>
						<th class="ps-2"><i class="fas fa-briefcase"></i> Módulo</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Creado</th>
						<th class="ps-2"><i class="fas fa-calendar-day"></i> Actualizado</th>
						<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
						<th class="ps-2 text-center"><i class="fas fa-toggle-on"></i></th>
						<th class="ps-2 text-center"><i class="fas fa-cogs"></i></th>
					</tr>
				</thead>
				<tbody>
					@foreach($modulos as $index => $modulo)
					@php
					$idrand = rand(100000,999999);
					@endphp
					<tr>
						<td class="py-1 px-2 text-center"><i class="fas fa-sort"></i> {{$modulo->orden}}</td>
						<td class="py-1 px-2"><i class="{{$modulo->icono}} me-2"></i>{{$modulo->modulo}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($modulo->created))}}</td>
						<td class="py-1 px-2">{{date('h:i:s A d/m/y', strtotime($modulo->updated))}}</td>
						<td class="py-1 px-2 text-center" id="contenedor_badge{{$idrand}}">
							@if ($modulo->estatus == "A")
							<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@else
							<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						<td class="py-1 px-2 text-center">
							<div class="form-check form-switch form-check-inline m-0">
								<input type="checkbox" class="form-check-input mx-auto switch_estatus" role="switch" id="switch_estatus{{$idrand}}" data-id="{{$idrand}}" value="{{$modulo->idmodulo}}" <?= $modulo->estatus == "A" ? "checked" : "" ?>>
							</div>
						</td>
						<td class="py-1 px-2" style="width: 20px;">
							<button type="button" class="btn btn-primary btn-sm btn-icon btn_editar" data-id="{{$modulo->idmodulo}}"><i class="fas fa-edit"></i></button>
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
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_label"><i class="fas fa-folder-plus"></i> Registrar módulo</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('modulos.store')}}">
					@csrf
					<div class="form-group mb-3">
						<label for="c_modulo_r" class="required"><i class="fas fa-briefcase"></i> Módulo</label>
						<input type="text" class="form-control text-uppercase" name="c_modulo" id="c_modulo_r" placeholder="Ingrese el nombre del módulo" minlength="3" required>
					</div>
					<div class="form-group mb-3">
						<label for="c_icono_r" class="d-flex justify-content-between">
							<span><i class="fas fa-briefcase"></i> Icono</span>
							<a href="https://fontawesome.com/v5/search?o=r&m=free" class="fw-bold text-dark" style="text-decoration: none;" target="blank">
								<i class="fas fa-icons"></i> Ver lista <i class="fas fa-external-link-alt"></i>
							</a>
						</label>
						<input type="text" class="form-control text-lowercase" name="c_icono" id="c_icono_r" placeholder="Ejemplo: fas fa-icons" minlength="3" required>
					</div>
					<div class="form-group mb-3">
						<label><i class="fas fa-desktop"></i> Vista previa</label>
						<div class="text-center border rounded p-3" style="height: 82px;">
							<i id="preview_r" style="font-size: 3rem;"></i>
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

<div class="modal fade" id="modal_modificar" tabindex="-1" aria-labelledby="modal_modificar_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_modificar_label"><i class="fas fa-folder-open"></i> Modificar módulo</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_actualizacion" id="formulario_actualizacion" method="POST" action="">
					@csrf
					@method('PATCH')
					<div class="form-group">
						<label for="c_modulo_m" class="required"><i class="fas fa-briefcase"></i> Módulo</label>
						<input type="text" class="form-control text-uppercase" name="c_modulo" id="c_modulo_m" placeholder="Ingrese el nombre del módulo" minlength="3" required>
					</div>
					<div class="form-group mb-3">
						<label for="c_icono_m" class="d-flex justify-content-between">
							<span><i class="fas fa-briefcase"></i> Icono</span>
							<a href="https://fontawesome.com/v5/search?o=r&m=free" class="fw-bold text-dark" style="text-decoration: none;" target="blank">
								<i class="fas fa-icons"></i> Ver lista <i class="fas fa-external-link-alt"></i>
							</a>
						</label>
						<input type="text" class="form-control text-lowercase" name="c_icono" id="c_icono_m" placeholder="Ejemplo: fas fa-icons" minlength="3" required>
					</div>
					<div class="form-group mb-3">
						<label><i class="fas fa-desktop"></i> Vista previa</label>
						<div class="text-center border rounded p-3" style="height: 82px;">
							<i id="preview_m" style="font-size: 3rem;"></i>
						</div>
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

<div class="modal fade" id="modal_organizar" tabindex="-1" aria-labelledby="modal_organizar_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_organizar_label"><i class="fas fa-folder-plus"></i> Organizar módulos</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_organizar" id="formulario_organizar" method="POST" action="{{route('modulos.order')}}">
					@csrf
					<ul id="lista-modulos" class="list-group border rounded border-bottom-0 mb-3">
						@foreach($modulos as $modulo)
						<li class="list-group-item border-0 border-bottom d-flex justify-content-between">
							<p class="fw-bold m-0"><i class="{{$modulo->icono}} text-center me-2" style="width: 20px;"></i> {{$modulo->modulo}}</p>
							<span><i class="fas fa-sort"></i> <span class="o_htmlorden">{{$modulo->orden}}</span></span>
							<input type="hidden" class="o_idmodulo" name="modulo[]" value="{{$modulo->idmodulo}}">
							<input type="hidden" class="o_orden" name="orden[]" value="{{$modulo->orden}}">
						</li>
						@endforeach
					</ul>
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_guardar"><i class="fas fa-save me-2"></i>Guardar cambios</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection