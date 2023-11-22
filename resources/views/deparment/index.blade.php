@extends('template')

@section('title', 'Departamentos - ' . env('TITLE'))

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Departamentos</h4>
		</div>
		<div class="col-6 text-end">
			<button type="button" class="btn btn-primary btn-sm rounded" data-bs-toggle="modal" data-bs-target="#form-register"><i data-feather="plus"></i> Agregar</button>
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover" id="data-table">
				<thead>
					<tr>
						<th>N°</th>
						<th>Departamento</th>
						<th>Departamento</th>
						<th>Estatus</th>
					</tr>
				</thead>
				<tbody>
					@foreach($departments as $department)
					<tr>
						<td>1</td>
						<td>Jacob</td>
						<td>Photoshop</td>
						<td class="text-danger"> 28.76% <i class="ti-arrow-down"></i></td>
						<td><label class="badge badge-danger">Pending</label></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="form-register" tabindex="-1" aria-labelledby="form-register-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="forms-sample" name="form-register" id="form-register" method="POST" action="{{url('create-client')}}">
					@csrf
					<div class="form-group">
						<label for="exampleInputUsername1">Username</label>
						<input type="text" class="form-control" id="exampleInputUsername1" placeholder="Username">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Email address</label>
						<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="exampleInputPassword1">Password</label>
						<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
					</div>
					<div class="form-group">
						<label for="exampleInputConfirmPassword1">Confirm Password</label>
						<input type="password" class="form-control" id="exampleInputConfirmPassword1" placeholder="Password">
					</div>
					<div class="form-check form-check-flat form-check-primary">
						<label class="form-check-label">
							<input type="checkbox" class="form-check-input">
							Remember me
						</label>
					</div>
					<button type="submit" class="btn btn-primary me-2">Submit</button>
					<button class="btn btn-light">Cancel</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>
@endsection