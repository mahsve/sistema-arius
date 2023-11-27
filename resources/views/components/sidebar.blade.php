<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<div class="sidebar-logo text-center d-flex d-lg-none align-items-center justify-content-center py-3 px-4">
		<a href="{{url('/')}}">
			<img src="{{url('images/' . env('LOGO_DARK'))}}" alt="Logo {{env('TITLE')}}">
		</a>
	</div>

	<ul class="nav">
		<li class="nav-item active">
			<a class="nav-link" href="{{url('/')}}">
				<i data-feather="pie-chart" class="menu-icon"></i>
				<span class="menu-title">Panel principal</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#ui-clients" aria-expanded="false" aria-controls="ui-basic">
				<i data-feather="user" class="menu-icon"></i>
				<span class="menu-title">Clientes</span>
				<i data-feather="chevron-right" class="menu-arrow"></i>
			</a>
			<div class="collapse" id="ui-clients">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"><a class="nav-link" href="{{url('clientes')}}">Clientes</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#ui-monitoring" aria-expanded="false" aria-controls="ui-basic">
				<i data-feather="monitor" class="menu-icon"></i>
				<span class="menu-title">Monitoreo</span>
				<i data-feather="chevron-right" class="menu-arrow"></i>
			</a>
			<div class="collapse" id="ui-monitoring">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"> <a class="nav-link" href="{{url('monitoreo')}}">Servicio Técnico Solicitdado</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#ui-users" aria-expanded="false" aria-controls="ui-basic">
				<i data-feather="users" class="menu-icon"></i>
				<span class="menu-title">Personal</span>
				<i data-feather="chevron-right" class="menu-arrow"></i>
			</a>
			<div class="collapse" id="ui-users">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"> <a class="nav-link" href="{{url('personal')}}">Personal</a></li>
					<li class="nav-item"> <a class="nav-link" href="{{url('departamentos')}}">Departamentos</a></li>
					<li class="nav-item"> <a class="nav-link" href="{{url('tipo-personal')}}">Tipo personal</a></li>
				</ul>
			</div>
		</li>
	</ul>
</nav>