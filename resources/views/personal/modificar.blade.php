@extends('plantilla')

@section('title', 'Modificar personal - ' . env('TITLE'))

@section('scripts')
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Modificar personal</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('personal.index')}}" class="btn btn-primary btn-sm rounded"><i data-feather="chevron-left"></i> Regresar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="form-register" id="form-register" method="POST" action="{{route('personal.update', ['personal' => $personal->cedula])}}">
			@csrf
			@method('PATCH')

			<div class="form-row">
				<div class="form-group col-3">
					<label for="cedula">Cédula <span class="required">*</span></label>
					<input type="text" class="form-control" name="cedula" id="cedula" placeholder="Ingrese la cédula" value="{{$personal->cedula}}" required>
				</div>
				<div class="form-group col-3">
					<label for="firstname">Nombres <span class="required">*</span></label>
					<input type="text" class="form-control" name="firstname" id="firstname" placeholder="Ingrese los nombres" value="{{$personal->nombres}}" required>
				</div>
				<div class="form-group col-3">
					<label for="lastname">Apellidos <span class="required">*</span></label>
					<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Ingrese los apellidos" value="{{$personal->apellidos}}" required>
				</div>

				<div class="form-group col-3">
					<label for="phone1">Teléfono 1 <span class="required">*</span></label>
					<input type="text" class="form-control" name="phone1" id="phone1" placeholder="Ingrese el teléfono del cliente" value="{{$personal->telefono1}}" required>
				</div>
				<div class="form-group col-3">
					<label for="phone2">Teléfono 2</label>
					<input type="text" class="form-control" name="phone2" id="phone2" placeholder="Ingrese el teléfono del cliente" value="{{$personal->telefono2}}">
				</div>
				<div class="form-group col-6">
					<label for="email">Correo electrónico <span class="required">*</span></label>
					<input type="email" class="form-control" name="email" id="email" placeholder="Ingrese el correo electrónico" value="{{$personal->correo}}" required>
				</div>
				<div class="form-group col-3">
					<label for="id_position">Cargo <span class="required">*</span></label>
					<select class="form-control" name="id_position" id="id_position" required>
						<option value="0">Seleccione una opción</option>
						@foreach ($positions as $cargo)
						<option value="{{$cargo->id_cargo}}" {{$cargo->id_cargo == $personal->id_cargo ? "selected" : ""}}>{{$cargo->cargo}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-12">
					<label for="address">Dirección <span class="required">*</span></label>
					<textarea class="form-control" name="address" id="address" placeholder="Ingrese la dirección del cliente" required style="height: initial;">{{$personal->direccion}}</textarea>
				</div>
			</div>

			<div class="text-end">
				<button type="submit" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
			</div>
		</form>
	</div>
</div>
@endsection