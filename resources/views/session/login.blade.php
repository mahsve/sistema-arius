@extends('session.template')

@section('title', 'Iniciar sesión - ' . env('TITLE'))

@section('content')
<h4>Hello! let's get started</h4>
<h6 class="fw-light">Sign in to continue.</h6>
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show mt-3 border-0 mb-1" role="alert">
	{{session('error')}}
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{session('user')}}
<form class="pt-3" name="form-login" id="form-login" method="POST" action="{{route('login')}}">
	@csrf
	<div class="form-group">
		<input type="text" class="form-control form-control-lg" name="username" id="username" placeholder="Nombre de usuario" required>
	</div>
	<div class="form-group">
		<input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Contraseña" required>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary btn-lg w-100"><i class="icon-log-in"></i> Iniciar sesión</button>
	</div>
	<div class="text-end">
		<a href="{{route('show-recover')}}" class="auth-link text-black">¡Olvidé mi contraseña!</a>
	</div>
</form>
@endsection