@extends('plantilla')

@section('title', 'Seguridad - Arius Seguridad Integral C.A.')

@section('scripts')
<script src="{{url('js/app/perfil/seguridad.js')}}"></script>
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-user-shield"></i> Seguridad</h4>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12 col-lg-5">
		<div class="card mb-4">
			<div class="card-body">
				<!-- TITUTLO -->
				<div class="row align-items-center mb-3">
					<div class="col-12 text-start">
						<h4 class="text-uppercase m-0"><i class="fas fa-key"></i> Nueva contraseña</h4>
					</div>
				</div>

				<!-- FORMULARIO -->
				<form class="forms-sample" name="formulario_contrasenas" id="formulario_contrasenas" method="POST" action="{{route('profile.update')}}">
					@csrf
					<div class="form-row">
						<div class="form-group col-12">
							<label for="nueva_contrasena" class="required"><i class="fas fa-unlock"></i> Nueva contraseña</label>
							<input type="password" class="form-control" name="nueva_contrasena" id="nueva_contrasena" placeholder="Ingrese su nueva contraseña">
						</div>
						<div class="form-group col-12">
							<label for="repetir_contrasena" class="required"><i class="fas fa-unlock"></i> Repita su contraseña</label>
							<input type="password" class="form-control" name="repetir_contrasena" id="repetir_contrasena" placeholder="Repita la nueva contraseña">
						</div>
						<div class="form-group col-12">
							<label for="actual_contrasena" class="required"><i class="fas fa-unlock"></i> Contraseña actual</label>
							<input type="password" class="form-control" name="actual_contrasena" id="actual_contrasena" placeholder="Ingrese su contraseña actual">
						</div>
					</div>

					<div class="text-end">
						<button type="reset" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Limpiar</button>
						<button type="submit" class="btn btn-primary" id="btn_guardar_contrasena"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="col-12 col-lg-7">
		<div class="card mb-4">
			<div class="card-body">
				<!-- TITUTLO -->
				<div class="row align-items-center mb-3">
					<div class="col-12 text-start">
						<h4 class="text-uppercase m-0"><i class="fas fa-question"></i> Preguntas de seguridad</h4>
					</div>
				</div>

				<!-- FORMULARIO -->
				<form class="forms-sample" name="formulario_preguntas" id="formulario_preguntas" method="POST" action="{{route('security.update')}}">
					@csrf
					<div class="form-row">
						<div class="form-group col-12 col-lg-6">
							<label for="pregunta_1" class="required"><i class="fas fa-question-circle"></i> Pregunta de seguridad</label>
							<input type="text" class="form-control text-uppercase" name="pregunta_1" id="pregunta_1" value="{{auth()->user()->pregunta1}}" placeholder="Ingrese el nombre completo">
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="respuesta_1" class="required"><i class="fas fa-comments"></i> Respuesta</label>
							<input type="password" class="form-control text-uppercase" name="respuesta_1" id="respuesta_1" placeholder="Ingrese el nombre completo">
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="pregunta_2" class="required"><i class="fas fa-question-circle"></i> Pregunta de seguridad</label>
							<input type="text" class="form-control text-uppercase" name="pregunta_2" id="pregunta_2" value="{{auth()->user()->pregunta2}}" placeholder="Ingrese el nombre completo">
						</div>
						<div class="form-group col-12 col-lg-6">
							<label for="respuesta_2" class="required"><i class="fas fa-comments"></i> Respuesta</label>
							<input type="password" class="form-control text-uppercase" name="respuesta_2" id="respuesta_2" placeholder="Ingrese el nombre completo">
						</div>
						<div class="form-group col-12">
							<label for="actual_contrasena2" class="required"><i class="fas fa-unlock"></i> Contraseña actual</label>
							<input type="password" class="form-control" name="actual_contrasena" id="actual_contrasena2" placeholder="Ingrese su contraseña actual">
						</div>
					</div>

					<div class="text-end">
						<button type="reset" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Limpiar</button>
						<button type="submit" class="btn btn-primary" id="btn_guardar_preguntas"><i class="fas fa-save me-2"></i>Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection