@extends('plantilla')

@section('title', 'Panel principal - Arius Seguridad Integral C.A.')

@section('scripts')
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-user-circle"></i> Perfil</h4>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-4">
		<div class="card">
			<div class="card-body">
				<img src="{{url('/images/user-default.jpg')}}" alt="Imagen usuario" class="rounded-circle d-block border mx-auto mb-3" style="width: 120px;">
				<div class="w-75 mx-auto text-start">
					<p class="m-0"><i class="fas fa-id-card" style="width: 20px;"></i> <b>Nombre:</b> {{session('personal')->nombre}}</p>
					<p class="m-0"><i class="fas fa-user" style="width: 20px;"></i> <b>Usuario:</b> {{auth()->user()->usuario}}</p>
					<p class="m-0 text-capitalize"><i class="fas fa-phone-alt" style="width: 20px;"></i> <b>Tel:</b> {{session('personal')->telefono1}}</p>
					<p class="m-0 text-lowercase"><i class="fas fa-envelope" style="width: 20px;"></i> {{session('personal')->correo}}</p>
				</div>
			</div>
		</div>
	</div>

	<div class="col-8">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title text-uppercase my-2"><i class="fas fa-user-edit"></i> Datos personales</h4>
				<form action="">


				</form>
			</div>
		</div>
	</div>
</div>
@endsection