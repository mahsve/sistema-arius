@extends('plantilla')

@section('title', 'Registrar mapa de zona - ' . env('TITLE'))

@section('scripts')
<script src="{{url('js/app/mapa_de_zona/registrar.js')}}"></script>
@endsection

@php
// Separar la identificación en tipo y el número.
$identificacion_ = explode('-', $cliente->identificacion);
$tipo_identificacion_ = $identificacion_[0];
$identificacion_ = $identificacion_[1];

// Separar el teléfono 1 en prefijo y número.
$telefono1_ = explode(' ', $cliente->telefono1);
$prefijo_telefono1_ = substr($telefono1_[0], 1, 3);
$telefono1_ = $telefono1_[1];

// Separar el teléfono 2 en prefijo y número.
$telefono2_	= "";
$prefijo_telefono2_ = "";
if ($cliente->telefono2 != null and $cliente->telefono2 != 'null') {
	$telefono2_ = explode(' ', $cliente->telefono2);
	$prefijo_telefono2_ = substr($telefono2_[0], 1, 3);
	$telefono2_ = $telefono2_[1];
}
@endphp

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase m-0"><i class="fas fa-map-marked-alt"></i> Registrar mapa de zona</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('mapas_de_zonas.index')}}" class="btn btn-primary btn-sm "><i class="fas fa-chevron-left me-2"></i>Regresar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('mapas_de_zonas.store', ['id' => $cliente->identificacion])}}">
			@csrf
			<div class="form-row justify-content-end mb-3">
				<div class="form-group col-6 col-lg-2 mb-3">
					<label for="m_tipo_contrato" class="required">Contrato</label>
					<select class="form-control text-uppercase" name="m_tipo_contrato" id="m_tipo_contrato">
						@foreach($lista_contratos as $index => $contrato)
							<option value="{{$index}}">{{$contrato}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-6 col-lg-2 mb-3">
					<label for="m_codigo" class="required">Código</label>
					<input type="text" class="form-control text-uppercase text-end" name="m_codigo" id="m_codigo" placeholder="AUTOMATICO">
				</div>
			</div>

			<div class="form-row pb-3">
				<div class="form-group col-6 col-lg-2">
					<label for="c_tipo_identificacion" class="required"><i class="fas fa-id-badge"></i> Tipo ID.</label>
					<input type="text" class="form-control text-uppercase" id="c_tipo_identificacion" value="{{$tipos_identificaciones[$cliente->tipo_identificacion]}}" readonly>
				</div>
				<div id="contenedor_identificacion" class="form-group col-6 col-lg-3">
					<label for="c_identificacion" class="required">{{$tipos_identificaciones[$cliente->tipo_identificacion]}}</label>
					<div class="input-group">
						<input type="text" class="form-control text-center" id="c_prefijo_identificacion" value="{{$tipo_identificacion_}}" readonly>
						<input type="text" class="form-control text-uppercase" id="c_identificacion" value="{{$identificacion_}}" style="width: calc(100% - 65px);" readonly>
					</div>
				</div>
				<div id="nm_container" class="form-group col-12 col-lg-6">
					<label for="c_nombre_completo" class="required"><i class="fas fa-address-card"></i> Nombre / Razón social</label>
					<input type="text" class="form-control text-uppercase" id="c_nombre_completo" value="{{$cliente->nombre_completo}}" placeholder="Ingrese el nombre completo" readonly>
				</div>
				<div class="form-group col-6 col-lg-3">
					<label for="c_telefono1" class="required"><i class="fas fa-phone-alt"></i> Teléfono 1</label>
					<div class="input-group">
						<input type="text" class="form-control text-center" id="c_prefijo_telefono1" value="{{$prefijo_telefono1_}}" readonly>
						<input type="text" class="form-control text-uppercase" id="c_telefono1" value="{{$telefono1_}}" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px);" readonly>
					</div>
				</div>
				<div class="form-group col-6 col-lg-3">
					<label for="c_telefono2"><i class="fas fa-phone-alt"></i> Teléfono 2</label>
					<div class="input-group">
						<input type="text" class="form-control text-center" id="c_prefijo_telefono2" value="{{$prefijo_telefono2_}}" readonly>
						<input type="text" class="form-control text-uppercase" name="c_telefono2" id="c_telefono2" value="{{$telefono2_}}" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px);" readonly>
					</div>
				</div>
				<div class="form-group col-12 col-lg-6">
					<label for="c_correo_electronico" class="required"><i class="fas fa-envelope"></i> Correo electrónico</label>
					<input type="email" class="form-control text-uppercase" name="c_correo_electronico" id="c_correo_electronico" value="{{$cliente->direccion}}" placeholder="Ingrese el correo electrónico" readonly>
				</div>
				<div class="form-group col-12 col-lg-6">
					<label for="c_direccion" class="required"><i class="fas fa-map-marked-alt"></i> Dirección</label>
					<textarea class="form-control text-uppercase" name="c_direccion" id="c_direccion" placeholder="Ingrese la dirección" rows="3" readonly>{{$cliente->direccion}}</textarea>
				</div>
				<div class="form-group col-12 col-lg-6">
					<label for="c_referencia"><i class="fas fa-sticky-note"></i> Punto de referencia</label>
					<textarea class="form-control text-uppercase" name="c_referencia" id="c_referencia" placeholder="Ingrese el punto de referencia" rows="3" readonly>{{$cliente->puntoreferencia}}</textarea>
				</div>
			</div>

			<div class="row align-items-center mb-3">
				<div class="col-6 text-start">
					<h5 class="text-uppercase m-0"><i class="fas fa-users"></i> Contactos</h5>
				</div>
				<div class="col-6 text-end">
					<button type="button" class="btn btn-primary btn-sm" id="btn_agregar_usuario"><i class="fas fa-user-plus me-2"></i>Agregar usuario</button>
				</div>
			</div>

			<div class="table-responsive border rounded mb-4">
				<table id="tabla_usuarios" class="table table-hover m-0">
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
					<button type="button" class="btn btn-primary btn-sm " id="btn-new-zone"><i data-feather="plus"></i> Agregar zona</button>
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

			<div class="form-row">
				<div class="form-group col-12">
					<label for="observation">Observación: </label>
					<textarea class="form-control" name="observation" id="observation" placeholder="Ingrese las observaciones (Opcional)" rows="5" style="height: initial;"></textarea>
				</div>
			</div>

			<div class="text-end">
				<button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Guardar</button>
			</div>
		</form>
	</div>
</div>
@endsection