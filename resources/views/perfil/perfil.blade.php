@extends('plantilla')

@section('title', 'Panel principal - Arius Seguridad Integral C.A.')

{{dd(session())}}

<?php
// Separar la identificación en tipo y el número.
$identificacion_ = explode('-', session()->personal->cedula);
$tipo_identificacion_ = $identificacion_[0];
$identificacion_ = $identificacion_[1];

// Separar el teléfono 1 en prefijo y número.
$telefono1_ = explode(' ', session()->personal->telefono1);
$prefijo_telefono1_ = substr($telefono1_[0], 1, 3);
$telefono1_ = $telefono1_[1];

// Separar el teléfono 2 en prefijo y número.
$telefono2_ = "";
$prefijo_telefono2_ = "";
if (session()->personal->telefono2 != null and session()->personal->telefono2 != 'null') {
	$telefono2_ = explode(' ', session()->personal->telefono2);
	$prefijo_telefono2_ = substr($telefono2_[0], 1, 3);
	$telefono2_ = $telefono2_[1];
}
?>

@section('scripts')
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-user-circle"></i> Perfil</h4>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-4">
		<div class="card">
			<div class="card-body">
				<img src="{{url('/images/user-default.jpg')}}" alt="Imagen usuario" class="rounded-circle d-block border mx-auto mb-3" style="width: 120px;">
				<div class="w-75 mx-auto text-start">
					<p class="m-0"><i class="fas fa-id-card" style="width: 20px;"></i> <b>Nombre:</b> {{session('personal')->nombre}}</p>
					<p class="m-0"><i class="fas fa-user" style="width: 20px;"></i> <b>Usuario:</b> {{auth()->user()->usuario}}</p>
					<p class="m-0 text-capitalize"><i class="fas fa-phone-alt" style="width: 20px;"></i> <b>Tel:</b> {{session('personal')->telefono1}}</p>
					<p class="m-0 text-lowercase"><i class="fas fa-envelope" style="width: 20px;"></i> {{session('personal')->correo}}</p>
				</div>
			</div>
		</div>
	</div>

	<div class="col-8">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title text-uppercase my-2"><i class="fas fa-user-edit"></i> Datos personales</h4>
				<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="">
					@csrf
					@method('PATCH')
					<div class="form-row">
						<div class="form-group col-12 col-lg-2">
							<label for="c_identificacion" class="required"><i class="fas fa-id-badge"></i> Cédula</label>
							<div class="input-group">
								<select class="form-control text-center" name="c_prefijo_identificacion" id="c_prefijo_identificacion" style="height: 31px; margin-top: 1px;" disabled>
									@foreach ($lista_cedula as $index => $cedula)
									<option value="{{$cedula}}" <?= $tipo_identificacion_ == $cedula ? "selected" : "" ?>>{{$cedula}}</option>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase" name="c_identificacion" id="c_identificacion" value="{{$identificacion_}}" placeholder="Ingrese la cédula" style="width: calc(100% - 65px); height: 33px;" readonly>
							</div>
						</div>
						<div class="form-group col-12 col-lg-4">
							<label for="c_nombre_completo" class="required"><i class="fas fa-address-card"></i> Nombre / Razón social</label>
							<input type="text" class="form-control text-uppercase" name="c_nombre_completo" id="c_nombre_completo" value="{{session()->personal->nombre}}" placeholder="Ingrese el nombre completo">
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
							<input type="text" class="form-control text-uppercase" name="c_correo_electronico" id="c_correo_electronico" value="{{session()->personal->correo}}" placeholder="Ingrese el correo electrónico">
						</div>
						<div class="form-group col-6 col-lg-3">
							<label for="c_departamento" class="required"><i class="fas fa-hotel"></i> Departamento</label>
							<select class="form-control text-uppercase" name="c_departamento" id="c_departamento">
								<option value="">Seleccione una opción</option>
							</select>
						</div>
						<div class="form-group col-6 col-lg-3">
							<label for="c_cargo" class="required"><i class="fas fa-briefcase"></i> Cargo</label>
							<select class="form-control text-uppercase" name="c_cargo" id="c_cargo">
								<option value="">Seleccione una opción</option>
							</select>
						</div>
						<div class="col-12"></div>
						<div class="form-group col-12 col-lg-6">
							<label for="c_direccion" class="required"><i class="fas fa-map-marked-alt"></i> Dirección</label>
							<textarea class="form-control text-uppercase" name="c_direccion" id="c_direccion" placeholder="Ingrese la dirección" rows="3">{{session()->personal->direccion}}</textarea>
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="c_referencia"><i class="fas fa-sticky-note"></i> Punto de referencia</label>
							<textarea class="form-control text-uppercase" name="c_referencia" id="c_referencia" placeholder="Ingrese el punto de referencia" rows="3">{{session()->personal->referencia}}</textarea>
						</div>
					</div>

					<div class="text-end">
						<button type="submit" class="btn btn-primary" id="btn_guardar"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection