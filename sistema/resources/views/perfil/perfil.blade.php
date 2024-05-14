@extends('plantilla')

@section('title', 'Mi perfil - Arius Seguridad Integral C.A.')

@section('styles')
<style>
	.image-container::before {
		content: '';
		position: absolute;
		top: 0px;
		left: 0px;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, .3);
		z-index: 1;
	}

	.image-container>* {
		position: relative;
		z-index: 2;
	}
</style>
@endsection

@section('scripts')
<script src="{{url('js/app/perfil/perfil.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-user-circle"></i> Mi perfil</h4>
		</div>
	</div>
</div>

@php
$ruta_background = url('images/background-camaras.jpg');
@endphp

<div class="row">
	<div class="col-12 col-md-4 col-lg-12 col-xl-4">
		<div class="card mb-4 overflow-hidden">
			<div class="position-relative image-container p-4" style="background-image: url('{{$ruta_background}}'); background-size: cover; background-position: center;">
				<img src="{{url('/images/user-default.jpg')}}" alt="Imagen usuario" class="rounded-circle d-block border mx-auto shadow" style="width: 120px;">
			</div>
			<div class="card-body">
				<div class="mx-auto text-start" style="width: 100%; max-width: 250px;">
					<p class="m-0"><i class="fas fa-id-card" style="width: 20px;"></i> <b>Nombre:</b> {{session('personal')->nombre}}</p>
					<p class="m-0"><i class="fas fa-user" style="width: 20px;"></i> <b>Usuario:</b> {{auth()->user()->usuario}}</p>
					<p class="m-0 text-capitalize"><i class="fas fa-phone-alt" style="width: 20px;"></i> <b>Tel:</b> {{session('personal')->telefono1}}</p>
					<p class="m-0 text-lowercase"><i class="fas fa-envelope" style="width: 20px;"></i> {{session('personal')->correo}}</p>
				</div>
			</div>
		</div>
	</div>

	<div class="col-12 col-md-8 col-lg-12 col-xl-8">
		<div class="card mb-4">
			<div class="card-body">
				<h4 class="card-title text-uppercase my-2"><i class="fas fa-user-edit"></i> Datos personales</h4>
				<form class="forms-sample" name="formulario_perfil" id="formulario_perfil" method="POST" action="{{route('profile.update')}}">
					@csrf
					<div class="form-row">
						<div class="form-group col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
							<label for="c_identificacion" class="required"><i class="fas fa-id-badge"></i> Cédula</label>
							<div class="input-group">
								<select class="form-control text-center" id="c_prefijo_identificacion" style="height: 31px; margin-top: 1px;" disabled>
									@foreach ($lista_cedula as $index => $cedula)
									<option value="{{$cedula}}" <?= $perfil->ti == $cedula ? "selected" : "" ?>>{{$cedula}}</option>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase" id="c_identificacion" value="{{$perfil->id}}" placeholder="Ingrese la cédula" style="width: calc(100% - 65px); height: 33px;" readonly>
							</div>
						</div>
						<div class="form-group col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
							<label for="c_nombre_completo" class="d-block text-truncate required"><i class="fas fa-address-card"></i> Nombre / Razón social</label>
							<input type="text" class="form-control text-uppercase" name="c_nombre_completo" id="c_nombre_completo" value="{{session('personal')->nombre}}" placeholder="Ingrese el nombre completo">
						</div>
						<div class="form-group col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
							<label for="c_telefono1" class="required"><i class="fas fa-phone-alt"></i> Teléfono 1</label>
							<div class="input-group">
								<select class="form-control text-center" name="c_prefijo_telefono1" id="c_prefijo_telefono1" style="height: 31px; margin-top: 1px;">
									<option value="">COD.</option>
									@foreach ($lista_prefijos as $index => $prefijo)
									<optgroup label="{{$index}}">
										@foreach ($prefijo as $codigos)
										<option value="{{$codigos}}" <?= $perfil->pt1 == $codigos ? "selected" : "" ?>>{{$codigos}}</option>
										@endforeach
									</optgroup>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase" name="c_telefono1" id="c_telefono1" value="{{$perfil->tl1}}" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
							</div>
						</div>
						<div class="form-group col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
							<label for="c_telefono2"><i class="fas fa-phone-alt"></i> Teléfono 2</label>
							<div class="input-group">
								<select class="form-control text-center" name="c_prefijo_telefono2" id="c_prefijo_telefono2" style="height:31px; margin-top: 1px;">
									<option value="">COD.</option>
									@foreach ($lista_prefijos as $index => $prefijo)
									<optgroup label="{{$index}}">
										@foreach ($prefijo as $codigos)
										<option value="{{$codigos}}" <?= $perfil->pt2 == $codigos ? "selected" : "" ?>>{{$codigos}}</option>
										@endforeach
									</optgroup>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase" name="c_telefono2" id="c_telefono2" value="{{$perfil->tl2}}" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
							</div>
						</div>
						<div class="form-group col-12 col-lg-8">
							<label for="c_correo_electronico" class="required"><i class="fas fa-envelope"></i> Correo electrónico</label>
							<input type="text" class="form-control text-uppercase" name="c_correo_electronico" id="c_correo_electronico" value="{{session('personal')->correo}}" placeholder="Ingrese el correo electrónico">
						</div>
						<div class="form-group col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
							<label for="c_departamento" class="required"><i class="fas fa-hotel"></i> Departamento</label>
							<select class="form-control text-uppercase" id="c_departamento" disabled>
								<option value="">Seleccione una opción</option>
								@foreach ($departamentos as $departamento)
								<option value="{{$departamento->iddepartamento}}" <?= $departamento->iddepartamento == $perfil->iddepartamento ? "selected" : "" ?>>{{$departamento->departamento}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
							<label for="c_cargo" class="required"><i class="fas fa-briefcase"></i> Cargo</label>
							<select class="form-control text-uppercase" id="c_cargo" disabled>
								<option value="">Seleccione una opción</option>
								@foreach ($cargos as $cargo)
								<option value="{{$cargo->idcargo}}" <?= $cargo->idcargo == $perfil->idcargo ? "selected" : "" ?>>{{$cargo->cargo}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
							<label for="c_usuario" class="d-block text-truncate required"><i class="fas fa-user"></i> Usuario</label>
							<input type="text" class="form-control text-uppercase" name="c_usuario" id="c_usuario" value="{{auth()->user()->usuario}}" placeholder="Ingrese el nombre de usuario">
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="c_direccion" class="required"><i class="fas fa-map-marked-alt"></i> Dirección</label>
							<textarea class="form-control text-uppercase" name="c_direccion" id="c_direccion" placeholder="Ingrese la dirección" rows="3">{{session('personal')->direccion}}</textarea>
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="c_referencia"><i class="fas fa-sticky-note"></i> Punto de referencia</label>
							<textarea class="form-control text-uppercase" name="c_referencia" id="c_referencia" placeholder="Ingrese el punto de referencia" rows="3">{{session('personal')->referencia}}</textarea>
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