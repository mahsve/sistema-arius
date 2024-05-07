@extends('sesion.plantilla')

@section('title', 'Restablecer contraseña - ' . env('TITLE'))

@section('scripts')
<script src="{{url('js/app/sesion/restablecer_contrasena.js')}}"></script>
@endsection

@section('content')
<h4 class="text-center">Restablecer contraseña</h4>
<h6 class="text-center fw-light">Ingrese su nueva contraseña para recuperar el acceso a su cuenta</h6>

<!-- Formulario verificar restablecer contraseña -->
<form class="pt-3" name="formulario_contrasenas" id="formulario_contrasenas" method="POST" action="{{route('session.reset_password')}}">
  @csrf
  <div class="form-group mb-3">
    <input type="text" class="form-control form-control-lg rounded" value="{{$usuario->usuario}}" style="background: #f2f2f2;" readonly>
  </div>
  <div class="form-group mb-3">
    <div class="position-relative">
      <input type="password" class="form-control form-control-lg rounded" name="contrasena1" id="contrasena1" placeholder="Ingrese la nueva contraseña">
      <i class="fas fa-eye position-absolute toggle-password" data-toggle="contrasena1"></i>
    </div>
  </div>
  <div class="form-group mb-3">
    <div class="position-relative">
      <input type="password" class="form-control form-control-lg rounded" name="contrasena2" id="contrasena2" placeholder="Repita la contraseña">
      <i class="fas fa-eye position-absolute toggle-password" data-toggle="contrasena2"></i>
    </div>
  </div>
  <div class="form-group mb-3">
    <button type="submit" class="btn btn-primary w-100" id="btn_submit"><i class="fas fa-unlock-alt me-2"></i>Actualizar contraseña</button>
  </div>
</form>
@endsection