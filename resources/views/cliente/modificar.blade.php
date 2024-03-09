@extends('plantilla')

@section('title', 'Modificar cliente - ' . env('TITLE'))

@section('scripts')
<script src="{{url('js/app/create-client.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Modificar cliente</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('clientes.index')}}" class="btn btn-primary btn-sm rounded"><i data-feather="chevron-left"></i> Regresar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="form-register" id="form-register" method="POST" action="{{route('clientes.update', ['cliente' => $client->identificacion])}}">
			@csrf
			@method('PUT')

			<div class="form-row">
				<div class="form-group col-3">
					<label for="kind_of_client">Tipo cliente <span class="required">*</span></label>
					<select class="form-control" name="kind_of_client" id="kind_of_client" readonly required>
						<option value="N" {{$client->tipo_cliente == "N" ? "selected" : ""}}>Natural</option>
						<option value="J" {{$client->tipo_cliente == "J" ? "selected" : ""}}>Jurídico</option>
					</select>
				</div>
				<div id="id_container" class="form-group col-3">
					<label for="identification">Cédula <span class="required">*</span></label>
					<input type="text" class="form-control" name="identification" id="identification" value="{{$client->identificacion}}" placeholder="Ingrese la cédula" readonly required>
				</div>
				<div id="nm_container" class="form-group col-6">
					<label for="fullname">Nombre del cliente <span class="required">*</span></label>
					<input type="text" class="form-control" name="fullname" id="fullname" value="{{$client->nombre_completo}}" placeholder="Ingrese el nombre del cliente" required>
				</div>

				<div class="form-group col-3">
					<label for="phone1">Teléfono 1 <span class="required">*</span></label>
					<input type="text" class="form-control" name="phone1" id="phone1" value="{{$client->telefono1}}" placeholder="Ingrese el teléfono del cliente" required>
				</div>
				<div class="form-group col-3">
					<label for="phone2">Teléfono 2</label>
					<input type="text" class="form-control" name="phone2" id="phone2" value="{{$client->telefono2}}" placeholder="Ingrese el teléfono del cliente">
				</div>
				<div class="form-group col-6">
					<label for="email">Correo electrónico <span class="required">*</span></label>
					<input type="email" class="form-control" name="email" id="email" value="{{$client->correo_electronico}}" placeholder="Ingrese el correo electrónico" required>
				</div>
			</div>

			<div class="text-end">
				<button type="submit" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
			</div>
		</form>
	</div>
</div>
@endsection