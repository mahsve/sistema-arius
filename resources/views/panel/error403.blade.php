@extends('plantilla')

@section('title', 'Error 403 - Acceso no autorizado - ' . env('TITLE'))

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: calc(100vh - 225px);">
	<div class="card w-100 h-100">
		<div class="card-body d-flex justify-content-center align-items-center">
			<div class="text-center">
				<img src="{{url('/images/'.env('LOGO_LIGHT'))}}" alt="{{env('TITLE')}}" width="300">
				<h1 class="fw-bold mt-5 mb-3">Error 403</h1>
				<p class="mb-4">El personal no tiene acceso autorizado para acceder a este apartado de la aplicación.<br>Hable con el administrador.</p>
				<a href="{{url('/')}}" class="btn btn-primary text-uppercase"><i class="fas fa-chart-pie me-2"></i> Ir al panel</a>
			</div>
		</div>
	</div>
</div>
@endsection