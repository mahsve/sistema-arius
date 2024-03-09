@extends('plantilla')

@section('title', 'Modificar mapa de zona - ' . env('TITLE'))

@section('scripts')
<script src="{{url('js/app/create-client.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Modificar mapa de zona</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('mapas-de-zonas.index')}}" class="btn btn-primary btn-sm rounded"><i data-feather="chevron-left"></i> Regresar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="form-register" id="form-register" method="POST" action="{{route('mapas-de-zonas.update', ['mapas_de_zona' => $client->id_codigo])}}">
			@csrf
			@method('PATCH')

			<div class="form-row justify-content-end">
				<div class="form-group col-3 mb-3 d-flex align-items-center">
					<label for="code_map" style="white-space: nowrap;">Código: <span class="required">*</span></label>
					<input type="text" class="form-control ms-3 text-end" name="code_map" id="code_map" value="{{$client->id_codigo}}" readonly required>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-3">
					<label for="kind_of_client">Tipo cliente <span class="required">*</span></label>
					<select class="form-control" name="kind_of_client" id="kind_of_client" readonly>
						<option value="N" {{$client->tipo_cliente == "N" ? "selected" : ""}}>Natural</option>
						<option value="J" {{$client->tipo_cliente == "J" ? "selected" : ""}}>Jurídico</option>
					</select>
				</div>
				<div id="id_container" class="form-group col-3">
					<label for="identification">Cédula <span class="required">*</span></label>
					<input type="text" class="form-control" name="identification" id="identification" value="{{$client->identificacion}}" placeholder="Ingrese la cédula" readonly>
				</div>
				<div id="nm_container" class="form-group col-6">
					<label for="fullname">Nombre del cliente <span class="required">*</span></label>
					<input type="text" class="form-control" name="fullname" id="fullname" value="{{$client->nombre_completo}}" placeholder="Ingrese el nombre del cliente" readonly>
				</div>

				<div class="form-group col-3">
					<label for="phone1">Teléfono 1 <span class="required">*</span></label>
					<input type="text" class="form-control" name="phone1" id="phone1" value="{{$client->telefono1}}" placeholder="Ingrese el teléfono del cliente" readonly>
				</div>
				<div class="form-group col-3">
					<label for="phone2">Teléfono 2</label>
					<input type="text" class="form-control" name="phone2" id="phone2" value="{{$client->telefono2}}" placeholder="Ingrese el teléfono del cliente" readonly>
				</div>
				<div class="form-group col-6">
					<label for="email">Correo electrónico <span class="required">*</span></label>
					<input type="email" class="form-control" name="email" id="email" value="{{$client->correo_electronico}}" placeholder="Ingrese el correo electrónico" readonly>
				</div>

				<div class="form-group col-6">
					<label for="address">Dirección <span class="required">*</span></label>
					<textarea class="form-control" name="address" id="address" placeholder="Ingrese la dirección del cliente" required style="height: initial;">{{$client->direccion}}</textarea>
				</div>
				<div class="form-group col-6">
					<label for="references">Punto de referencia</label>
					<textarea class="form-control" name="references" id="references" placeholder="Ingrese el punto de referencia" style="height: initial;">{{$client->punto_referencia}}</textarea>
				</div>
			</div>

			<div class="row align-items-center mb-3">
				<div class="col-6 text-start">
					<h5 class="m-0">Contactos</h5>
				</div>
				<div class="col-6 text-end">
					<button type="button" class="btn btn-primary btn-sm rounded" id="btn-new-user"><i data-feather="user-plus"></i> Agregar usuario</button>
				</div>
			</div>

			<div class="table-responsive border rounded mb-4">
				<table id="table-user" class="table table-hover m-0">
					<thead>
						<tr>
							<th class="px-2 text-center" width="36px">N°</th>
							<th class="px-2">Nombre y apellido</th>
							<th class="px-2">Cédula</th>
							<th class="px-2">Contraseña</th>
							<th class="px-2">Teléfono</th>
							<th class="px-2">Nota</th>
							<th class="px-1 text-center" width="42px"><i data-feather="settings" width="14px" height="14px"></i></th>
						</tr>
					</thead>

					<tbody>
						@foreach ($contacts as $index => $contact)
						@php
						$idrand = rand(100000, 999999);
						@endphp
						<tr id="tr-user-{{$idrand}}">
							<input type="hidden" name="idcontact_[]" value="{{$contact->id_contacto}}">
							<td class="py-2 px-2 text-end users-counter">{{$index + 1}}</td>
							<td class="py-2 px-1"><input type="text" class="form-control border-0" name="fullname_[]" id="fullname_{{$idrand}}" value="{{$contact->nombre_completo}}" placeholder="Nombre completo"></td>
							<td class="py-2 px-1"><input type="text" class="form-control border-0" name="cedula_[]" id="cedula_{{$idrand}}" value="{{$contact->identificacion}}" placeholder="Cédula"></td>
							<td class="py-2 px-1"><input type="text" class="form-control border-0" name="password_[]" id="password_{{$idrand}}" value="{{$contact->contrasena}}" placeholder="Contraseña"></td>
							<td class="py-2 px-1"><input type="text" class="form-control border-0" name="phone_[]" id="phone_{{$idrand}}" value="{{$contact->telefono1}}" placeholder="Teléfono"></td>
							<td class="py-2 px-1"><input type="text" class="form-control border-0" name="note_[]" id="note_{{$idrand}}" value="{{$contact->observacion}}" placeholder="Nota (Opcional)"></td>
							<td class="py-0 px-1">
								<button type="button" class="btn btn-danger btn-sm rounded px-2" onclick="deleteTrElement('{{$idrand}}')">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
										<polyline points="3 6 5 6 21 6"></polyline>
										<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
									</svg>
								</button>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
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