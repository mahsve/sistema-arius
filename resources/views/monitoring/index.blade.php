@extends('template')

@section('title', 'Panel principal - Arius Seguridad Integral C.A.')

@section('content')
<div class="row align-items-center mb-4">
	<div class="col-6">
		<h3 class="m-0">Servicios Técnicos solicitados</h3>
	</div>
	<div class="col-6 text-end">
		<button type="button" class="btn btn-primary btn-sm rounded"><i class="fas fa-plus"></i> Nuevo</button>
	</div>
</div>

<div class="card rounded shadow-sm">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col-6">
				<h4 class="m-0">Listado</h4>
			</div>
			<div class="col-6">
				<div class="input-group">
					<input type="text" class="form-control" name="search-table" id="search-table" placeholder="Buscar...">
					<button type="button" class="btn btn-primary btn-sm rounded-end"><i class="fas fa-search" style="font-size: 14px;"></i><span class="ms-2">Buscar</span></button>
				</div>
			</div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>User</th>
					<th>Product</th>
					<th>Sale</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Jacob</td>
					<td>Photoshop</td>
					<td class="text-danger"> 28.76% <i class="ti-arrow-down"></i></td>
					<td><label class="badge badge-danger">Pending</label></td>
				</tr>
				<tr>
					<td>Messsy</td>
					<td>Flash</td>
					<td class="text-danger"> 21.06% <i class="ti-arrow-down"></i></td>
					<td><label class="badge badge-warning">In progress</label></td>
				</tr>
				<tr>
					<td>John</td>
					<td>Premier</td>
					<td class="text-danger"> 35.00% <i class="ti-arrow-down"></i></td>
					<td><label class="badge badge-info">Fixed</label></td>
				</tr>
				<tr>
					<td>Peter</td>
					<td>After effects</td>
					<td class="text-success"> 82.00% <i class="ti-arrow-up"></i></td>
					<td><label class="badge badge-success">Completed</label></td>
				</tr>
				<tr>
					<td>Dave</td>
					<td>53275535</td>
					<td class="text-success"> 98.05% <i class="ti-arrow-up"></i></td>
					<td><label class="badge badge-warning">In progress</label></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@endsection