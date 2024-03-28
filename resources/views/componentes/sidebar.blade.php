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
				<span class="menu-title">Panel principal</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{route('mapas_de_zonas.index')}}">
				<i class="menu-icon fas fa-map-marked-alt"></i>
				<span class="menu-title">Mapas de zonas</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#ui-monitoreo" aria-expanded="false" aria-controls="ui-monitoreo">
				<i class="menu-icon fas fa-desktop"></i>
				<span class="menu-title">Monitoreo</span>
				<i class="menu-arrow fas fa-chevron-right"></i>
			</a>
			<div class="collapse" id="ui-monitoreo">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"> <a class="nav-link" href="#">Servicio Técnico Solicitdado</a></li>
					<li class="nav-item"> <a class="nav-link" href="#">Servicio Técnico Solicitdado</a></li>
					<li class="nav-item"> <a class="nav-link" href="#">Servicio Técnico Solicitdado</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#ui-configuracion" aria-expanded="false" aria-controls="ui-configuracion">
				<i class="menu-icon fas fa-cogs"></i>
				<span class="menu-title">Configuración</span>
				<i class="menu-arrow fas fa-chevron-right"></i>
			</a>
			<div class="collapse" id="ui-configuracion">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"><a class="nav-link" href="{{route('clientes.index')}}">Clientes</a></li>
					<li class="nav-item"> <a class="nav-link" href="{{route('departamentos.index')}}">Departamentos</a></li>
					<li class="nav-item"> <a class="nav-link" href="{{route('cargos.index')}}">Cargos</a></li>
					<li class="nav-item"> <a class="nav-link" href="{{route('personal.index')}}">Personal</a></li>
					<li class="nav-item"> <a class="nav-link" href="{{route('dispositivos.index')}}">Dispositivos</a></li>
					<li class="nav-item"> <a class="nav-link" href="{{route('dispositivo_cog.index')}}">Configuración Disp.</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#ui-seguridad" aria-expanded="false" aria-controls="ui-seguridad">
				<i class="menu-icon fas fa-user-shield"></i>
				<span class="menu-title">Seguridad</span>
				<i class="menu-arrow fas fa-chevron-right"></i>
			</a>
			<div class="collapse" id="ui-seguridad">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"><a class="nav-link" href="{{route('roles.index')}}">Roles</a></li>
					<li class="nav-item"><a class="nav-link" href="{{route('roles.index')}}">Módulos</a></li>
					<li class="nav-item"><a class="nav-link" href="{{route('roles.index')}}">Bitácora</a></li>
				</ul>
			</div>
		</li>
	</ul>
</nav>