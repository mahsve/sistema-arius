@extends('sesion.plantilla')

@section('title', 'Preguntas de seguridad - ' . env('TITLE'))

@section('scripts')
<script src="{{url('js/app/sesion/preguntas_seguridad.js')}}"></script>
@endsection

@section('content')
<h4 class="text-center">Preguntas de seguridad</h4>
<h6 class="text-center fw-light">Verifique su identidad contestanto las preguntas de seguridad</h6>

<!-- Formulario verificar preguntas de seguridad -->
<form class="pt-3" name="formulario_preguntas" id="formulario_preguntas" method="POST" action="{{route('session.verify_answers')}}">
	@csrf
	<div class="form-group mb-3">
		<input type="text" class="form-control form-control-lg rounded" value="{{$usuario->usuario}}" style="background: #f2f2f2;" readonly>
	</div>
	<div class="form-group mb-3">
		<input type="text" class="form-control form-control-lg rounded" value="{{$usuario->pregunta1}}" style="background: #f2f2f2;" readonly>
	</div>
	<div class="form-group mb-3">
		<div class="position-relative">
			<input type="password" class="form-control form-control-lg rounded" name="respuesta1" id="respuesta1" placeholder="Primera respuesta">
			<i class="fas fa-eye position-absolute toggle-password" data-toggle="respuesta1"></i>
		</div>
	</div>
	<div class="form-group mb-3">
		<input type="text" class="form-control form-control-lg rounded" value="{{$usuario->pregunta2}}" style="background: #f2f2f2;" readonly>
	</div>
	<div class="form-group mb-3">
		<div class="position-relative">
			<input type="password" class="form-control form-control-lg rounded" name="respuesta2" id="respuesta2" placeholder="Segunda respuesta">
			<i class="fas fa-eye position-absolute toggle-password" data-toggle="respuesta2"></i>
		</div>
	</div>
	<div class="form-group mb-3">
		<button type="submit" class="btn btn-primary w-100" id="btn_submit"><i class="fas fa-user-check me-2"></i>Verificar respuestas</button>
	</div>
</form>
@endsection