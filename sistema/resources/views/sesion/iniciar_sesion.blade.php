@extends('sesion.plantilla')

@section('title', 'Iniciar sesión - ' . env('TITLE'))

@section('scripts')
<script src="{{url('js/app/sesion/iniciar_sesion.js')}}"></script>
@endsection

@section('content')
<h4 class="text-center">Bienvenido al panel administrativo</h4>
<h6 class="text-center fw-light">Inicie sesión para continuar</h6>

<form class="pt-3" name="formulario_sesion" id="formulario_sesion" method="POST" action="{{route('session.login')}}">
	@csrf
	<div class="form-group mb-3">
		<input type="text" class="form-control form-control-lg rounded" name="usuario" id="usuario" placeholder="Nombre de usuario">
	</div>
	<div class="form-group mb-3">
		<div class="position-relative">
			<input type="password" class="form-control form-control-lg rounded" name="contrasena" id="contrasena" placeholder="Contraseña">
			<i class="fas fa-eye position-absolute toggle-password" data-toggle="contrasena"></i>
		</div>
	</div>
	<div class="form-group mb-3">
		<button type="submit" class="btn btn-primary w-100" id="btn_submit"><i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión</button>
	</div>
	<div class="text-end">
		<a href="{{route('session.recover')}}" class="auth-link text-black">Recuperar contraseña<i class="fas fa-arrow-right ms-2"></i></a>
	</div>
</form>
@endsection