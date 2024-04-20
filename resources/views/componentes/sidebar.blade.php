<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<div class="sidebar-logo text-center d-flex d-lg-none align-items-center justify-content-center py-3 px-4">
		<a href="{{url('/')}}">
			<img src="{{url('images/' . env('LOGO_DARK'))}}" alt="Logo {{env('TITLE')}}">
		</a>
	</div>

	<ul class="nav">
		<li class="nav-item">
			<a class="nav-link" href="{{url('/')}}">
				<i class="menu-icon fas fa-chart-pie"></i>
				<span class="menu-title text-uppercase">Panel principal</span>
			</a>
		</li>
		@foreach($modulos_servicios as $modulo)
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href='#ui-{{strtolower(preg_replace("/[\r\n|\n|\r]+/", "-", $modulo->modulo))}}' aria-expanded="false" aria-controls='ui-{{strtolower(preg_replace("/[\r\n|\n|\r]+/", "-", $modulo->modulo))}}'>
				<i class="menu-icon {{$modulo->icono}}"></i>
				<span class="menu-title">{{$modulo->modulo}}</span>
				<i class="menu-arrow fas fa-chevron-right"></i>
			</a>
			<div class="collapse" id='ui-{{strtolower(preg_replace("/[\r\n|\n|\r]+/", "-", $modulo->modulo))}}'>
				<ul class="nav flex-column sub-menu">
					@foreach($modulo->servicios as $servicio)
					@if ($servicio->menu_url != null)
					<li class="nav-item"><a class="nav-link" href="{{route($servicio->menu_url)}}">{{$servicio->servicio}}</a></li>
					@else
					<li class="nav-item"><a class="nav-link" href="#">{{$servicio->servicio}}</a></li>
					@endif
					@endforeach
				</ul>
			</div>
		</li>
		@endforeach
	</ul>
</nav>