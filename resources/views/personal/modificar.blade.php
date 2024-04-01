@extends('plantilla')

@section('title', 'Modificar personal - ' . env('TITLE'))

@php
// Separar la identificación en tipo y el número.
$identificacion_ = explode('-', $personal->cedula);
$tipo_identificacion_ = $identificacion_[0];
$identificacion_ = $identificacion_[1];

// Separar el teléfono 1 en prefijo y número.
$telefono1_ = explode(' ', $personal->telefono1);
$prefijo_telefono1_ = substr($telefono1_[0], 1, 3);
$telefono1_ = $telefono1_[1];

// Separar el teléfono 2 en prefijo y número.
$telefono2_	= "";
$prefijo_telefono2_ = "";
if ($personal->telefono2 != null and $personal->telefono2 != 'null') {
	$telefono2_ = explode(' ', $personal->telefono2);
	$prefijo_telefono2_ = substr($telefono2_[0], 1, 3);
	$telefono2_ = $telefono2_[1];
}
@endphp

@section('scripts')
<script src="{{url('js/app/personal/modificar.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase m-0"><i class="fas fa-folder-open"></i> Modificar personal</h4>
		</div>
		<div class="col-6 text-end">
		<a href="{{route('personal.index')}}" class="btn btn-primary btn-sm "><i class="fas fa-chevron-left me-2"></i>Regresar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('personal.update', ['personal' => $personal->cedula])}}">
			@csrf
			@method('PATCH')
			<div class="form-row">
				<div class="form-group col-12 col-lg-2">
					<label for="c_identificacion" class="required"><i class="fas fa-id-badge"></i> Cédula</label>
					<div class="input-group">
						<input type="text" class="form-control text-center" value="{{$tipo_identificacion_}}"  style="height: 33px;" readonly>
						<input type="text" class="form-control text-uppercase" value="{{$identificacion_}}" style="width: calc(100% - 65px); height: 33px;" readonly>
					</div>
				</div>
				<div class="form-group col-12 col-lg-4">
					<label for="c_nombre_completo" class="required"><i class="fas fa-address-card"></i> Nombre / Razón social</label>
					<input type="text" class="form-control text-uppercase" name="c_nombre_completo" id="c_nombre_completo" value="{{$personal->nombre}}" placeholder="Ingrese el nombre completo">
				</div>
				<div class="form-group col-6 col-lg-3">
					<label for="c_telefono1" class="required"><i class="fas fa-phone-alt"></i> Teléfono 1</label>
					<div class="input-group">
						<select class="form-control text-center" name="c_prefijo_telefono1" id="c_prefijo_telefono1" style="height: 31px; margin-top: 1px;">
							<option value="">COD.</option>
							@foreach ($lista_prefijos as $index => $prefijo)
								<optgroup label="{{$index}}">
									@foreach ($prefijo as $codigos)
									<option value="{{$codigos}}" <?= $prefijo_telefono1_ == $codigos ? "selected" : "" ?>>{{$codigos}}</option>
									@endforeach
								</optgroup>
							@endforeach
						</select>
						<input type="text" class="form-control text-uppercase" name="c_telefono1" id="c_telefono1" value="{{$telefono1_}}" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
					</div>
				</div>
				<div class="form-group col-6 col-lg-3">
					<label for="c_telefono2"><i class="fas fa-phone-alt"></i> Teléfono 2</label>
					<div class="input-group">
						<select class="form-control text-center" name="c_prefijo_telefono2" id="c_prefijo_telefono2" style="height:31px; margin-top: 1px;">
							<option value="">COD.</option>
							@foreach ($lista_prefijos as $index => $prefijo)
								<optgroup label="{{$index}}">
									@foreach ($prefijo as $codigos)
									<option value="{{$codigos}}" <?= $prefijo_telefono2_ == $codigos ? "selected" : "" ?>>{{$codigos}}</option>
									@endforeach
								</optgroup>
							@endforeach
						</select>
						<input type="text" class="form-control text-uppercase" name="c_telefono2" id="c_telefono2" value="{{$telefono2_}}" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
					</div>
				</div>
				<div class="form-group col-12 col-lg-6">
					<label for="c_correo_electronico" class="required"><i class="fas fa-envelope"></i> Correo electrónico</label>
					<input type="email" class="form-control text-uppercase" name="c_correo_electronico" id="c_correo_electronico" value="{{$personal->correo}}" placeholder="Ingrese el correo electrónico">
				</div>
				<div class="form-group col-6 col-lg-2">
					<label for="c_departamento" class="required"><i class="fas fa-hotel"></i> Departamento</label>
					<select class="form-control text-uppercase" name="c_departamento" id="c_departamento" required>
						<option value="">Seleccione una opción</option>
						@foreach ($departamentos as $departamento)
						<option value="{{$departamento->iddepartamento}}" <?= $departamento->iddepartamento == $personal->iddepartamento ? "selected" : "" ?>>{{$departamento->departamento}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-6 col-lg-2">
					<label for="c_cargo" class="required"><i class="fas fa-briefcase"></i> Cargo</label>
					<select class="form-control text-uppercase" name="c_cargo" id="c_cargo" required>
						<option value="">Seleccione una opción</option>
						@foreach ($cargos as $cargo)
						<option value="{{$cargo->idcargo}}" <?= $cargo->idcargo == $personal->idcargo ? "selected" : "" ?>>{{$cargo->cargo}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-6 col-lg-2">
					<label for="c_rol" class="required"><i class="fas fa-id-card-alt"></i> Rol</label>
					<select class="form-control text-uppercase" name="c_rol" id="c_rol" required>
						<option value="">Seleccione</option>
						@foreach ($roles as $rol)
						<option value="{{$rol->idrol}}" <?= $rol->idrol == $personal->idrol ? "selected" : "" ?>>{{$rol->rol}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-12"></div>
				<div class="form-group col-12 col-lg-6">
					<label for="c_direccion" class="required"><i class="fas fa-map-marked-alt"></i> Dirección</label>
					<textarea class="form-control text-uppercase" name="c_direccion" id="c_direccion" placeholder="Ingrese la dirección" rows="3">{{$personal->direccion}}</textarea>
				</div>
				<div class="form-group col-12 col-lg-6">
					<label for="c_referencia"><i class="fas fa-sticky-note"></i> Punto de referencia</label>
					<textarea class="form-control text-uppercase" name="c_referencia" id="c_referencia" placeholder="Ingrese el punto de referencia" rows="3">{{$personal->referencia}}</textarea>
				</div>
			</div>

			<div class="text-end">
				<button type="reset" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Limpiar</button>
				<button type="submit" class="btn btn-primary" id="btn_guardar"><i class="fas fa-save me-2"></i>Guardar</button>
			</div>
		</form>
	</div>
</div>
@endsection