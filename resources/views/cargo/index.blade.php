@extends('plantilla')

@section('title', 'Cargo - ' . env('TITLE'))

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
			<h4 class="card-title m-0">Cargo</h4>
		</div>
		<div class="col-6 text-end">
			<button type="button" class="btn btn-primary btn-sm rounded" data-bs-toggle="modal" data-bs-target="#modal-register"><i data-feather="plus"></i> Agregar</button>
		</div>
	</div>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
	<i data-feather="check"></i> {{session('success')}}
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover" id="data-table">
				<thead>
					<tr>
						<th>N°</th>
						<th>Cargo</th>
						<th>Personal</th>
						<th>Creado</th>
						<th>Actualizado</th>
						<th>Estatus</th>
						<th class="text-center"><i data-feather="settings" width="14px" height="14px"></i></th>
					</tr>
				</thead>
				<tbody>
					@foreach($position as $index => $cargo)
					<tr>
						<td>{{$index + 1}}</td>
						<td>{{$cargo->cargo}}</td>
						<td>{{0}} usuarios</td>
						<td>{{date('h:i:s A d/m/y', strtotime($cargo->created))}}</td>
						<td>{{date('h:i:s A d/m/y', strtotime($cargo->updated))}}</td>
						<td>
							@if ($cargo->estatus == "A")
							<label class="badge badge-success"><i data-feather="check" width="14px" height="14px"></i> Activo</label>
							@else
							<label class="badge badge-danger"><i data-feather="x" width="14px" height="14px"></i> Inactivo</label>
							@endif
						</td>
						<td class="p-2" style="width: 20px;">
							<button type="button" class="btn btn-primary btn-sm rounded p-2" onclick="edit('{{$cargo->id_cargo}}')"><i data-feather="edit"></i></button>
							<button type="button" class="btn btn-danger btn-sm rounded p-2"><i data-feather="trash"></i></button>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-register" tabindex="-1" aria-labelledby="modal-register-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title fs-5" id="modal-register-label">Registrar cargo</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="form-register" id="form-register" method="POST" action="{{route('cargo.store')}}">
					@csrf
					<div class="form-group mb-3">
						<label for="cargo_new">Cargo</label>
						<input type="text" class="form-control" name="cargo" id="cargo_new" placeholder="Ingrese el nombre del cargo" required minlength="3">
					</div>
					<div class="form-group">
						<label for="id_departamento_new">Departamento</label>
						<select class="form-control" name="id_departamento" id="id_departamento_new" required>
							<option value="">Seleccione el departamento</option>
							@foreach ($departments as $department)
							<option value="{{$department->id_departamento}}">{{$department->departamento}}</option>
							@endforeach
						</select>
					</div>
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm rounded" data-bs-dismiss="modal"><i data-feather="x"></i> Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm rounded"><i data-feather="save"></i> Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modal-edit-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h1 class="modal-title fs-5" id="modal-edit-label">Modificar cargo</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3">
				<form class="forms-sample" name="form-edit" id="form-edit" method="POST" action="{{route('cargo.update', ['cargo' => 0])}}">
					@csrf
					@method('PATCH')
					<div class="form-group">
						<label for="cargo_edit">Cargo</label>
						<input type="text" class="form-control" name="cargo" id="cargo_edit" placeholder="Ingrese el nombre del cargo" required minlength="3">
					</div>
					<div class="form-group">
						<label for="id_departamento_edit">Departamento</label>
						<select class="form-control" name="id_departamento" id="id_departamento_edit" required>
							<option value="">Seleccione el departamento</option>
							@foreach ($departments as $department)
							<option value="{{$department->id_departamento}}">{{$department->departamento}}</option>
							@endforeach
						</select>
					</div>
					<div class="text-end">
						<button type="button" class="btn btn-secondary btn-sm rounded" data-bs-dismiss="modal"><i data-feather="x"></i> Cerrar</button>
						<button type="submit" class="btn btn-primary btn-sm rounded"><i data-feather="save"></i> Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	const edit = (id) => {
		fetch(`cargo/${id}`, {
			headers: {
				"X-CSRF-Token": document.querySelector('input[name=_token]').value
			},
			method: 'get'
		}).then(response => response.json()).then((data) => {
			const modal_ = new bootstrap.Modal('#modal-edit');
			let action = document.getElementById('form-edit').getAttribute('action').split('/');
			action[action.length - 1] = id;
			action = action.join('/');

			// Cargamos los datos.
			document.getElementById('form-edit').setAttribute('action', action);
			document.getElementById('cargo_edit').value = data.cargo;
			document.getElementById('id_departamento_edit').value = data.id_departamento;
			modal_.show();
		});
	}
</script>
@endsection