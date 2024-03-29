@extends('plantilla')

@section('title', 'Registrar mapa de zona - ' . env('TITLE'))

@section('scripts')
<script>
	const tipos_identificaciones = <?= json_encode($tipos_identificaciones) ?>;
	const lista_cedula = <?= json_encode($lista_cedula) ?>;
	const lista_rif = <?= json_encode($lista_rif) ?>;
	const lista_prefijos = <?= json_encode($lista_prefijos) ?>;
	const dispositivos = <?= json_encode($dispositivos) ?>;
</script>
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
				<li class="nav-item" role="presentation"><button class="nav-link active" id="pills-cliente-tab" data-bs-toggle="pill" data-bs-target="#pills-cliente" type="button" role="tab" aria-controls="pills-cliente" aria-selected="true"><i class="fas fa-file-invoice"></i> Principal</button></li>
				<li class="nav-item" role="presentation"><button class="nav-link" id="pills-usuarios-tab" data-bs-toggle="pill" data-bs-target="#pills-usuarios" type="button" role="tab" aria-controls="pills-usuarios" aria-selected="false"><i class="fas fa-address-book"></i> Usuarios</button></li>
				<li class="nav-item" role="presentation"><button class="nav-link" id="pills-zonas-tab" data-bs-toggle="pill" data-bs-target="#pills-zonas" type="button" role="tab" aria-controls="pills-zonas" aria-selected="false"><i class="fas fa-map-marked-alt"></i> Zonas</button></li>
				<li class="nav-item" role="presentation"><button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false"><i class="fas fa-cogs"></i> Técnico</button></li>
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
									<label for="m_registro" class="required">Registro</label>
									<input type="date" class="form-control text-uppercase" name="m_registro" id="m_registro" placeholder="AUTOMATICO">
								</div>
								<div class="form-group col-4 mb-4">
									<label for="m_tipo_contrato" class="required">Contrato</label>
									<select class="form-control text-uppercase" name="m_tipo_contrato" id="m_tipo_contrato">
										<option value="0">Seleccione</option>
										@foreach($lista_contratos as $index => $contrato)
										<option value="{{$index}}">{{$contrato}}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-4 mb-4">
									<div class="d-flex align-items-center justify-content-between">
										<label for="m_codigo" class="required">Código</label>
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
						<div class="form-group col-6 col-lg-2 mb-2">
							<label for="cl_tipo_identificacion"><i class="fas fa-id-badge"></i> Tipo ID.</label>
							<input type="text" class="form-control text-uppercase" id="cl_tipo_identificacion" placeholder="TIPO ID." readonly>
						</div>
						<div class="form-group col-6 col-lg-3 mb-2">
							<label for="cl_identificacion"><i class="fas fa-id-badge"></i> Número ID.</label>
							<div class="input-group">
								<input type="text" class="form-control text-center" id="cl_prefijo_identificacion" placeholder="V" readonly>
								<input type="text" class="form-control text-uppercase" id="cl_identificacion" placeholder="Número" style="width: calc(100% - 65px);" readonly>
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
						<button type="button" class="btn btn-primary mx-1" id="asd">Siguiente<i class="fas fa-chevron-right ms-2"></i></button>
					</div>
				</div>

				<!-- USUARIOS -->
				<div class="tab-pane fade" id="pills-usuarios" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
					<!-- TITULO -->
					<div class="row align-items-center mb-3">
						<div class="col-6 text-start">
							<h4 class="text-uppercase m-0"><i class="fas fa-address-book"></i> Usuarios</h4>
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
									<td colspan="7" class="text-center"><i class="fas fa-times"></i> Sin usuarios registrados</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- BOTONES -->
					<div class="d-flex align-items-center justify-content-end">
						<button type="button" class="btn btn-primary mx-1" id="asd"><i class="fas fa-chevron-left me-2"></i>Anterior</button>
						<button type="button" class="btn btn-primary mx-1" id="asd">Siguiente<i class="fas fa-chevron-right ms-2"></i></button>
					</div>
				</div>

				<!-- ZONAS -->
				<div class="tab-pane fade" id="pills-zonas" role="tabpanel" aria-labelledby="pills-zonas-tab" tabindex="0">
					<!-- TITULO -->
					<div class="row align-items-center mb-3">
						<div class="col-6 text-start">
							<h4 class="text-uppercase m-0"><i class="fas fa-map-marked-alt"></i> Zonas</h4>
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
									<td colspan="7" class="text-center"><i class="fas fa-times"></i> Sin zonas registradas</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- BOTONES -->
					<div class="d-flex align-items-center justify-content-end">
						<button type="button" class="btn btn-primary mx-1" id="asd"><i class="fas fa-chevron-left me-2"></i>Anterior</button>
						<button type="button" class="btn btn-primary mx-1" id="asd">Siguiente<i class="fas fa-chevron-right ms-2"></i></button>
					</div>
				</div>

				<!-- OTROS -->
				<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
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

					<!-- BOTONES -->
					<div class="d-flex align-items-center justify-content-end">
						<button type="button" class="btn btn-primary mx-1" id="asd"><i class="fas fa-chevron-left me-2"></i>Anterior</button>
						<button type="button" class="btn btn-primary mx-1" id="asd"><i class="fas fa-save ms-2"></i>Guardar</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

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
@endsection