@extends('plantilla')

@section('title', 'Registrar personal - ' . env('TITLE'))

@section('scripts')
<script src="{{url('js/app/personal/registrar.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-folder-plus"></i> Registrar personal</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('personal.index')}}" class="btn btn-primary btn-sm "><i class="fas fa-chevron-left me-2"></i>Regresar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('personal.store')}}">
			@csrf
			<div class="form-row">
				<div class="form-group col-12 col-lg-2">
					<label for="c_identificacion" class="required"><i class="fas fa-id-badge"></i> Cédula</label>
					<div class="input-group">
						<select class="form-control text-center" name="c_prefijo_identificacion" id="c_prefijo_identificacion" style="height: 31px; margin-top: 1px;">
							@foreach ($lista_cedula as $index => $cedula)
							<option value="{{$cedula}}">{{$cedula}}</option>
							@endforeach
						</select>
						<input type="text" class="form-control text-uppercase" name="c_identificacion" id="c_identificacion" placeholder="Ingrese la cédula" style="width: calc(100% - 65px); height: 33px;">
					</div>
				</div>
				<div class="form-group col-12 col-lg-4">
					<label for="c_nombre_completo" class="required"><i class="fas fa-address-card"></i> Nombre completo</label>
					<input type="text" class="form-control text-uppercase" name="c_nombre_completo" id="c_nombre_completo" placeholder="Ingrese el nombre completo">
				</div>
				<div class="form-group col-6 col-lg-3">
					<label for="c_telefono1" class="required"><i class="fas fa-phone-alt"></i> Teléfono 1</label>
					<div class="input-group">
						<select class="form-control text-center" name="c_prefijo_telefono1" id="c_prefijo_telefono1" style="height: 31px; margin-top: 1px;">
							<option value="">COD.</option>
							@foreach ($lista_prefijos as $index => $prefijo)
							<optgroup label="{{$index}}">
								@foreach ($prefijo as $codigos)
								<option value="{{$codigos}}">{{$codigos}}</option>
								@endforeach
							</optgroup>
							@endforeach
						</select>
						<input type="text" class="form-control text-uppercase" name="c_telefono1" id="c_telefono1" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
					</div>
				</div>
				<div class="form-group col-6 col-lg-3">
					<label for="c_telefono2"><i class="fas fa-phone-alt"></i> Teléfono 2</label>
					<div class="input-group">
						<select class="form-control text-center" name="c_prefijo_telefono2" id="c_prefijo_telefono2" style="height: 31px; margin-top: 1px;">
							<option value="">COD.</option>
							@foreach ($lista_prefijos as $index => $prefijo)
							<optgroup label="{{$index}}">
								@foreach ($prefijo as $codigos)
								<option value="{{$codigos}}">{{$codigos}}</option>
								@endforeach
							</optgroup>
							@endforeach
						</select>
						<input type="text" class="form-control text-uppercase" name="c_telefono2" id="c_telefono2" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
					</div>
				</div>
				<div class="form-group col-12 col-lg-6">
					<label for="c_correo_electronico" class="required"><i class="fas fa-envelope"></i> Correo electrónico</label>
					<input type="text" class="form-control text-uppercase" name="c_correo_electronico" id="c_correo_electronico" placeholder="Ingrese el correo electrónico">
				</div>
				<div class="form-group col-6 col-lg-3">
					<div class="row align-items-center">
						<div class="col-8">
							<label for="c_departamento" class="required"><i class="fas fa-hotel"></i> Departamento</label>
						</div>
						@if (isset($crear_departamento))
						<div class="col-4 text-end">
							<button type="button" class="btn btn-sm btn-primary btn-auxilar ms-auto btn_nuevo_dep" data-form="main"><i class="fas fa-plus"></i></button>
						</div>
						@endif
					</div>
					<select class="form-control text-uppercase" name="c_departamento" id="c_departamento">
						<option value="">Seleccione</option>
						@foreach ($departamentos as $departamento)
						<option value="{{$departamento->iddepartamento}}">{{$departamento->departamento}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-6 col-lg-3">
					<div class="row align-items-center">
						<div class="col-8">
							<label for="c_cargo" class="required"><i class="fas fa-briefcase"></i> Cargo</label>
						</div>
						@if (isset($crear_departamento))
						<div class="col-4 text-end">
							<button type="button" class="btn btn-sm btn-primary btn-auxilar ms-auto btn_nuevo_car" data-form="main"><i class="fas fa-plus"></i></button>
						</div>
						@endif
					</div>
					<select class="form-control text-uppercase" name="c_cargo" id="c_cargo">
						<option value="">Seleccione</option>
					</select>
				</div>
				<div class="col-12"></div>
				<div class="form-group col-12 col-lg-6">
					<label for="c_direccion" class="required"><i class="fas fa-map-marked-alt"></i> Dirección</label>
					<textarea class="form-control text-uppercase" name="c_direccion" id="c_direccion" placeholder="Ingrese la dirección" rows="3"></textarea>
				</div>
				<div class="form-group col-12 col-lg-6">
					<label for="c_referencia"><i class="fas fa-sticky-note"></i> Punto de referencia</label>
					<textarea class="form-control text-uppercase" name="c_referencia" id="c_referencia" placeholder="Ingrese el punto de referencia" rows="3"></textarea>
				</div>
			</div>

			<div class="text-end">
				<button type="reset" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Limpiar</button>
				<button type="submit" class="btn btn-primary" id="btn_guardar"><i class="fas fa-save me-2"></i>Guardar</button>
			</div>
		</form>
	</div>
</div>

@if (isset($crear_departamento))
<div class="modal fade" id="modal_registrar_dep" tabindex="-1" aria-labelledby="modal_registrar_dep_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_dep_label"><i class="fas fa-paste"></i> Registro rápido</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro" id="formulario_registro_dep" method="POST" action="{{route('departamentos.store')}}">
					@csrf
					<div class="form-group">
						<label for="c_departamento_aux" class="required"><i class="fas fa-hotel"></i> Departamento</label>
						<input type="text" class="form-control text-uppercase" name="c_departamento" id="c_departamento_aux" placeholder="Ingrese el nombre del departamento">
					</div>
					<input type="hidden" name="modulo" id="btn_click_dep" value="personal">
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_registrar_dep"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif

@if (isset($crear_cargo))
<div class="modal fade" id="modal_registrar_car" tabindex="-1" aria-labelledby="modal_registrar_car_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_car_label"><i class="fas fa-paste"></i> Registro rápido</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro" id="formulario_registro_car" method="POST" action="{{route('cargos.store')}}">
					@csrf
					<div class="form-group">
						<div class="row align-items-center">
							<div class="col">
								<label for="c_departamento_c" class="required"><i class="fas fa-hotel"></i> Departamento</label>
							</div>
							@if (isset($crear_departamento))
							<div class="col text-end">
								<button type="button" class="btn btn-sm btn-primary btn-auxilar ms-auto btn_nuevo_dep" data-form="modal"><i class="fas fa-plus"></i></button>
							</div>
							@endif
						</div>
						<select class="form-control text-uppercase" name="c_departamento" id="c_departamento_c">
							<option value="">Seleccione el departamento</option>
							@foreach ($departamentos as $departamento)
							<option value="{{$departamento->iddepartamento}}">{{$departamento->departamento}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group mb-3">
						<label for="c_cargo_aux" class="required"><i class="fas fa-briefcase"></i> Cargo</label>
						<input type="text" class="form-control text-uppercase" name="c_cargo" id="c_cargo_aux" placeholder="Ingrese el nombre del cargo">
					</div>
					<input type="hidden" name="modulo" id="btn_click_car" value="personal">
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_registrar_car"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif
@endsection