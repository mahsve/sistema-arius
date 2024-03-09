@extends('session.template')

@section('title', 'Restablecer contraseña - ' . env('TITLE'))

@section('content')
<h4>Hello! let's get started</h4>
<h6 class="fw-light">Sign in to continue.</h6>
<form class="pt-3" name="form-login" id="form-login" method="POST" action="{{route('login')}}">
	<div class="form-group">
		<input type="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username">
	</div>
	<div class="form-group">
		<a class="btn btn-primary btn-lg w-100" href="../../index.html"><i class="icon-log-in"></i> Iniciar sesión</a>
	</div>

	<a href="{{route('show-login')}}" class="auth-link text-black">¡Iniciar sesión!</a>
</form>
@endsection