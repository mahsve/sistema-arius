@extends('plantilla')

@section('title', 'Modificar mapa de zona - ' . env('TITLE'))

<?php
// Definimos que lista del tipo de Identificaciones se mostrara en el campo [Cédulas(V-E)|RIF(V-J-G-E)].
$lista_tpid = $lista_cedula;
if ($cliente->tipo_identificacion == "R") $lista_id = $lista_rif;

// Definimos que tipo de contrato tiene el cliente.
$tipocontrato = "";
if ($mapa->tipocontrato == 1 or $mapa->tipocontrato == 5) {
	$tipocontrato = "Comercios";
} else if ($mapa->tipocontrato == 2 or $mapa->tipocontrato == 6) {
	$tipocontrato = "Residencias";
} else if ($mapa->tipocontrato == 3 or $mapa->tipocontrato == 7) {
	$tipocontrato = "Oficinas";
} else if ($mapa->tipocontrato == 4 or $mapa->tipocontrato == 8) {
	$tipocontrato = "Industrias";
}
?>

@section('scripts')
<script id="contenedor_script_variables_2">
	const tipos_identificaciones = <?= json_encode($tipos_identificaciones) ?>;
	const lista_cedula = <?= json_encode($lista_cedula) ?>;
	const lista_rif = <?= json_encode($lista_rif) ?>;
	const lista_prefijos = <?= json_encode($lista_prefijos) ?>;
	const lista_tecnicos = <?= json_encode($personal) ?>;
	var dispositivos = <?= json_encode($dispositivos) ?>;
	document.getElementById('contenedor_script_variables_2').remove();
</script>
<script src="{{url('libraries/sortable/sortable.min.js')}}"></script>
<script src="{{url('js/app/mapa_de_zona/modificar.js')}}"></script>
<script src="{{url('js/app/mapa_de_zona/registrar_auxiliares.js')}}"></script>

@foreach($contactos as $index => $contacto)
<script class="script_auxiliar">
	document.getElementById('btn_agregar_usuario').dispatchEvent(new Event('click')); // Ejecutamos el evento click del botón agregar nuevo usuario.

	// Capturamos la cedula y la dividimos para agregarla a los campos correspondientes.
	var cedula_contacto = "{{$contacto->idcliente}}";
	var prefijoid = cedula_contacto.substring(0, 1);
	var cedula = cedula_contacto.substring(2, 10);
	// Capturamos el teléfono y lo dividimos para agregarlo a los campos correspondientes.
	var telefono_contacto = "{{$contacto->telefono1}}";
	var prefijotl = telefono_contacto.substring(1, 4);
	var telefono = telefono_contacto.substring(6, 14);

	// Capturamos el ID rand para acceder a los id de cada td y empezar a rellenar los datos.
	var tc = document.querySelectorAll('#tabla_usuarios tbody tr').length; // Capturamos el total de tr en la tabla.
	var idrand = document.querySelectorAll('#tabla_usuarios tbody tr')[tc - 1].getAttribute('data-rand');
	document.getElementById(`idcontacto_${idrand}`).value = "{{$contacto->idcontacto}}";
	document.getElementById(`usuario_rcedula_${idrand}`).value = "{{$contacto->idcliente}}";
	document.getElementById(`usuario_prefijoid_${idrand}`).value = prefijoid;
	document.getElementById(`usuario_prefijoid_${idrand}`).setAttribute('disabled', true);
	document.getElementById(`usuario_cedula_${idrand}`).value = cedula;
	document.getElementById(`usuario_cedula_${idrand}`).setAttribute('disabled', true);
	document.getElementById(`usuario_nombre_${idrand}`).value = "{{$contacto->nombre}}";
	document.getElementById(`usuario_nombre_${idrand}`).setAttribute('disabled', true);
	document.getElementById(`usuario_contrasena_${idrand}`).value = "{{$contacto->contrasena}}";
	document.getElementById(`usuario_prefijotl_${idrand}`).value = prefijotl;
	document.getElementById(`usuario_prefijotl_${idrand}`).setAttribute('disabled', true);
	document.getElementById(`usuarios_telefono_${idrand}`).value = telefono;
	document.getElementById(`usuarios_telefono_${idrand}`).setAttribute('disabled', true);
	document.getElementById(`usuarios_nota_${idrand}`).value = "{{$contacto->nota}}";

	// Eliminamos estas variables para evitar filtrar información.
	cedula_contacto = null;
	prefijoid = null;
	cedula = null;
	telefono_contacto = null;
	prefijotl = null;
	telefono = null;
	tc = null;
	idrand = null;

	// Eliminamos toda esta etiqueca con el código Javascript.
	document.querySelector('.script_auxiliar').remove();
</script>
@endforeach

@foreach($zonas as $index => $zona)
<script class="script_auxiliar">
	document.getElementById('btn_agregar_zona').dispatchEvent(new Event('click')); // Ejecutamos el evento click del botón agregar nueva zona.

	// Capturamos el ID rand para acceder a los id de cada td y empezar a rellenar los datos.
	var tc = document.querySelectorAll('#tabla_zonas tbody tr').length; // Capturamos el total de tr en la tabla.
	var idrand = document.querySelectorAll('#tabla_zonas tbody tr')[tc - 1].getAttribute('data-rand');
	var datacf = <?= $zona->configuraciones ? json_encode($zona->configuraciones) : 0 ?>; // Cargamos todos los datos.
	document.getElementById(`idzona_${idrand}`).value = "{{$zona->idzona}}";
	document.getElementById(`zona_descripcion_${idrand}`).value = "{{$zona->zona}}";
	document.getElementById(`zona_equipos_${idrand}`).value = "{{$zona->iddispositivo}}";
	document.getElementById(`zona_configuracion_${idrand}`).innerHTML = `<option value="0">SELC. CONFIGURACIÓN</option>${datacf.map(cf => `<option value="${cf.idconfiguracion}">${cf.configuracion}</option>`).join('')}`;
	document.getElementById(`zona_configuracion_${idrand}`).value = "{{$zona->idconfiguracion}}";
	document.getElementById(`zona_nota_${idrand}`).value = "{{$zona->nota}}";

	// Eliminamos estas variables para evitar filtrar información.
	tc = null;
	idrand = null;
	datacf = null;

	// Eliminamos toda esta etiqueca con el código Javascript.
	document.querySelector('.script_auxiliar').remove();
</script>
@endforeach

@foreach($insts as $index => $inst)
<script class="script_auxiliar">
	document.getElementById('btn_auxiliar_instaladores').dispatchEvent(new Event('click')); // Ejecutamos el evento click del botón agregar nuevo instalador.

	// Capturamos el ID rand para acceder a los id de cada td y empezar a rellenar los datos.
	var tc = document.querySelectorAll('#tabla_tecnicos tbody tr').length; // Capturamos el total de tr en la tabla.
	var idrand = document.querySelectorAll('#tabla_tecnicos tbody tr')[tc - 1].getAttribute('data-rand');
	document.getElementById(`idinstalador_${idrand}`).value = "{{$inst->iddetalle}}";
	document.getElementById(`cedula_instalador_${idrand}`).value = "{{$inst->cedula}}";
	document.getElementById(`nombre_tecnico_${idrand}`).innerText = "{{$inst->nombre}}";
	document.getElementById(`cedula_tecnico_${idrand}`).innerText = "{{$inst->cedula}}";
	document.getElementById(`telefono_tecnico_${idrand}`).innerText = "{{$inst->telefono1}}";

	// Eliminamos estas variables para evitar filtrar información.
	tc = null;
	idrand = null;

	// Eliminamos toda esta etiqueca con el código Javascript.
	document.querySelector('.script_auxiliar').remove();
</script>
@endforeach
<script class="script_auxiliar2">
	document.getElementById('btn_auxiliar_instaladores').remove();
	document.querySelector('.script_auxiliar2').remove();
</script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-folder-open"></i> Modificar</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('mapas_de_zonas.index')}}" class="btn btn-primary btn-sm"><i class="fas fa-chevron-left me-2"></i>Regresar</a>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('mapas_de_zonas.update', ['id' => $mapa->idcodigo])}}">
			@csrf
			@method('PATCH')
			<ul class="nav nav-pills pb-0 mb-2 border-0" id="pills-tab" role="tablist">
				<li class="nav-item mb-2" role="presentation"><button type="button" data-vista="0" class="nav-link tab_mapa active" id="pills-cliente-tab" data-bs-toggle="pill" data-bs-target="#pills-cliente" role="tab" aria-controls="pills-cliente" aria-selected="true"><i class="fas fa-file-invoice"></i> Principal</button></li>
				<li class="nav-item mb-2" role="presentation"><button type="button" data-vista="1" class="nav-link tab_mapa" id="pills-usuarios-tab" data-bs-toggle="pill" data-bs-target="#pills-usuarios" role="tab" aria-controls="pills-usuarios" aria-selected="false"><i class="fas fa-address-card"></i> Contactos</button></li>
				<li class="nav-item mb-2" role="presentation"><button type="button" data-vista="2" class="nav-link tab_mapa" id="pills-zonas-tab" data-bs-toggle="pill" data-bs-target="#pills-zonas" role="tab" aria-controls="pills-zonas" aria-selected="false"><i class="fas fa-door-open"></i> Zonas</button></li>
				<li class="nav-item mb-2" role="presentation"><button type="button" data-vista="3" class="nav-link tab_mapa" id="pills-tecnicos-tab" data-bs-toggle="pill" data-bs-target="#pills-tecnicos" role="tab" aria-controls="pills-tecnicos" aria-selected="false"><i class="fas fa-digital-tachograph"></i> Datos técnico</button></li>
				<li class="nav-item mb-2" role="presentation"><button type="button" data-vista="4" class="nav-link tab_mapa" id="pills-visitas-tab" data-bs-toggle="pill" data-bs-target="#pills-visitas" role="tab" aria-controls="pills-visitas" aria-selected="false"><i class="fas fa-map-marked-alt"></i> Visitas</button></li>
			</ul>

			<div class="tab-content border rounded pt-3 pb-0" id="pills-tabContent">
				<!-- CLIENTE Y CAMPO -->
				<div class="tab-pane fade show active" id="pills-cliente" role="tabpanel" aria-labelledby="pills-cliente-tab" tabindex="0">
					<!-- CONTRATO -->
					<div class="form-row mb-4">
						<div class="col-12 col-md-5 col-lg-4 col-xl-5 text-start">
							<h3 class="text-uppercase my-3"><i class="fas fa-file-invoice"></i> Mapa de zona</h3>
						</div>
						<div class="col-12 col-md-7 col-lg-8 col-xl-7">
							<div class="form-row">
								<div class="form-group col-6 col-md-6 mb-3">
									<label for="m_ingreso"><i class="fas fa-calendar-day"></i> Fecha ingreso</label>
									<span class="d-block">{{date('d/m/Y', strtotime($mapa->registro))}}</span>
								</div>
								<div class="form-group col-6 col-md-6 mb-3">
									<label for="m_asesor"><i class="fas fa-file-contract"></i> Asesor </label>
									<span class="d-block">{{$mapa->asesor}}</span>
								</div>
								<div class="form-group col-6 col-md-6 mb-3">
									<label for="m_tipo_contrato"><i class="fas fa-file-contract"></i> Contrato</label>
									<span class="d-block">{{$lista_contratos[$tipocontrato][$mapa->tipocontrato]}}</span>
								</div>
								<div class="form-group col-6 col-md-6 mb-3">
									<label for="m_codigo"><i class="fas fa-barcode"></i> Código</label>
									<span class="d-block">{{$mapa->idcodigo}}</span>
								</div>
							</div>
						</div>
					</div>

					<!-- TITULOS CLIENTE -->
					<div class="row align-items-center mb-2">
						<div class="col-12 col-md-7 col-lg-8 text-start">
							<h4 class="text-uppercase my-2"><i class="fas fa-id-card-alt"></i> Cliente</h4>
						</div>
					</div>

					<!-- CAMPOS -->
					<div class="form-row mb-4">
						<div class="form-group col-6 col-md-3 col-lg-2 mb-3">
							<label for="cl_tipo_identificacion" class="required"><i class="fas fa-id-badge"></i> Tipo ID.</label>
							<select class="form-control text-uppercase" id="cl_tipo_identificacion" disabled>
								@foreach($tipos_identificaciones as $index => $tipo_identificacion)
								<option value="{{$index}}" <?= $index == $cliente->tipo_identificacion ? "selected" : "" ?>>{{$tipo_identificacion}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-6 col-md-3 col-lg-3 mb-3" id="contenedor_identificacion_f">
							<label for="cl_identificacion" class="required">{{$tipos_identificaciones[$cliente->tipo_identificacion]}}</label>
							<div class="input-group">
								<select class="form-control text-center" id="cl_prefijo_identificacion" style="height: 31px; margin-top: 1px;" disabled>
									@foreach($lista_tpid as $tpid)
									<option value="{{$tpid}}" <?= $tpid == $cliente->ti ? "selected" : "" ?>>{{$tpid}}</option>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase" id="cl_identificacion" value="{{$cliente->id}}" style="width: calc(100% - 65px); height: 33px;" disabled>
							</div>
						</div>
						<div class="form-group col-12 col-md-6 col-lg-7 mb-3">
							<label for="cl_nombre_completo"><i class="fas fa-address-card"></i> Nombre / Razón social</label>
							<input type="text" class="form-control text-uppercase" id="cl_nombre_completo" value="{{$cliente->nombre}}" placeholder="Nombre / Razón social" readonly>
						</div>
						<div class="form-group col-12 col-md-3 col-lg-3 mb-3">
							<label for="cl_telefono2"><i class="fas fa-phone-alt"></i> Teléfono 2</label>
							<div class="input-group">
								<select class="form-control text-center" id="cl_prefijo_telefono1" style="height:31px; margin-top: 1px;" disabled>
									<option value="">COD.</option>
									@foreach ($lista_prefijos as $index => $prefijo)
									<optgroup label="{{$index}}">
										@foreach ($prefijo as $codigos)
										<option value="{{$codigos}}" <?= $cliente->pt1 == $codigos ? "selected" : "" ?>>{{$codigos}}</option>
										@endforeach
									</optgroup>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase" id="cl_telefono1" value="{{$cliente->tl1}}" placeholder="Teléfono" style="width: calc(100% - 100px); height: 33px;" readonly>
							</div>
						</div>
						<div class="form-group col-12 col-md-3 col-lg-3 mb-3">
							<label for="cl_telefono2"><i class="fas fa-phone-alt"></i> Teléfono 2</label>
							<div class="input-group">
								<select class="form-control text-center" id="cl_prefijo_telefono2" style="height:31px; margin-top: 1px;" disabled>
									<option value="">COD.</option>
									@foreach ($lista_prefijos as $index => $prefijo)
									<optgroup label="{{$index}}">
										@foreach ($prefijo as $codigos)
										<option value="{{$codigos}}" <?= $cliente->pt2 == $codigos ? "selected" : "" ?>>{{$codigos}}</option>
										@endforeach
									</optgroup>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase" id="cl_telefono2" value="{{$cliente->tl2}}" placeholder="Teléfono" style="width: calc(100% - 100px); height: 33px;" readonly>
							</div>
						</div>
						<div class="form-group col-12 col-md-6 col-lg-6 mb-3">
							<label for="cl_correo_electronico"><i class="fas fa-envelope"></i> Correo electrónico</label>
							<input type="email" class="form-control text-uppercase" id="cl_correo_electronico" value="{{$cliente->correo}}" placeholder="Ingrese el correo electrónico" readonly>
						</div>
						<input type="hidden" name="id_cliente" id="id_cliente">
					</div>

					<!-- TITULOS DIRECCION -->
					<div class="row align-items-center pt-2 mb-3">
						<div class="col-12 text-start">
							<h4 class="text-uppercase m-0"><i class="fas fa-warehouse"></i> Detalles del inmueble</h4>
						</div>
					</div>

					<!-- CAMPOS -->
					<div class="form-row justify-content-end">
						<div class="form-group col-12 col-md-6">
							<label for="c_direccion" class="required"><i class="fas fa-map-marked-alt"></i> Dirección</label>
							<textarea class="form-control text-uppercase" name="c_direccion" id="c_direccion" placeholder="Ingrese la dirección" rows="3">{{$mapa->direccion}}</textarea>
						</div>
						<div class="form-group col-12 col-md-6">
							<label for="c_referencia"><i class="fas fa-sticky-note"></i> Punto de referencia</label>
							<textarea class="form-control text-uppercase" name="c_referencia" id="c_referencia" placeholder="Ingrese el punto de referencia" rows="3">{{$mapa->referencia}}</textarea>
						</div>
					</div>
				</div>

				<!-- USUARIOS -->
				<div class="tab-pane fade" id="pills-usuarios" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
					<!-- TITULO -->
					<div class="row align-items-center mb-3">
						<div class="col-12 col-md-6 text-start">
							<h4 class="text-uppercase mb-3 m-md-0"><i class="fas fa-address-card"></i> Contactos</h4>
						</div>
						<div class="col-12 col-md-6 text-end">
							<div class="form-row justify-content-end">
								<div class="col-12 col-md-6">
									<button type="button" class="btn btn-primary btn-sm w-100" id="btn_agregar_usuario"><i class="fas fa-address-book me-2"></i>Agregar contacto</button>
								</div>
							</div>
						</div>
					</div>

					<!-- LISTADO -->
					<div class="table-responsive border rounded mb-4">
						<table id="tabla_usuarios" class="table table-hover m-0">
							<thead>
								<tr>
									<th class="px-2 text-center" width="40px"><i class="fas fa-arrows-alt"></i></th>
									<th class="px-2 text-center" width="40px"><i class="fas fa-list-ol"></i> N°</th>
									<th class="ps-2"><i class="fas fa-id-badge"></i> <span class="required">Cédula</span></th>
									<th class="ps-2"><i class="fas fa-address-card"></i> <span class="required">Nombre completo</span></th>
									<th class="ps-2"><i class="fas fa-unlock"></i> Contraseña</th>
									<th class="ps-2"><i class="fas fa-phone-alt"></i> <span class="required">Teléfono</span></th>
									<th class="ps-2"><i class="fas fa-sticky-note"></i> Nota</th>
									<th class="px-2 text-center" width="55px"><i class="fas fa-cogs"></i></th>
								</tr>
							</thead>
							<tbody>
								<tr class="sin_usuarios">
									<td colspan="8" class="text-center"><i class="fas fa-times"></i> Sin contactos agregados</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- ZONAS -->
				<div class="tab-pane fade" id="pills-zonas" role="tabpanel" aria-labelledby="pills-zonas-tab" tabindex="0">
					<!-- TITULO -->
					<div class="row align-items-center mb-3">
						<div class="col-12 col-md-6 text-start">
							<h4 class="text-uppercase mb-3 m-md-0"><i class="fas fa-door-open"></i> Zonas</h4>
						</div>
						<div class="col-12 col-md-6 text-end">
							<div class="form-row justify-content-end">
								<div class="col-12 col-md-6">
									<button type="button" class="btn btn-primary btn-sm w-100" id="btn_agregar_zona"><i class="fas fa-map-marker-alt me-2"></i>Agregar zona</button>
								</div>
							</div>
						</div>
					</div>

					<!-- LISTADO -->
					<div class="table-responsive border rounded mb-4">
						<table id="tabla_zonas" class="table table-hover m-0">
							<thead>
								<tr>
									<th class="px-2 text-center" width="40px"><i class="fas fa-arrows-alt"></i></th>
									<th class="px-2 text-center" width="40px"><i class="fas fa-list-ol"></i> N°</th>
									<th class="ps-2"><i class="fas fa-map"></i> <span class="required">zona</span></th>
									<th class="ps-2">
										<div class="d-flex align-items-center">
											<span><i class="fas fa-laptop-house"></i> <span class="required">Equipos</span></span>
											@if (isset($crear_dispositivo))
											<button type="button" class="btn btn-sm btn-primary btn-auxilar ms-2" id="btn_modal_equipos"><i class="fas fa-plus"></i></button>
											@endif
										</div>
									</th>
									<th class="ps-2"><i class="fas fa-laptop-code"></i> <span class="required">Configuración</span></th>
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
				</div>

				<!-- DATOS TÉCNICOS -->
				<div class="tab-pane fade" id="pills-tecnicos" role="tabpanel" aria-labelledby="pills-tecnicos-tab" tabindex="0">
					<!-- TITULO -->
					<div class="row align-items-center mb-3">
						<div class="col-12 col-md-6 text-start">
							<h4 class="text-uppercase my-2"><i class="fas fa-digital-tachograph"></i> Datos técnico</h4>
						</div>
						<div class="col-12 col-md-6 text-start text-md-end">
							<div class="form-row justify-content-end">
								<div class="col-12 col-6">
									<div class="form-check d-flex align-items-center">
										<input type="checkbox" class="form-check-input m-0 ms-md-auto" name="omitir_datos_tecnicos" value="S" id="omitir_datos_tecnicos">
										<label for="omitir_datos_tecnicos" class="form-check-label position-relative ms-2" style="top: 2px; cursor: pointer;">Omitir por ahora <i class="fas fa-lock ms-1"></i></label>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- CAMPOS -->
					<div class="form-row">
						<div class="form-group col-12 col-md-3 mb-3">
							<label for="m_panel_version" class="d-block text-truncate required"><i class="fas fa-digital-tachograph"></i> Panel y versión</label>
							<input type="text" class="form-control text-uppercase form-tecnicos" name="m_panel_version" id="m_panel_version" value="{{$mapa->panel_version}}" placeholder="Ingrese el panel y la versión">
						</div>
						<div class="form-group col-12 col-md-3 mb-3">
							<div class="row align-items-center">
								<div class="col">
									<label for="m_teclado" class="d-block text-truncate required"><i class="fas fa-keyboard"></i> Modelo teclado</label>
								</div>
								@if (isset($crear_dispositivo))
								<div class="col text-end">
									<button type="button" class="btn btn-sm btn-primary btn-auxilar ms-auto form-tecnicos" id="btn_modal_teclados"><i class="fas fa-plus"></i></button>
								</div>
								@endif
							</div>
							<select class="form-control text-uppercase form-tecnicos" name="m_teclado" id="m_teclado">
								<option value="">Seleccione un modelo</option>
								@foreach ($dispositivos as $dispositivo)
								<?php if ($dispositivo->tipo == "T") { ?>
									<option value="{{$dispositivo->iddispositivo}}" <?= $mapa->idteclado == $dispositivo->iddispositivo ? "selected" : "" ?>>{{$dispositivo->dispositivo}}</option>
								<?php } ?>
								@endforeach
							</select>
						</div>
						<div class="form-group col-12 col-md-3 mb-3">
							<label for="m_reporta" class="required"><i class="fas fa-satellite-dish"></i> Reporta por</label>
							<select class="form-control text-uppercase form-tecnicos" name="m_reporta" id="m_reporta">
								<option value="">Seleccione</option>
								@foreach ($canales_reportes as $index => $canal)
								<option value="{{$index}}" <?= $mapa->reporta_por == $index ? "selected" : "" ?>>{{$canal}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-12 col-md-3 col-lg-3 mb-3" id="contenedor_telefono_asig" style="display: none;">
							<label for="c_telefono_assig" class="required"><i class="fas fa-phone-alt"></i> N° de telefono asig.</label>
							<div class="input-group">
								<select class="form-control text-center form-tecnicos" name="c_prefijo_telefono_asg" id="c_prefijo_telefono_asg" style="height: 31px; margin-top: 1px;">
									<option value="">COD.</option>
									@foreach ($lista_prefijos as $index => $prefijo)
									<optgroup label="{{$index}}">
										@foreach ($prefijo as $codigos)
										<option value="{{$codigos}}" <?= $mapa->pt == $codigos ? "selected" : "" ?>>{{$codigos}}</option>
										@endforeach
									</optgroup>
									@endforeach
								</select>
								<input type="text" class="form-control text-uppercase form-tecnicos" name="c_telefono_assig" id="c_telefono_assig" value="{{$mapa->tl}}" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
							</div>
						</div>
						<div class="form-group col-6 col-md-3 mb-3">
							<label for="m_instalacion" class="d-block text-truncate required"><i class="fas fa-calendar-day"></i> Fecha de instalación </label>
							<input type="date" class="form-control text-uppercase form-tecnicos" name="m_instalacion" id="m_instalacion" value="{{$mapa->fecha_instalacion}}">
						</div>
						<div class="form-group col-6 col-md-3 mb-3">
							<label for="m_entrega" class="d-block text-truncate required"><i class="fas fa-calendar-day"></i> Fecha de entrega </label>
							<input type="date" class="form-control text-uppercase form-tecnicos" name="m_entrega" id="m_entrega" value="{{$mapa->fecha_entrega}}">
						</div>
						<div class="form-group col-12 col-md-6 mb-3">
							<label for="m_ubicacion_panel" class="required"><i class="fas fa-map-marker-alt"></i> Ubicación del panel</label>
							<input type="text" class="form-control text-uppercase form-tecnicos" name="m_ubicacion_panel" id="m_ubicacion_panel" value="{{$mapa->ubicacion_panel}}" placeholder="Ingrese la ubicación del panel">
						</div>
						<div class="form-group col-6 col-md-3 mb-3">
							<label for="m_particiones" class="d-block text-truncate"><i class="fas fa-project-diagram"></i> Particiones del sistema</label>
							<input type="text" class="form-control text-uppercase form-tecnicos" name="m_particiones" id="m_particiones" value="{{$mapa->particiones_sistema}}" placeholder="Ingrese las particiones del sistema">
						</div>

						<div class="form-group col-6 col-md-3 mb-3">
							<label for="m_imei"><i class="fas fa-microchip"></i> imei</label>
							<input type="text" class="form-control text-uppercase form-tecnicos" name="m_imei" id="m_imei" value="{{$mapa->imei}}" placeholder="Ingrese el IMEI" maxlength="15">
						</div>
						<div class="form-group col-6 col-md-3 mb-3">
							<label for="m_linea_principal" class="d-block text-truncate"><i class="fas fa-sim-card"></i> Linea principal</label>
							<select class="form-control text-uppercase form-tecnicos" name="m_linea_principal" id="m_linea_principal">
								<option value="">Seleccione</option>
								@foreach ($lineas as $index => $linea)
								<option value="{{$index}}" <?= $mapa->linea_principal == $index ? "selected" : "" ?>>{{$linea}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-6 col-md-3 mb-3">
							<label for="m_linea_respaldo" class="d-block text-truncate"><i class="fas fa-sim-card"></i> Linea de respaldo</label>
							<select class="form-control text-uppercase form-tecnicos" name="m_linea_respaldo" id="m_linea_respaldo">
								<option value="">Seleccione</option>
								@foreach ($lineas as $index => $linea)
								<option value="{{$index}}" <?= $mapa->linea_respaldo == $index ? "selected" : "" ?>>{{$linea}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-12 col-lg-3 mb-3">
							<label for="m_monitoreo" class="required"><i class="fas fa-desktop"></i> Monitoreo</label>
							<select class="form-control text-uppercase" name="m_monitoreo" id="m_monitoreo">
								<option value="N" <?= $mapa->monitoreo_contratado == "N" ? "selected" : "" ?>>No contratado</option>
								<option value="S" <?= $mapa->monitoreo_contratado == "S" ? "selected" : "" ?>>Contratado</option>
							</select>
						</div>
					</div>

					<!-- TITULO -->
					<div class="row align-items-center mt-2 mb-3">
						<div class="col-12 col-md-6 text-start">
							<h4 class="text-uppercase mb-3 m-md-0"><i class="fas fa-user-tie"></i> Técnicos instaladores</h4>
						</div>
						<div class="col-12 col-md-6 text-end">
							<div class="form-row justify-content-end">
								<div class="col-12 col-md-6">
									<button type="button" class="btn btn-primary btn-sm w-100 form-tecnicos" id="btn_agregar_instalador"><i class="fas fa-folder-plus me-2"></i>Agregar técnico</button>
									<button type="button" class="d-none" id="btn_auxiliar_instaladores"></button>
								</div>
							</div>
						</div>
					</div>

					<!-- LISTADO -->
					<div class="table-responsive border rounded mb-4">
						<table id="tabla_tecnicos" class="table table-hover m-0">
							<thead>
								<tr>
									<th class="px-2 text-center" width="40px"><i class="fas fa-list-ol"></i> N°</th>
									<th class="ps-2"><i class="fas fa-id-badge"></i> Cédula</th>
									<th class="ps-2"><i class="fas fa-address-card"></i> Nombre completo</th>
									<th class="ps-2"><i class="fas fa-phone-alt"></i> Teléfono</th>
									<th class="px-2 text-center" width="55px"><i class="fas fa-cogs"></i></th>
								</tr>
							</thead>
							<tbody>
								<tr class="sin_tecnicos">
									<td colspan="5" class="text-center"><i class="fas fa-times"></i> Sin usuarios registrados</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- VISITAS -->
				<div class="tab-pane fade" id="pills-visitas" role="tabpanel" aria-labelledby="pills-visitas-tab" tabindex="0">
					<!-- TITULO -->
					<div class="row align-items-center mb-3">
						<div class="col-6 text-start">
							<h4 class="text-uppercase m-0"><i class="fas fa-map-marked-alt"></i> Visitas</h4>
						</div>
						<div class="col-6 text-end">
							<button type="button" class="btn btn-primary btn-sm" id="btn_abrir_modal_anio"><i class="fas fa-calendar-week me-2"></i>Agregar nuevo</button>
						</div>
					</div>

					<div id="contenedor_visitas">
						<div class="d-flex justify-content-center align-items-center py-5 px-3 border rounded mb-3 sin_anios">
							<h4 class="m-0"><i class="fas fa-map-marked-alt"></i> Sin visitas agregadas</h4>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group mt-3">
				<label for="m_observacion">Observación: </label>
				<textarea class="form-control" name="m_observacion" id="m_observacion" placeholder="Ingrese las observaciones (Opcional)" rows="5" style="height: initial;">{{$mapa->observaciones}}</textarea>
			</div>

			<!-- BOTONES -->
			<div class="d-flex align-items-center justify-content-end">
				<button type="button" class="btn btn-secondary mx-1" id="btn_prev" style="display: none;"><i class="fas fa-chevron-left me-2"></i>Anterior</button>
				<button type="button" class="btn btn-secondary mx-1" id="btn_next">Siguiente<i class="fas fa-chevron-right ms-2"></i></button>
				<button type="submit" class="btn btn-primary mx-1" id="btn_save"><i class="fas fa-save me-2"></i>Guardar</button>
			</div>
		</form>
	</div>
</div>

<!-- MODAL INSTALADORES -->
<div class="modal fade" id="modal_instaladores" tabindex="-1" aria-labelledby="modal_instaladores_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_instaladores_label"><i class="fas fa-user-tie"></i> Seleccione el instalador</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<div class="form-group mb-3">
					<label for="lista_tecnicos" class="required"><i class="fas fa-user-tie"></i> Técnico</label>
					<select class="form-control text-uppercase" name="lista_tecnicos" id="lista_tecnicos">
						<option value="">Seleccione el técnico</option>
						@foreach($personal as $index => $tecnico)
						<option value="{{$index}}">{{$tecnico->cedula}} - {{$tecnico->nombre}}</option>
						@endforeach
					</select>
				</div>
				<div class="text-end">
					<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
					<button type="submit" class="btn btn-primary btn-sm" id="btn_agregar_tecnico"><i class="fas fa-plus me-2"></i>Agregar</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- MODAL AÑO VISITAS -->
<div class="modal fade" id="modal_visita1" tabindex="-1" aria-labelledby="modal_visita1_label" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_visita1_label"><i class="fas fa-calendar-week"></i> Seleccione el año</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<div class="form-group mb-3">
					<label for="lista_anios" class="required"><i class="fas fa-calendar-week"></i> Año</label>
					<select class="form-control text-uppercase" name="lista_anios" id="lista_anios">
						@for($var = date('Y'); $var > 2000; $var--)
						<option value="{{$var}}">{{$var}}</option>
						@endfor
					</select>
				</div>
				<div class="text-end">
					<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
					<button type="submit" class="btn btn-primary btn-sm" id="btn_agregar_anio"><i class="fas fa-calendar-plus me-2"></i>Agregar</button>
				</div>
			</div>
		</div>
	</div>
</div>

@if (isset($crear_dispositivo))
<div class="modal fade" id="modal_registrar_disp" tabindex="-1" aria-labelledby="modal_registrar_disp_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_disp_label"><i class="fas fa-paste"></i> Registro rápido</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro_disp" id="formulario_registro_disp" method="POST" action="{{route('dispositivos.store')}}">
					@csrf
					<div class="form-group" style="display: none;">
						<label for="c_tipo_aux" class="required"><i class="fas fa-laptop-house"></i> Tipo de dispositivo</label>
						<select class="form-control text-uppercase" name="c_tipo" id="c_tipo_aux">
							<option value="">Seleccione una opción</option>
							@foreach ($tipos_dispositivos as $index => $td)
							<option value="{{$index}}">{{$td}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="c_dispositivo_aux" class="required"><i class="fas fa-video"></i> <span id="text_label_devices">Dispositivo</span></label>
						<input type="text" class="form-control text-uppercase" name="c_dispositivo" id="c_dispositivo_aux" placeholder="Ingrese el nombre del dispositivo">
					</div>
					<div class="form-group" id="contenedor_conf_aux" style="display: none;">
						<div class="row align-items-center">
							<div class="col">
								<label class="required"><i class="fas fa-laptop-code"></i> Configuraciones</label>
							</div>
							@if (isset($crear_configuracion))
							<div class="col text-end">
								<button type="button" class="btn btn-sm btn-primary btn-auxilar ms-auto btn_nuevo_conf" data-form="create"><i class="fas fa-plus"></i></button>
							</div>
							@endif
						</div>
						<div class="border rounded py-1 px-2">
							<div class="form-row" id="configuraciones_r">
								@foreach ($configuraciones as $configuracion)
								<div class="col-6">
									<div class="form-check d-flex align-items-center my-1">
										<input class="form-check-input m-0 configuraciones_r" type="checkbox" name="configuraciones[]" id="r_conf_{{$configuracion->idconfiguracion}}" value="{{$configuracion->idconfiguracion}}">
										<label class="form-check-label ms-2" for="r_conf_{{$configuracion->idconfiguracion}}">{{$configuracion->configuracion}}</label>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
					<input type="hidden" name="modulo" id="btn_click_disp" value="mapas_de_zonas">
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_registrar_disp"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif

@if (isset($crear_configuracion))
<div class="modal fade" id="modal_registrar_conf" tabindex="-1" aria-labelledby="modal_registrar_conf_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_conf_label"><i class="fas fa-paste"></i> Registro rápido</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro_conf" id="formulario_registro_conf" method="POST" action="{{route('configuracion_disp.store')}}">
					@csrf
					<div class="form-group mb-3">
						<label for="c_configuracion_aux" class="required"><i class="fas fa-laptop-code"></i> Configuración</label>
						<input type="text" class="form-control text-uppercase" name="c_configuracion" id="c_configuracion_aux" placeholder="Ingrese el nombre de la configuración">
					</div>
					<input type="hidden" name="modulo" id="btn_click_conf" value="configuraciones">
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_registrar_conf"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif
@endsection