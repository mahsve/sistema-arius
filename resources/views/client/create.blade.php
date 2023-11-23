@extends('template')

@section('title', 'Registrar cliente - ' . env('TITLE'))

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Registrar cliente</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{url('clientes')}}" class="btn btn-primary btn-sm rounded"><i data-feather="chevron-left"></i> Regresar</a>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<form class="forms-sample" name="form-register" id="form-register" method="POST" action="{{url('clientes.store')}}">
			@csrf
			<div class="form-row justify-content-end">
				<div class="form-group col-4 mb-3 d-flex align-items-center">
					<label for="codemap" style="white-space: nowrap;">Código: <span class="required">*</span></label>
					<input type="text" class="form-control ms-3" name="codemap" id="codemap" value="2002" readonly="true">
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-3 mb-2">
					<label for="cedula">Cédula <span class="required">*</span></label>
					<input type="text" class="form-control" name="cedula" id="cedula" placeholder="Ingrese la cédula del cliente" required>
				</div>
				<div class="form-group col-9 mb-2">
					<label for="fullname">Nombre del cliente <span class="required">*</span></label>
					<input type="text" class="form-control" name="fullname" id="fullname" placeholder="Ingrese el nombre del cliente" required>
				</div>
				<div class="form-group col-6 mb-2">
					<label for="address">Dirección <span class="required">*</span></label>
					<textarea class="form-control" name="address" id="address" placeholder="Ingrese la dirección del cliente" required style="height: initial;"></textarea>
				</div>
				<div class="form-group col-6 mb-2">
					<label for="references">Punto de referencia</label>
					<textarea class="form-control" name="references" id="references" placeholder="Ingrese el punto de referencia" style="height: initial;"></textarea>
				</div>
				<div class="form-group col-3 mb-2">
					<label for="phone1">Teléfono 1: <span class="required">*</span></label>
					<input type="text" class="form-control" name="phone1" id="phone1" placeholder="Ingrese el teléfono del cliente" required>
				</div>
				<div class="form-group col-3 mb-2">
					<label for="phone2">Teléfono 2:</label>
					<input type="text" class="form-control" name="phone2" id="phone2" placeholder="Ingrese el teléfono del cliente">
				</div>
				<div class="form-group col-6 mb-2">
					<label for="email">Correo electrónico:</label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Ingrese el correo electrónico">
				</div>
			</div>

			<div class="col-12 mt-3">
				<div class="button-container text-end mb-3">
					<button type="button" class="btn btn-primary btn-sm rounded"><i data-feather="user-plus"></i> Agregar usuario</button>
				</div>

				<div class="table-responsive border rounded">
					<table id="data-table" class="table table-hover m-0">
						<thead>
							<tr>
								<th>N°</th>
								<th>Nombre y apellido</th>
								<th>Cédula</th>
								<th>Contraseña</th>
								<th>Teléfono</th>
								<th class="text-center"><i data-feather="settings" width="14px" height="14px"></i></th>
							</tr>
						</thead>

						<tbody>
							<tr>
								<td colspan="6" class="text-center">Sin usuarios registrados</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="text-end mt-2">
				<button type="submit" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
			</div>
		</form>
	</div>
</div>
@endsection