@extends('template')

@section('title', 'Instalación de equipos - ' . env('TITLE'))

@section('scripts')
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Instalación y configuración de equipos</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('clientes.index')}}" class="btn btn-primary btn-sm rounded"><i data-feather="chevron-left"></i> Regresar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="form-register" id="form-register" method="POST" action="{{route('clientes.update', ['cliente' => $client->id_codigo])}}">
			@csrf
			@method('PATCH')

			<div class="form-row">
				<div id="nm_container" class="form-group col-6">
					<label for="fullname">Panel y versión del sistema <span class="required">*</span></label>
					<input type="text" class="form-control" name="fullname" id="fullname" placeholder="Ingrese el nombre del cliente" required>
				</div>
				<div class="form-group col-3">
					<label for="phone1">Reporta por <span class="required">*</span></label>
					<input type="text" class="form-control" name="phone1" id="phone1" placeholder="Ingrese el teléfono del cliente" required>
				</div>
				<div class="form-group col-3">
					<label for="phone2">Modelo de teclado</label>
					<input type="text" class="form-control" name="phone2" id="phone2" placeholder="Ingrese el teléfono del cliente">
				</div>
				<div class="form-group col-3">
					<label for="text">N° de teléfono asig. <span class="required">*</span></label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Ingrese el correo electrónico" required>
				</div>
				<div class="form-group col-3">
					<label for="text">Fecha de instalación <span class="required">*</span></label>
					<input type="date" class="form-control" name="email" id="email" placeholder="Ingrese el correo electrónico" required>
				</div>
				<div class="form-group col-3">
					<label for="text">Ubicación de panel <span class="required">*</span></label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Ingrese el correo electrónico" required>
				</div>
				<div class="form-group col-3">
					<label for="text">Particiones del sistema <span class="required">*</span></label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Ingrese el correo electrónico" required>
				</div>
				<div class="form-group col-3">
					<label for="text">IMEI <span class="required">*</span></label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Ingrese el correo electrónico" required>
				</div>
        <div class="form-group col-3">
					<label for="text">Línea principal <span class="required">*</span></label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Ingrese el correo electrónico" required>
				</div>
        <div class="form-group col-3">
					<label for="text">Línea respaldo <span class="required">*</span></label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Ingrese el correo electrónico" required>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-12">
					<label for="observation">Observación: </label>
					<textarea class="form-control" name="observation" id="observation" placeholder="Ingrese las observaciones (Opcional)" rows="5" style="height: initial;">{{$client->observaciones}}</textarea>
				</div>
			</div>

			<div class="text-end">
				<button type="submit" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
			</div>
		</form>
	</div>
</div>
@endsection