@extends('plantilla')

@section('title', 'Mapas de zonas - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script src="{{url('js/datatable/configuracion.js')}}"></script>
<script src="{{url('js/app/mapa_de_zona/index.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-12 col-md-7 col-lg-6 text-start">
			<h4 class="card-title text-uppercase mb-3 my-md-2"><i class="fas fa-map-marked-alt"></i> Mapas de zonas</h4>
		</div>
		<div class="col-12 col-md-5 col-lg-6 text-end">
			@if (isset($permisos->create))
			<div class="form-row justify-content-end">
				<div class="col-12 col-md-8 col-lg-6">
					<a href="{{route('mapas_de_zonas.create')}}" class="btn btn-primary btn-sm w-100"><i class="fas fa-folder-plus me-2"></i>Agregar</a>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table id="data-table" class="table table-hover border-bottom m-0">
				<thead>
					<tr>
						<th class="ps-2" width="40px"><i class="fas fa-barcode"></i> Código</th>
						<th class="ps-2"><i class="fas fa-id-badge"></i> Identificación</th>
						<th class="ps-2"><i class="fas fa-address-card"></i> Cliente</th>
						<th class="ps-2"><i class="fas fa-address-card"></i> Asesor</th>
						<th class="ps-2"><i class="fas fa-desktop"></i> Monitoreo</th>
						<th class="ps-2"><i class="fas fa-toggle-on"></i> Estatus</th>
						@if (isset($permisos->update) or isset($permisos->toggle) or isset($permisos->generar_pdf))
						<th class="ps-2 text-center"><i class="fas fa-cogs"></i></th>
						@endif
					</tr>
				</thead>

				<tbody>
					@foreach ($mapas_de_zonas as $index => $mapa_de_zona)
					<tr>
						<td class="py-1 px-2 text-center">{{$mapa_de_zona->idcodigo}}</td>
						<td class="py-1 px-2">{{$mapa_de_zona->idcliente}}</td>
						<td class="py-1 px-2">{{$mapa_de_zona->cliente}}</td>
						<td class="py-1 px-2">{{$mapa_de_zona->asesor}}</td>
						<td class="py-1 px-2">
							@if ($mapa_de_zona->monitoreo_estatus == "A")
							<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@elseif ($mapa_de_zona->monitoreo_estatus == "N")
							<span class="badge badge-info"><i class="fas fa-times"></i> No contratado</span>
							@else
							<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						<td class="py-1 px-2">
							@if ($mapa_de_zona->estatus == "A")
							<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
							@elseif ($mapa_de_zona->estatus == "R")
							<span class="badge badge-success"><i class="fas fa-check"></i> Registrado</span>
							@elseif ($mapa_de_zona->estatus == "I")
							<span class="badge badge-success"><i class="fas fa-check"></i> Instalado</span>
							@else
							<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
							@endif
						</td>
						@if (isset($permisos->update) or isset($permisos->toggle) or isset($permisos->generar_pdf))
						<td class="py-1 px-2" width="63px">
							@if (isset($permisos->update))
							<a href="{{route('mapas_de_zonas.edit', ['id' => $mapa_de_zona->idcodigo])}}" class="btn btn-primary btn-sm btn-icon"><i class="fas fa-edit"></i></a>
							@endif
							@if (isset($permisos->toggle) or isset($permisos->generar_pdf))
							<div class="d-inline dropdown no_arrow">
								<button class="btn btn-secondary btn-sm btn-icon dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="fas fa-ellipsis-v px-1"></i>
								</button>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item" href="{{route('mapas_de_zonas.pdf', ['id' => $mapa_de_zona->idcodigo])}}" target="blank"><i class="fas fa-print"></i> Imprimir</a></li>
								</ul>
							</div>
							@endif
						</td>
						@endif
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection