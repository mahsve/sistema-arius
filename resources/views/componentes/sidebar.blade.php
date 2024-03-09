<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<div class="sidebar-logo text-center d-flex d-lg-none align-items-center justify-content-center py-3 px-4">
		<a href="{{url('/')}}">
			<img src="{{url('images/' . env('LOGO_DARK'))}}" alt="Logo {{env('TITLE')}}">
		</a>
	</div>

	<ul class="nav">
		<li class="nav-item">
			<a class="nav-link" href="{{url('/')}}">
				<i data-feather="pie-chart" class="menu-icon"></i>
				<span class="menu-title">Panel principal</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('/mapas-de-zonas')}}">
				<i data-feather="map" class="menu-icon"></i>
				<span class="menu-title">Mapas de zonas</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#ui-monitoring" aria-expanded="false" aria-controls="ui-monitoring">
				<i data-feather="monitor" class="menu-icon"></i>
				<span class="menu-title">Monitoreo</span>
				<i data-feather="chevron-right" class="menu-arrow"></i>
			</a>
			<div class="collapse" id="ui-monitoring">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"> <a class="nav-link" href="#">Servicio Técnico Solicitdado</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#ui-settings" aria-expanded="false" aria-controls="ui-settings">
				<i data-feather="settings" class="menu-icon"></i>
				<span class="menu-title">Configuración</span>
				<i data-feather="chevron-right" class="menu-arrow"></i>
			</a>
			<div class="collapse" id="ui-settings">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"><a class="nav-link" href="{{route('clientes.index')}}">Clientes</a></li>
					<li class="nav-item"> <a class="nav-link" href="{{route('personal.index')}}">Personal</a></li>
					<li class="nav-item"> <a class="nav-link" href="{{route('cargo.index')}}">Cargos</a></li>
					<li class="nav-item"> <a class="nav-link" href="{{route('departamentos.index')}}">Departamentos</a></li>
				</ul>
			</div>
		</li>
	</ul>
</nav>