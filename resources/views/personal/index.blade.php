@extends('template')

@section('title', 'Personal - ' . env('TITLE'))

@section('styles')
<link href="{{url('css/datatable/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{url('js/datatable/datatables.min.js')}}"></script>
<script>
	setTimeout(() => {
		let table = new DataTable('#data-table', {
			language: {
				url: '{{url("js/datatable-languaje-ES.json")}}',
			},
		});
	}, 500);
</script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Personal</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{route('personal.create')}}" class="btn btn-primary btn-sm rounded"><i data-feather="plus"></i> Agregar</a>
		</div>
	</div>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
	<i data-feather="check"></i> {{session('success')}}
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
	<i data-feather="x"></i> {{session('error')}}
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover" id="data-table">
				<thead>
					<tr>
						<th class="text-center">N°</th>
						<th>Cédula</th>
						<th>Personal</th>
						<th>Usuario</th>
						<th>Creado</th>
						<th>Actualizado</th>
						<th>Estatus</th>
						<th class="text-center"><i data-feather="settings" width="14px" height="14px"></i></th>
					</tr>
				</thead>
				<tbody>
					@foreach($personals as $index => $personal)
					<tr>
						<td class="text-end">{{$index + 1}}</td>
						<td>{{$personal->cedula}}</td>
						<td>{{$personal->nombres . ' ' . $personal->apellidos}}</td>
						<td>{{$personal->usuario}}</td>
						<td>{{date('h:i:s A d/m/y', strtotime($personal->created))}}</td>
						<td>{{date('h:i:s A d/m/y', strtotime($personal->updated))}}</td>
						<td>
							@if ($personal->estatus == "A")
							<label class="badge badge-success"><i data-feather="check" width="14px" height="14px"></i> Activo</label>
							@else
							<label class="badge badge-danger"><i data-feather="x" width="14px" height="14px"></i> Inactivo</label>
							@endif
						</td>
						<td class="p-2" style="width: 20px;">
							@if ($personal->cedula != session('user')->cedula)
							<a href="{{route('personal.edit', ['personal' => $personal->cedula])}}" class="btn btn-primary btn-sm rounded p-2"><i data-feather="edit"></i></a>
							<button type="button" class="btn btn-danger btn-sm rounded p-2"><i data-feather="trash"></i></button>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection