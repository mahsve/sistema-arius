@extends('plantilla')

@section('title', 'Registrar mapa de zona - ' . env('TITLE'))

@section('styles')
<link rel="stylesheet" href="{{url('libraries/tom-select/css/tom-select.min.css')}}">
@endsection

@section('scripts')
<script>
	const tipos_identificaciones = <?= json_encode($tipos_identificaciones) ?>;
	const lista_cedula = <?= json_encode($lista_cedula) ?>;
	const lista_rif = <?= json_encode($lista_rif) ?>;
	const lista_prefijos = <?= json_encode($lista_prefijos) ?>;
	const dispositivos = <?= json_encode($dispositivos) ?>;
</script>
<script src="{{url('libraries/sortable/sortable.min.js')}}"></script>
<script src="{{url('libraries/tom-select/js/tom-select.base.min.js')}}"></script>
<script src="{{url('js/app/mapa_de_zona/registrar.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase m-0"><i class="fas fa-folder-plus"></i> Registrar</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('mapas_de_zonas.index')}}" class="btn btn-primary btn-sm "><i class="fas fa-chevron-left me-2"></i>Regresar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('mapas_de_zonas.store')}}">
			@csrf
			<ul class="nav nav-pills pb-0 mb-3 border-0" id="pills-tab" role="tablist">
				<li class="nav-item" role="presentation"><button type="button" class="nav-link active" id="pills-cliente-tab" data-bs-toggle="pill" data-bs-target="#pills-cliente" role="tab" aria-controls="pills-cliente" aria-selected="true"><i class="fas fa-file-invoice"></i> Principal</button></li>
				<li class="nav-item" role="presentation"><button type="button" class="nav-link" id="pills-usuarios-tab" data-bs-toggle="pill" data-bs-target="#pills-usuarios" role="tab" aria-controls="pills-usuarios" aria-selected="false"><i class="fas fa-address-card"></i> Usuarios</button></li>
				<li class="nav-item" role="presentation"><button type="button" class="nav-link" id="pills-zonas-tab" data-bs-toggle="pill" data-bs-target="#pills-zonas" role="tab" aria-controls="pills-zonas" aria-selected="false"><i class="fas fa-door-open"></i> Zonas</button></li>
				<li class="nav-item" role="presentation"><button type="button" class="nav-link" id="pills-tecnicos-tab" data-bs-toggle="pill" data-bs-target="#pills-tecnicos" role="tab" aria-controls="pills-tecnicos" aria-selected="false"><i class="fas fa-digital-tachograph"></i> Datos técnico</button></li>
				<!-- <li class="nav-item" role="presentation"><button type="button" class="nav-link" id="pills-visitas-tab" data-bs-toggle="pill" data-bs-target="#pills-visitas" role="tab" aria-controls="pills-visitas" aria-selected="false"><i class="fas fa-compass"></i> Visitas</button></li> -->
			</ul>

			<div class="tab-content border rounded pt-3 pb-3" id="pills-tabContent">
				<!-- CLIENTE Y CAMPO -->
				<div class="tab-pane fade show active" id="pills-cliente" role="tabpanel" aria-labelledby="pills-cliente-tab" tabindex="0">
					<!-- CONTRATO -->
					<div class="row">
						<div class="col-12 col-lg-6 text-start">
							<h3 class="text-uppercase my-3"><i class="fas fa-file-invoice"></i> Mapa de zona</h3>
						</div>
						<div class="col-12 col-lg-6 mb-2">
							<div class="form-row justify-content-end">
								<div class="form-group col-4 mb-4">
									<label for="m_ingreso" class="required"><i class="fas fa-calendar-day"></i> Fecha ingreso</label>
									<input type="date" class="form-control text-uppercase" name="m_ingreso" id="m_ingreso">
								</div>
								<div class="form-group col-4 mb-4">
									<label for="m_tipo_contrato" class="required"><i class="fas fa-file-contract"></i> Contrato</label>
									<select class="form-control text-uppercase" name="m_tipo_contrato" id="m_tipo_contrato">
										<option value="">Seleccione</option>
										@foreach ($lista_contratos as $index => $contratos)
										<optgroup label="{{$index}}">
											@foreach ($contratos as $index2 => $canal)
											<option value="{{$index2}}">{{$canal}}</option>
											@endforeach
										</optgroup>
										@endforeach
									</select>
								</div>
								<div class="form-group col-4 mb-4">
									<div class="d-flex align-items-center justify-content-between">
										<label for="m_codigo" class="required"><i class="fas fa-barcode"></i> Código</label>
										<span>
											<input type="checkbox" class="form-check-input mb-1" id="codigo_manual">
											<i class="fas fa-question-circle position-relative" style="font-size: 0.7rem; top: -7px;"></i>
										</span>
									</div>
									<input type="text" class="form-control text-uppercase text-end" name="m_codigo" id="m_codigo" placeholder="AUTOMATICO" readonly>
								</div>
							</div>
						</div>
					</div>

					<!-- TITULOS CLIENTE -->
					<div class="row align-items-center mb-3">
						<div class="col-4 text-start">
							<h4 class="text-uppercase m-0"><i class="fas fa-id-card-alt"></i> Cliente</h4>
						</div>
						<div class="col-8 text-end">
							<button type="button" class="btn btn-primary btn-sm" id="btn_abrir_registrar_cliente"><i class="fas fa-user-plus me-2"></i>Registrar</button>
							<button type="button" class="btn btn-info btn-sm" id="btn_abrir_buscar_cliente"><i class="fas fa-search me-2"></i>Buscar</button>
						</div>
					</div>

					<!-- CAMPOS -->
					<div class="form-row">
						<div class="form-group col-6 col-lg-2">
							<label for="cl_tipo_identificacion" class="required"><i class="fas fa-id-badge"></i> Tipo ID.</label>
							<select class="form-control text-uppercase" id="cl_tipo_identificacion" disabled>
								@foreach($tipos_identificaciones as $index => $tipo_identificacion)
								<option value="{{$index}}">{{$tipo_identificacion}}</option>
								@endforeach
							</select>
						</div>
						<div id="contenedor_identificacion_f" class="form-group col-6 col-lg-3">
							<label for="cl_identificacion" class="required"></label>
							<div class="input-group">
								<select class="form-control text-center" id="cl_prefijo_identificacion" style="height: 31px; margin-top: 1px;" disabled></select>
								<input type="text" class="form-control text-uppercase" id="cl_identificacion" style="width: calc(100% - 65px); height: 33px;" disabled>
							</div>
						</div>
						<div class="form-group col-12 col-lg-6 mb-2">
							<label for="cl_nombre_completo"><i class="fas fa-address-card"></i> Nombre / Razón social</label>
							<input type="text" class="form-control text-uppercase" id="cl_nombre_completo" placeholder="Ingrese el nombre completo" readonly>
						</div>
						<div class="form-group col-6 col-lg-3 mb-2">
							<label for="cl_telefono1"><i class="fas fa-phone-alt"></i> Teléfono 1</label>
							<div class="input-group">
								<input type="text" class="form-control text-center" id="cl_prefijo_telefono1" placeholder="COD." readonly>
								<input type="text" class="form-control text-uppercase" id="cl_telefono1" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px);" readonly>
							</div>
						</div>
						<div class="form-group col-6 col-lg-3 mb-2">
							<label for="cl_telefono2"><i class="fas fa-phone-alt"></i> Teléfono 2</label>
							<div class="input-group">
								<input type="text" class="form-control text-center" id="cl_prefijo_telefono2" placeholder="COD." readonly>
								<input type="text" class="form-control text-uppercase" id="cl_telefono2" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px);" readonly>
							</div>
						</div>
						<div class="form-group col-12 col-lg-6 mb-2">
							<label for="cl_correo_electronico"><i class="fas fa-envelope"></i> Correo electrónico</label>
							<input type="email" class="form-control text-uppercase" id="cl_correo_electronico" placeholder="Ingrese el correo electrónico" readonly>
						</div>
						<input type="hidden" name="id_cliente" id="id_cliente">
					</div>

					<!-- TITULOS DIRECCION -->
					<div class="row align-items-center mt-4 mb-3">
						<div class="col-4 text-start">
							<h4 class="text-uppercase m-0"><i class="fas fa-warehouse"></i> Detalles del lugar</h4>
						</div>
					</div>

					<!-- CAMPOS -->
					<div class="form-row justify-content-end">
						<div class="form-group col-12 col-lg-6">
							<label for="c_direccion" class="required"><i class="fas fa-map-marked-alt"></i> Dirección</label>
							<textarea class="form-control text-uppercase" name="c_direccion" id="c_direccion" placeholder="Ingrese la dirección" rows="3"></textarea>
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="c_referencia"><i class="fas fa-sticky-note"></i> Punto de referencia</label>
							<textarea class="form-control text-uppercase" name="c_referencia" id="c_referencia" placeholder="Ingrese el punto de referencia" rows="3"></textarea>
						</div>
					</div>

					<!-- BOTONES -->
					<div class="d-flex align-items-center justify-content-end">
						<button type="button" class="btn btn-secondary mx-1" id="btn_next_1" data-tab="pills-usuarios-tab">Siguiente<i class="fas fa-chevron-right ms-2"></i></button>
					</div>
				</div>

				<!-- USUARIOS -->
				<div class="tab-pane fade" id="pills-usuarios" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
					<!-- TITULO -->
					<div class="row align-items-center mb-3">
						<div class="col-6 text-start">
							<h4 class="text-uppercase m-0"><i class="fas fa-address-card"></i> Usuarios</h4>
						</div>
						<div class="col-6 text-end">
							<button type="button" class="btn btn-primary btn-sm" id="btn_agregar_usuario"><i class="fas fa-folder-plus me-2"></i>Agregar usuario</button>
						</div>
					</div>

					<!-- LISTADO -->
					<div class="table-responsive border rounded mb-4">
						<table id="tabla_usuarios" class="table table-hover m-0">
							<thead>
								<tr>
									<th class="px-2 text-center" width="40px"><i class="fas fa-arrows-alt"></i></th>
									<th class="px-2 text-center" width="40px"><i class="fas fa-list-ol"></i> N°</th>
									<th class="ps-2"><i class="fas fa-id-badge"></i> Cédula</th>
									<th class="ps-2"><i class="fas fa-address-card"></i> Nombre completo</th>
									<th class="ps-2"><i class="fas fa-unlock"></i> Contraseña</th>
									<th class="ps-2"><i class="fas fa-phone-alt"></i> Teléfono</th>
									<th class="ps-2"><i class="fas fa-sticky-note"></i> Nota</th>
									<th class="px-2 text-center" width="55px"><i class="fas fa-cogs"></i></th>
								</tr>
							</thead>
							<tbody>
								<tr class="sin_usuarios">
									<td colspan="8" class="text-center"><i class="fas fa-times"></i> Sin usuarios registrados</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- BOTONES -->
					<div class="d-flex align-items-center justify-content-end">
						<button type="button" class="btn btn-secondary mx-1" id="btn_prev_2" data-tab="pills-cliente-tab"><i class="fas fa-chevron-left me-2"></i>Anterior</button>
						<button type="button" class="btn btn-secondary mx-1" id="btn_next_2" data-tab="pills-zonas-tab">Siguiente<i class="fas fa-chevron-right ms-2"></i></button>
					</div>
				</div>

				<!-- ZONAS -->
				<div class="tab-pane fade" id="pills-zonas" role="tabpanel" aria-labelledby="pills-zonas-tab" tabindex="0">
					<!-- TITULO -->
					<div class="row align-items-center mb-3">
						<div class="col-6 text-start">
							<h4 class="text-uppercase m-0"><i class="fas fa-door-open"></i> Zonas</h4>
						</div>
						<div class="col-6 text-end">
							<button type="button" class="btn btn-primary btn-sm" id="btn_agregar_zona"><i class="fas fa-folder-plus me-2"></i>Agregar zona</button>
						</div>
					</div>

					<!-- LISTADO -->
					<div class="table-responsive border rounded mb-4">
						<table id="tabla_zonas" class="table table-hover m-0">
							<thead>
								<tr>
									<th class="px-2 text-center" width="40px"><i class="fas fa-arrows-alt"></i></th>
									<th class="px-2 text-center" width="40px"><i class="fas fa-list-ol"></i> N°</th>
									<th class="ps-2"><i class="fas fa-map"></i> zona</th>
									<th class="ps-2"><i class="fas fa-laptop-house"></i> Equipos</th>
									<th class="ps-2"><i class="fas fa-laptop-code"></i> Configuración</th>
									<th class="ps-2"><i class="fas fa-sticky-note"></i> Notas</th>
									<th class="px-2 text-center" width="55px"><i class="fas fa-cogs"></i></th>
								</tr>
							</thead>
							<tbody>
								<tr class="sin_zonas">
									<td colspan="8" class="text-center"><i class="fas fa-times"></i> Sin zonas registradas</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- BOTONES -->
					<div class="d-flex align-items-center justify-content-end">
						<button type="button" class="btn btn-secondary mx-1" id="btn_prev_3" data-tab="pills-usuarios-tab"><i class="fas fa-chevron-left me-2"></i>Anterior</button>
						<button type="button" class="btn btn-secondary mx-1" id="btn_next_3" data-tab="pills-tecnicos-tab">Siguiente<i class="fas fa-chevron-right ms-2"></i></button>
					</div>
				</div>

				<!-- DATOS TÉCNICOS -->
				<div class="tab-pane fade" id="pills-tecnicos" role="tabpanel" aria-labelledby="pills-tecnicos-tab" tabindex="0">
					<!-- TITULO -->
					<div class="row align-items-center mb-3">
						<div class="col-6 text-start">
							<h4 class="text-uppercase my-2"><i class="fas fa-digital-tachograph"></i> Datos técnico</h4>
						</div>
						<div class="col-6 text-end">
							<div class="form-check d-flex justify-content-end align-items-center">
								<input type="checkbox" class="form-check-input" name="omitir_datos_tecnicos" value="S" id="omitir_datos_tecnicos">
								<label for="omitir_datos_tecnicos" class="form-check-label position-relative ms-2" style="top: 2px; cursor: pointer;">Omitir por ahora <i class="fas fa-lock ms-1"></i></label>
							</div>
						</div>
					</div>

					<!-- CAMPOS -->
					<div class="form-row">
						<div class="form-group col-3 mb-3">
							<label for="m_panel_version"><i class="fas fa-file-contract"></i> Panel y versión del sistema</label>
							<input class="form-control text-uppercase" name="m_panel_version" id="m_panel_version" placeholder="Ingrese el panel y la versión del sistema">
						</div>
						<div class="form-group col-3 mb-3">
							<label for="m_modelo"><i class="fas fa-file-contract"></i> Modelo </label>
							<input type="text" class="form-control text-uppercase" name="m_modelo" id="m_modelo">
						</div>
						<div class="form-group col-3 mb-3">
							<label for="m_reporta"><i class="fas fa-file-contract"></i> Reporta por</label>
							<select class="form-control text-uppercase" name="m_reporta" id="m_reporta">
								<option value="">Seleccione</option>
								@foreach ($canales_reportes as $index => $canal)
								<option value="{{$index}}">{{$canal}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-6 col-lg-3 mb-3">
							<label for="c_telefono_assig"><i class="fas fa-phone-alt"></i> N° de telefono asig.</label>
							<div class="input-group">
								<select class="form-control text-center" name="c_prefijo_telefono_asg" id="c_prefijo_telefono_asg" style="height: 31px; margin-top: 1px;">
									<option value="">COD.</option>
									@foreach ($lista_prefijos as $index => $prefijo)
									<optgroup label="{{$index}}">
										@foreach ($prefijo as $codigos)
										<option value="{{$codigos}}">{{$codigos}}</option>
										@endforeach
									</optgroup>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase" name="c_telefono_assig" id="c_telefono_assig" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
							</div>
						</div>

						<div class="form-group col-6 mb-3">
							<label for="m_instaladores"><i class="fas fa-file-contract"></i> Técnico instalador </label>
							<select class="form-control text-uppercase" name="m_instaladores[]" id="m_instaladores" data-placeholder="Seleccione un técnico" multiple>
								@foreach($personal as $persona)
								<option value="{{$persona->cedula}}">{{$persona->nombre}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-3 mb-3">
							<label for="m_instalacion"><i class="fas fa-file-contract"></i> Fecha de instalación </label>
							<input type="date" class="form-control text-uppercase" name="m_instalacion" id="m_instalacion">
						</div>
						<div class="form-group col-3 mb-3">
							<label for="m_entrega"><i class="fas fa-file-contract"></i> Fecha de entrega </label>
							<input type="date" class="form-control text-uppercase" name="m_entrega" id="m_entrega">
						</div>
						<div class="form-group col-3 mb-3">
							<label for="m_asesor"><i class="fas fa-file-contract"></i> Asesor </label>
							<select class="form-control text-uppercase" name="m_asesor" id="m_asesor">
								<option value="">Seleccione</option>
								@foreach($personal as $persona)
								<option value="{{$persona->cedula}}">{{$persona->nombre}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group col-9 mb-3">
							<label for="m_ubicacion_panel"><i class="fas fa-file-contract"></i> Ubicación del panel</label>
							<input type="input" class="form-control text-uppercase" name="m_ubicacion_panel" id="m_ubicacion_panel">
						</div>

						<div class="form-group col-3 mb-3">
							<label for="m_particiones"><i class="fas fa-file-contract"></i> Particiones del sistema</label>
							<input type="input" class="form-control text-uppercase" name="m_particiones" id="m_particiones">
						</div>
						<div class="form-group col-3 mb-3">
							<label for="m_imei"><i class="fas fa-file-contract"></i> imei</label>
							<input type="input" class="form-control text-uppercase" name="m_imei" id="m_imei">
						</div>
						<div class="form-group col-3 mb-3">
							<label for="m_linea_principal"><i class="fas fa-file-contract"></i> Linea principal</label>
							<select class="form-control text-uppercase" name="m_linea_principal" id="m_linea_principal">
								<option value="">Seleccione</option>
								<option value="1">L1</option>
								<option value="2">L2</option>
								<option value="3">L3</option>
								<option value="4">L4</option>
							</select>
						</div>
						<div class="form-group col-3 mb-3">
							<label for="m_linea_respaldo"><i class="fas fa-file-contract"></i> Linea de respaldo</label>
							<select class="form-control text-uppercase" name="m_linea_respaldo" id="m_linea_respaldo">
								<option value="">Seleccione</option>
								<option value="1">L1</option>
								<option value="2">L2</option>
								<option value="3">L3</option>
								<option value="4">L4</option>
							</select>
						</div>

						<div class="form-group col-12">
							<label for="m_observacion">Observación: </label>
							<textarea class="form-control" name="m_observacion" id="m_observacion" placeholder="Ingrese las observaciones (Opcional)" rows="5" style="height: initial;"></textarea>
						</div>
					</div>

					<!-- BOTONES -->
					<div class="d-flex align-items-center justify-content-end">
						<button type="button" class="btn btn-secondary mx-1" id="btn_prev_4" data-tab="pills-zonas-tab"><i class="fas fa-chevron-left me-2"></i>Anterior</button>
						<button type="submit" class="btn btn-primary mx-1" id="btn_save"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</div>

				<!-- VISITAS -->
				<div class="tab-pane fade" id="pills-visitas" role="tabpanel" aria-labelledby="pills-visitas-tab" tabindex="0">
					<!-- TITULO -->
					<div class="row align-items-center mb-3">
						<div class="col-6 text-start">
							<h4 class="text-uppercase m-0"><i class="fas fa-compass"></i> Visitas</h4>
						</div>
						<div class="col-6 text-end">
							<button type="button" class="btn btn-primary btn-sm" id="btn_agregar_zona"><i class="fas fa-folder-plus me-2"></i>Agregar zona</button>
						</div>
					</div>


					<!-- BOTONES -->
					<div class="d-flex align-items-center justify-content-end">
						<button type="button" class="btn btn-secondary mx-1" id="asd"><i class="fas fa-chevron-left me-2"></i>Anterior</button>
						<button type="submit" class="btn btn-primary mx-1" id="asd"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- MODAL PARA REGISTRAR CLIENTE -->
<div class="modal fade" id="modal_registrar_cliente" tabindex="-1" aria-labelledby="modal_rcliente_label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title fs-5" id="modal_rcliente_label"><i class="fas fa-folder-plus"></i> Registrar cliente</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body pt-3 pb-4">
				<form class="forms-sample" name="formulario_registro_cl" id="formulario_registro_cl" method="POST" action="{{route('clientes.store')}}">
					@csrf
					<div class="form-row">
						<div class="form-group col-6 col-lg-2">
							<label for="c_tipo_identificacion" class="required"><i class="fas fa-id-badge"></i> Tipo ID.</label>
							<select class="form-control text-uppercase" name="c_tipo_identificacion" id="c_tipo_identificacion">
								@foreach($tipos_identificaciones as $index => $tipo_identificacion)
								<option value="{{$index}}">{{$tipo_identificacion}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-6 col-lg-4" id="contenedor_identificacion">
							<label for="c_identificacion" class="required"></label>
							<div class="input-group">
								<select class="form-control text-center" name="c_prefijo_identificacion" id="c_prefijo_identificacion" style="height: 31px; margin-top: 1px;"></select>
								<input type="text" class="form-control text-uppercase" name="c_identificacion" id="c_identificacion" style="width: calc(100% - 65px); height: 33px;">
							</div>
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="c_nombre_completo" class="required"><i class="fas fa-address-card"></i> Nombre / Razón social</label>
							<input type="text" class="form-control text-uppercase" name="c_nombre_completo" id="c_nombre_completo" placeholder="Ingrese el nombre completo">
						</div>
						<div class="form-group col-6 col-lg-3">
							<label for="c_telefono1" class="required"><i class="fas fa-phone-alt"></i> Teléfono 1</label>
							<div class="input-group">
								<select class="form-control text-center" name="c_prefijo_telefono1" id="c_prefijo_telefono1" style="height: 31px; margin-top: 1px;">
									<option value="">COD.</option>
									@foreach ($lista_prefijos as $index => $prefijo)
									<optgroup label="{{$index}}">
										@foreach ($prefijo as $codigos)
										<option value="{{$codigos}}">{{$codigos}}</option>
										@endforeach
									</optgroup>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase" name="c_telefono1" id="c_telefono1" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
							</div>
						</div>
						<div class="form-group col-6 col-lg-3">
							<label for="c_telefono2"><i class="fas fa-phone-alt"></i> Teléfono 2</label>
							<div class="input-group">
								<select class="form-control text-center" name="c_prefijo_telefono2" id="c_prefijo_telefono2" style="height: 31px; margin-top: 1px;">
									<option value="">COD.</option>
									@foreach ($lista_prefijos as $index => $prefijo)
									<optgroup label="{{$index}}">
										@foreach ($prefijo as $codigos)
										<option value="{{$codigos}}">{{$codigos}}</option>
										@endforeach
									</optgroup>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase" name="c_telefono2" id="c_telefono2" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
							</div>
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="c_correo_electronico" class="required"><i class="fas fa-envelope"></i> Correo electrónico</label>
							<input type="email" class="form-control text-uppercase" name="c_correo_electronico" id="c_correo_electronico" placeholder="Ingrese el correo electrónico">
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="c_direccion" class="required"><i class="fas fa-map-marked-alt"></i> Dirección</label>
							<textarea class="form-control text-uppercase" name="c_direccion" id="c_direccion" placeholder="Ingrese la dirección" rows="3"></textarea>
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="c_referencia"><i class="fas fa-sticky-note"></i> Punto de referencia</label>
							<textarea class="form-control text-uppercase" name="c_referencia" id="c_referencia" placeholder="Ingrese el punto de referencia" rows="3"></textarea>
						</div>
						<input type="hidden" name="modulo" value="mapa_de_zona">
					</div>

					<div class="text-end">
						<button type="reset" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Limpiar</button>
						<button type="submit" class="btn btn-primary" id="btn_registrar_cliente"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- MODAL PARA BUSCAR CLIENTE -->
<div class="modal fade" id="modal_buscar_cliente" tabindex="-1" aria-labelledby="modal_bcliente_label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title fs-5" id="modal_bcliente_label"><i class="fas fa-search"></i> Buscar cliente</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body pt-3 pb-4">
				<div class="input-group input-group-sm mb-3">
					<input type="text" class="form-control h-100" id="input_buscar_cliente" placeholder="Buscar cliente">
					<button type="button" class="btn btn-primary btn-sm" id="btn_buscar_cliente"><i class="fas fa-search me-2"></i> Buscando</button>
				</div>

				<div class="table-responsive border rounded">
					<table id="tabla_clientes" class="table table-hover m-0">
						<thead>
							<tr>
								<th class="ps-2"><i class="fas fa-id-badge"></i> Identificación</th>
								<th class="ps-2"><i class="fas fa-address-card"></i> Cliente</th>
								<th class="ps-2"><i class="fas fa-phone-alt"></i> Teléfono</th>
								<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
								<th class="px-2 text-center"><i class="fas fa-cogs"></i></th>
							</tr>
						</thead>

						<tbody>
							<!-- Javascript -->
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- MODAL PARA REGISTRAR AÑOS -->
<div class="modal fade" id="modal_registrar_anio" tabindex="-1" aria-labelledby="modal_anio_label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title fs-5" id="modal_anio_label"><i class="fas fa-folder-plus"></i> Registrar año</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body pt-3 pb-4">

			</div>
		</div>
	</div>
</div>
@endsection