@extends('template')

@section('title', 'Registrar mapa de zona - ' . env('TITLE'))

@section('scripts')
<script src="{{url('js/app/create-client.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Registrar mapa de zona</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('mapas-de-zonas.index')}}" class="btn btn-primary btn-sm rounded"><i data-feather="chevron-left"></i> Regresar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="form-register" id="form-register" method="POST" action="{{route('mapas-de-zonas.store')}}">
			@csrf

			<div class="form-row justify-content-end">
				<div class="form-group col-3 mb-3 d-flex align-items-center">
					<label for="code_map" style="white-space: nowrap;">Código: <span class="required">*</span></label>
					<input type="text" class="form-control ms-3 text-end" name="code_map" id="code_map" required>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-3">
					<label for="kind_of_client">Tipo cliente <span class="required">*</span></label>
					<select class="form-control" name="kind_of_client" id="kind_of_client" required>
						<option value="N">Natural</option>
						<option value="J">Jurídico</option>
					</select>
				</div>
				<div id="id_container" class="form-group col-3">
					<label for="identification">Cédula <span class="required">*</span></label>
					<input type="text" class="form-control" name="identification" id="identification" placeholder="Ingrese la cédula" required>
				</div>
				<div id="nm_container" class="form-group col-6">
					<label for="fullname">Nombre del cliente <span class="required">*</span></label>
					<input type="text" class="form-control" name="fullname" id="fullname" placeholder="Ingrese el nombre del cliente" readonly>
				</div>

				<div class="form-group col-3">
					<label for="phone1">Teléfono 1 <span class="required">*</span></label>
					<input type="text" class="form-control" name="phone1" id="phone1" placeholder="Ingrese el teléfono del cliente" readonly>
				</div>
				<div class="form-group col-3">
					<label for="phone2">Teléfono 2</label>
					<input type="text" class="form-control" name="phone2" id="phone2" placeholder="Ingrese el teléfono del cliente" readonly>
				</div>
				<div class="form-group col-6">
					<label for="email">Correo electrónico <span class="required">*</span></label>
					<input type="email" class="form-control" name="email" id="email" placeholder="Ingrese el correo electrónico" readonly>
				</div>

				<div class="form-group col-6">
					<label for="address">Dirección <span class="required">*</span></label>
					<textarea class="form-control" name="address" id="address" placeholder="Ingrese la dirección del cliente" style="height: initial;"></textarea>
				</div>
				<div class="form-group col-6">
					<label for="references">Punto de referencia</label>
					<textarea class="form-control" name="references" id="references" placeholder="Ingrese el punto de referencia" style="height: initial;"></textarea>
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
						<tr class="no-users">
							<td colspan="7" class="text-center">Sin usuarios registrados</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="row align-items-center mb-3">
				<div class="col-6 text-start">
					<h5 class="m-0">Zonas</h5>
				</div>
				<div class="col-6 text-end">
					<button type="button" class="btn btn-primary btn-sm rounded" id="btn-new-zone"><i data-feather="plus"></i> Agregar zona</button>
				</div>
			</div>

			<div class="table-responsive border rounded mb-4">
				<table id="table-zone" class="table table-hover m-0">
					<thead>
						<tr>
							<th class="px-2 text-center" width="36px">N°</th>
							<th class="px-2">Descripción de zona</th>
							<th class="px-2">Configuración</th>
							<th class="px-2">Equipos</th>
							<th class="px-1 text-center" width="42px"><i data-feather="settings" width="14px" height="14px"></i></th>
						</tr>
					</thead>

					<tbody>
						<tr class="no-zones">
							<td colspan="7" class="text-center">Sin zonas registradas</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="row align-items-center mb-3">
				<div class="col-6 text-start">
					<h5 class="m-0">Otros datos</h5>
				</div>
			</div>

			<?php /*
			<div class="col-12">Horario de trabajo</div>
			<div class="form-row">
				<div class="col-6">
					<div class="form-row">
						<div class="form-group col-6">
							<label for="dia_inicio">Lunes a Viernes<span class="required">*</span></label>
							<div class="form-row">
								<div class="col-6">
									<input type="text" class="form-control" name="dia_inicio" id="dia_inicio" placeholder="Hora inicio" readonly>
								</div>
								<div class="col-6">
									<input type="text" class="form-control" name="dia_inicio" id="dia_inicio" placeholder="Hora fin" readonly>
								</div>
							</div>
						</div>
						<div class="form-group col-6">
							<label for="phone2">Sabado y domingo</label>
							<input type="text" class="form-control" name="phone2" id="phone2" placeholder="Ingrese canal de comunicación">
						</div>
					</div>
				</div>

				<div class="form-group col-4">
					<label for="phone1">Panel y Versión del sistema<span class="required">*</span></label>
					<input type="text" class="form-control" name="phone1" id="phone1" placeholder="Ingrese el panel y la versión">
				</div>
				<div class="form-group col-3">
					<label for="phone2">Reporta por:</label>
					<input type="text" class="form-control" name="phone2" id="phone2" placeholder="Ingrese canal de comunicación">
				</div>
				<div class="form-group col-6">
					<label for="email">N° de teléfono asig. <span class="required">*</span></label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Ingrese el número asignado">
				</div>
			</div>
			*/ ?>

			<div class="form-row">
				<div class="form-group col-12">
					<label for="observation">Observación: </label>
					<textarea class="form-control" name="observation" id="observation" placeholder="Ingrese las observaciones (Opcional)" rows="5" style="height: initial;"></textarea>
				</div>
			</div>

			<div class="text-end">
				<button type="submit" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
			</div>
		</form>
	</div>
</div>

<script>
	const buscarCliente = () => {
		fetch(`{{url('buscar-cliente')}}/${KindOfClientKey.value}/${identificationKey.value}`, {
			headers: {
				"X-CSRF-Token": document.querySelector('input[name=_token]').value
			},
			method: 'get'
		}).then(response => response.json()).then((data) => {
			if (data) {
				document.getElementById("fullname").value = data.nombre_completo;
				document.getElementById("phone1").value = data.telefono1;
				document.getElementById("phone2").value = data.telefono2;
				document.getElementById("email").value = data.correo_electronico;
			} else {
				document.getElementById("fullname").value = "";
				document.getElementById("phone1").value = "";
				document.getElementById("phone2").value = "";
				document.getElementById("email").value = "";
				alert("¡El usuario no existe!");
			}
		});
	};

	let identificationKey = document.getElementById("identification");
	let KindOfClientKey = document.getElementById("kind_of_client");
	identificationKey.addEventListener("change", buscarCliente);
	KindOfClientKey.addEventListener("change", buscarCliente);
</script>
@endsection