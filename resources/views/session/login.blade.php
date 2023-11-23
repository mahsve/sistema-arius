@extends('session.template')

@section('title', 'Iniciar sesión - ' . env('TITLE'))

@section('content')
<h4 class="text-center">Bienvenido al panel administrativo</h4>
<h6 class="text-center fw-light">Inicie sesión para continuar</h6>
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show mt-3 border-0 mb-1" role="alert">
	{{session('error')}}
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{session('user')}}
<form class="pt-3" name="form-login" id="form-login" method="POST" action="{{route('login')}}">
	@csrf
	<div class="form-group mb-3">
		<input type="text" class="form-control form-control-lg rounded" name="username" id="username" placeholder="Nombre de usuario" required>
	</div>
	<div class="form-group mb-3">
		<input type="password" class="form-control form-control-lg rounded" name="password" id="password" placeholder="Contraseña" required>
	</div>
	<div class="form-group mb-3">
		<button type="submit" class="btn btn-primary w-100"><i data-feather="log-in"></i> Iniciar sesión</button>
	</div>
	<div class="text-end">
		<a href="{{url('recuperar')}}" class="auth-link text-black">¡Olvidé mi contraseña!</a>
	</div>
</form>
@endsection