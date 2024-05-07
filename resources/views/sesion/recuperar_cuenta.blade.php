@extends('sesion.plantilla')

@section('title', 'Recuperar cuenta - ' . env('TITLE'))

@section('scripts')
<script src="{{url('js/app/sesion/recuperar_cuenta.js')}}"></script>
@endsection

@section('content')
<h4 class="text-center">Recuperar cuenta</h4>
<h6 class="text-center fw-light">Ingrese su usuario para reestablecer su contraseña de acceso</h6>

<!-- Formulario buscar usuario -->
<form class="pt-3" name="formulario_recuperar" id="formulario_recuperar" method="POST" action="{{route('session.recover')}}">
	@csrf
	<div class="form-group mb-3">
		<input type="text" class="form-control form-control-lg rounded" name="usuario" id="usuario" placeholder="Nombre de usuario">
	</div>
	<div class="form-group mb-3">
		<button type="submit" class="btn btn-primary w-100" id="btn_submit"><i class="fas fa-search me-2"></i>Buscar usuario</button>
	</div>
	<div>
		<a href="{{route('session.login')}}" class="auth-link text-black"><i class="fas fa-arrow-left me-2"></i>Regresar al iniciar sesión</a>
	</div>
</form>
@endsection