@extends('template')

@section('title', 'Registrar cliente - ' . env('TITLE'))

@section('content')
<div class="mb-4">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title m-0">Registrar cliente</h4>
		</div>
		<div class="col-6 text-end">
			<a href="{{url('clientes')}}" class="btn btn-primary btn-sm rounded"><i data-feather="chevron-left"></i> Regresar</a>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
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
</div>
@endsection