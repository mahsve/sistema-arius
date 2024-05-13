@extends('plantilla')

@section('title', 'Gestionar reporte diario - ' . env('TITLE'))

@section('scripts')
<script src="{{url('js/app/monitoreo/gestionar.js')}}"></script>
<script>
	const hora = (data) => {
		const hora_ = parseInt(data.substr(0, 2));
		const minutos_ = parseInt(data.substr(3, 2));
		const ampm_ = hora_ < 12 ? 'AM' : 'PM';
		return `${(hora_ > 12 ? hora_-12 : hora_).toString().padStart(2, '0')}:${minutos_.toString().padStart(2, '0')} ${ampm_}`;
	}
</script>

@foreach($eventos as $index => $evento)
<script class="script_auxiliar">
	document.getElementById('add_html_eventos').dispatchEvent(new Event('click')); // Ejecutamos el evento click del botón agregar nueva zona.

	// Capturamos el ID rand para acceder a los id de cada td y empezar a rellenar los datos.
	var tc = document.querySelectorAll('#tabla_eventos tbody tr').length; // Capturamos el total de tr en la tabla.
	var idrand = document.querySelectorAll('#tabla_eventos tbody tr')[tc - 1].getAttribute('data-rand');
	document.getElementById(`evento_codigo_${idrand}`).innerHTML = "{{$evento->idcodigo}}";
	document.getElementById(`evento_hora_${idrand}`).innerHTML = hora("{{$evento->hora}}");
	document.getElementById(`evento_cliente_${idrand}`).innerHTML = "{{$evento->cliente}}";
	document.getElementById(`evento_evento_${idrand}`).innerHTML = "{{$evento->evento}}";
	document.getElementById(`btn_editar_evento_${idrand}`).setAttribute('data-id', "{{$evento->iddetalle}}");
	document.getElementById(`btn_eliminar_evento_${idrand}`).setAttribute('data-id', "{{$evento->iddetalle}}");

	// Eliminamos estas variables para evitar filtrar información.
	tc = null;
	idrand = null;

	// Eliminamos toda esta etiqueca con el código Javascript.
	document.querySelector('.script_auxiliar').remove();
</script>
@endforeach
<script class="script_auxiliar2">
	document.getElementById('add_html_eventos').remove();
	document.querySelector('.script_auxiliar2').remove();
</script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-12 col-md-7 col-lg-6 text-start">
			<h4 class="card-title text-uppercase mb-3 my-md-2"><i class="fas fa-folder-open"></i> Gestionar reporte diario</h4>
		</div>
		<div class="col-12 col-md-5 col-lg-6 text-end">
			<div class="form-row justify-content-end">
				<div class="col-12 col-md-8 col-lg-6 d-flex">
					<a href="{{route('monitoreo.index')}}" class="btn btn-primary btn-sm w-50"><i class="fas fa-chevron-left me-2"></i>Regresar</a>
					<a href="{{route('monitoreo.index')}}" class="btn btn-primary btn-sm w-50 ms-1" id="btn_guardar"><i class="fas fa-save me-2"></i>Guardar</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<form class="forms-sample" name="formulario_gestionar" id="formulario_gestionar" method="POST" action="{{route('monitoreo.update', ['id' => $reporte->idreporte])}}">
			@csrf
			@method('PATCH')
			<div class="form-row">
				<div class="col-4">
					<div class="form-group mb-3">
						<label for="c_fecha_r" class="required"><i class="fas fa-calendar-day"></i> Fecha</label>
						<input type="text" class="form-control text-uppercase" value="{{$reporte->fecha}}" readonly>
					</div>
				</div>
				<div class="col-4">
					<div class="form-group mb-3">
						<label for="c_operador_r" class="required"><i class="fas fa-users-cog"></i> Operador</label>
						<input type="text" class="form-control text-uppercase" value="{{$reporte->operador}}" readonly>
					</div>
				</div>
				<div class="col-4">
					<div class="form-group mb-3">
						<label for="c_turno_r" class="required"><i class="fas fa-business-time"></i> Turno</label>
						<input type="text" class="form-control text-uppercase" value="{{$reporte->turno}}" readonly>
					</div>
				</div>
			</div>

			<!-- Tablas -->
			<div class="form-row mt-3">
				<div class="col-12 col-lg-6 mb-3">
					<div class="border rounded overflow-hidden">
						<table class="table">
							<tr>
								<td class="p-3 text-center text-white" colspan="2" style="padding: .5rem; background: #002060;"><span style="font-size: 0.7rem; font-weight: bold;">LEYENDA</span></td>
							</tr>
							<tr>
								<td style="padding: .5rem; background: #00B0F0;"><span style="font-size: 0.7rem; font-weight: bold;">AP FUERA DE HORARIO SIN CLO<span></td>
								<td style="padding: .5rem; background: #FF0000;"><span style="font-size: 0.7rem; font-weight: bold;">PRUEBAS REALIZADAS<span></td>
							</tr>
							<tr>
								<td style="padding: .5rem; background: #00B0F0;"><span style="font-size: 0.7rem; font-weight: bold;">AP Y CLO FUERA DE HORARIO<span></td>
								<td style="padding: .5rem; background: #FFC000;"><span style="font-size: 0.7rem; font-weight: bold;">TÉCNICOS EN EL ÁREA<span></td>
							</tr>
							<tr>
								<td style="padding: .5rem; background: black; color: white;"><span style="font-size: 0.7rem; font-weight: bold;">EVENTUALIDADES EN LA EMPRESA<span></td>
								<td style="padding: .5rem; background: #FFFF00;"><span style="font-size: 0.7rem; font-weight: bold;">EVENTOS ACUMULADOS<span></td>
							</tr>
							<tr>
								<td style="padding: .5rem; background: #92D050;"><span style="font-size: 0.7rem; font-weight: bold;">INFORMACION RECIBIDA POR<span></td>
								<td style="padding: .5rem; background: #FFC5C5;"><span style="font-size: 0.7rem; font-weight: bold;">PERSONAL LABORANDO<span></td>
							</tr>
							<tr>
								<td style="padding: .5rem; background: #B2A1C7;"><span style="font-size: 0.7rem; font-weight: bold;">ACTIVACIONES POR FALLO<span></td>
								<td style="padding: .5rem;"><span style="font-size: 0.7rem; font-weight: bold;">SIN NOVEDAD<span></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="col-12 col-lg-6 mb-3">
					<div class="border rounded overflow-hidden">
						<table class="table">
							<tr>
								<td class="p-3 text-center text-white" colspan="2" style="padding: .5rem; background: #002060;"><span style="font-size: 0.7rem; font-weight: bold;">ESTADO AL RECIBIR TURNO</span></td>
							</tr>
							<tr>
								<td class="form-group pt-0 px-1 pb-1 m-0 border-0"><label for="c_mensajeria_m" class="required" style="white-space: nowrap;"><i class="fas fa-envelope"></i> Servicio mensajería</label></td>
								<td class="form-group pt-0 px-1 pb-1 m-0 border-0"><input type="text" class="form-control text-uppercase w-100" name="c_mensajeria" id="c_mensajeria_m" value="{{$reporte->servidormensajeria}}" placeholder="Ingrese el nombre completo"></td>
							</tr>
							<tr>
								<td class="form-group pt-0 px-1 pb-1 m-0 border-0"><label for="c_radio_m" class="required" style="white-space: nowrap;"><i class="fas fa-broadcast-tower"></i> Radio</label></td>
								<td class="form-group pt-0 px-1 pb-1 m-0 border-0"><input type="text" class="form-control text-uppercase w-100" name="c_radio" id="c_radio_m" value="{{$reporte->radio}}" placeholder="Ingrese el nombre completo"></td>
							</tr>
							<tr>
								<td class="form-group pt-0 px-1 pb-1 m-0 border-0"><label for="c_lineas_m" class="required" style="white-space: nowrap;"><i class="fas fa-sim-card"></i> Lineas de reporte: L1, L2, L3, L4: </label></td>
								<td class="form-group pt-0 px-1 pb-1 m-0 border-0"><input type="text" class="form-control text-uppercase w-100" name="c_lineas" id="c_lineas_m" value="{{$reporte->lineasreportes}}" placeholder="Ingrese el nombre completo"></td>
							</tr>
							<tr>
								<td class="form-group pt-0 px-1 pb-1 m-0 border-0"><label for="c_observacion_m" class="required" style="white-space: nowrap;"><i class="fas fa-sticky-note"></i> Observación</label></td>
								<td class="form-group pt-0 px-1 pb-1 m-0 border-0"><textarea class="form-control w-100" name="c_observacion" id="c_observacion_m" placeholder="Ingrese la dirección" rows="2">{{$reporte->observaciones}}</textarea></td>
							</tr>
						</table>
					</div>
				</div>
			</div>

			<!-- TITULO -->
			<div class="row align-items-center mt-3 mb-3">
				<div class="col-12 col-md-6 text-start">
					<h4 class="text-uppercase mb-3 m-md-0"><i class="fas fa-video"></i> Eventos</h4>
				</div>
				<div class="col-12 col-md-6 text-end">
					<div class="form-row justify-content-end">
						<div class="col-12 col-md-6">
							<button type="button" class="btn btn-primary btn-sm w-100" id="btn_agregar_evento"><i class="fas fa-exclamation-triangle me-2"></i>Agregar evento</button>
							<button type="button" id="add_html_eventos" style="display: none;"></button>
						</div>
					</div>
				</div>
			</div>

			<!-- LISTADO -->
			<div class="table-responsive border rounded mb-4">
				<table id="tabla_eventos" class="table table-hover m-0">
					<thead>
						<tr>
							<th class="px-2 text-center" width="40px"><i class="fas fa-arrows-alt"></i></th>
							<th class="px-2 text-center" width="40px"><i class="fas fa-list-ol"></i> N°</th>
							<th class="px-2" width="60px"><i class="fas fa-barcode"></i> Código</th>
							<th class="px-2" width="60px"><i class="fas fa-clock"></i> Hora</th>
							<th class="px-2"><i class="fas fa-address-card"></i> Cliente</th>
							<th class="px-2"><i class="fas fa-exclamation-triangle"></i> Evento sucedido</th>
							<th class="px-2 text-center" width="55px"><i class="fas fa-cogs"></i></th>
						</tr>
					</thead>
					<tbody>
						<tr class="sin_eventos">
							<td colspan="8" class="text-center"><i class="fas fa-times"></i> Sin eventos agregados</td>
						</tr>
					</tbody>
				</table>
			</div>
		</form>
	</div>
</div>

<!-- Gestionar eventos -->
<div class="modal fade" id="modal_registrar" tabindex="-1" aria-labelledby="modal_registrar_label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_registrar_label"><i class="fas fa-folder-plus"></i> Registrar evento</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_registro" id="formulario_registro" method="POST" action="{{route('monitoreo.event', ['id' => $reporte->idreporte])}}">
					@csrf
					<div class="form-row">
						<div class="col-12 col-xl-2">
							<div class="form-group mb-3">
								<label for="c_codigo_r" class="required"><i class="fas fa-barcode"></i> Código</label>
								<input type="text" class="form-control text-uppercase" name="c_codigo" id="c_codigo_r" placeholder="COD" maxlength="4">
							</div>
						</div>
						<div class="col-12 col-xl-10">
							<div class="form-group mb-3">
								<label for="c_cliente_r" class="required"><i class="fas fa-address-card"></i> Nombre/Razón social</label>
								<div class="d-flex">
									<input type="text" class="form-control text-uppercase" name="c_cliente" id="c_cliente_r" readonly>
									<div class="d-flex ms-2">
										<button type="button" class="btn btn-primary btn-sm btn-icon" id="btn_modal_cliente"><i class="fas fa-external-link-alt"></i></button>
										<button type="button" class="btn btn-primary btn-sm btn-icon" id="btn_borrar_cliente" style="display: none;"><i class="fas fa-backspace"></i></button>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="c_codigo2" id="c_codigo_auxr">
						<div class="col-12 col-xl-3">
							<div class="form-group mb-3">
								<label for="c_hora_r" class="required"><i class="fas fa-barcode"></i> Hora</label>
								<input type="time" class="form-control text-uppercase" name="c_hora" id="c_hora_r">
							</div>
						</div>
						<div class="col-12 col-xl-9">
							<div class="form-group mb-3">
								<label for="c_evento_r" class="required"><i class="fas fa-exclamation-triangle"></i> Evento sucedido</label>
								<textarea class="form-control text-uppercase" name="c_evento" id="c_evento_r"></textarea>
							</div>
						</div>
					</div>
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_registrar"><i class="fas fa-save me-2"></i>Guardar</button>
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
								<th class="ps-2" width="60px"><i class="fas fa-barcode"></i> Código</th>
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

<div class="modal fade" id="modal_modificar" tabindex="-1" aria-labelledby="modal_modificar_label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title text-uppercase fs-5" id="modal_modificar_label"><i class="fas fa-folder-open"></i> Modificar evento</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="formulario_actualizacion" id="formulario_actualizacion" method="POST">
					@csrf
					@method('PATCH')
					<div class="form-row">
						<div class="col-12 col-xl-2">
							<div class="form-group mb-3">
								<label for="c_codigo_m" class="required"><i class="fas fa-barcode"></i> Código</label>
								<input type="text" class="form-control text-uppercase" name="c_codigo" id="c_codigo_m" readonly>
							</div>
						</div>
						<div class="col-12 col-xl-10">
							<div class="form-group mb-3">
								<label for="c_cliente_m" class="required"><i class="fas fa-address-card"></i> Nombre/Razón social</label>
								<input type="text" class="form-control text-uppercase" id="c_cliente_m" readonly>
							</div>
						</div>
						<div class="col-12 col-xl-3">
							<div class="form-group mb-3">
								<label for="c_hora_m" class="required"><i class="fas fa-barcode"></i> Hora</label>
								<input type="time" class="form-control text-uppercase" name="c_hora" id="c_hora_m">
							</div>
						</div>
						<div class="col-12 col-xl-9">
							<div class="form-group mb-3">
								<label for="c_evento_m" class="required"><i class="fas fa-exclamation-triangle"></i> Evento sucedido</label>
								<textarea class="form-control text-uppercase" name="c_evento" id="c_evento_m"></textarea>
							</div>
						</div>
					</div>
					<input type="hidden" name="idreporte" value="{{$reporte->idreporte}}">
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn_modificar"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection