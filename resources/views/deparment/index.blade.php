@extends('template')

@section('title', 'Departamentos - ' . env('TITLE'))

@section('content')
<div class="card">
	<div class="card-body border-bottom">
		<div class="row align-items-center">
			<div class="col-6">
				<h4 class="card-title m-0">Departamentos</h4>
			</div>
			<div class="col-6">
				<input type="text" class="form-control" placeholder="Buscar...">
			</div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>N°</th>
					<th>User</th>
					<th>Product</th>
					<th>Sale</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>Jacob</td>
					<td>Photoshop</td>
					<td class="text-danger"> 28.76% <i class="ti-arrow-down"></i></td>
					<td><label class="badge badge-danger">Pending</label></td>
				</tr>
				<tr>
					<td>1</td>
					<td>Messsy</td>
					<td>Flash</td>
					<td class="text-danger"> 21.06% <i class="ti-arrow-down"></i></td>
					<td><label class="badge badge-warning">In progress</label></td>
				</tr>
				<tr>
					<td>1</td>
					<td>John</td>
					<td>Premier</td>
					<td class="text-danger"> 35.00% <i class="ti-arrow-down"></i></td>
					<td><label class="badge badge-info">Fixed</label></td>
				</tr>
				<tr>
					<td>1</td>
					<td>Peter</td>
					<td>After effects</td>
					<td class="text-success"> 82.00% <i class="ti-arrow-up"></i></td>
					<td><label class="badge badge-success">Completed</label></td>
				</tr>
				<tr>
					<td>1</td>
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