<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
	<div class="text-center navbar-brand-wrapper d-flex align-items-center">
		<div class="ps-4 ps-lg-0 text-start text-lg-center w-100">
			<a class="navbar-brand brand-logo d-none d-sm-block d-lg-none" href="{{url('/')}}">
				<img src="{{url('images/' . env('LOGO_LIGHT'))}}" alt="Logo {{env('TITLE')}}">
			</a>
			<a class="navbar-brand brand-logo d-none d-sm-none d-lg-block" href="{{url('/')}}">
				<img src="{{url('images/' . env('LOGO_DARK'))}}" alt="Logo {{env('TITLE')}}">
			</a>
			<a class="navbar-brand brand-logo d-block d-sm-none" href="{{url('/')}}">
				<img src="{{url('images/' . env('LOGO_MINI'))}}" alt="Logo {{env('TITLE')}}" class="w-auto rounded">
			</a>
		</div>
	</div>

	<div class="navbar-menu-wrapper d-flex align-items-top border-bottom px-4 bg-white">
		<ul class="navbar-nav">
			<li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
				<h1 class="welcome-text">Bienvenido <span class="text-black fw-bold">{{session('personal')->nombre}}</span></h1>
				@if ($_SERVER['REQUEST_URI'] == '/')
				<h3 class="welcome-sub-text">Su resumen de desempeño esta semana</h3>
				@endif
			</li>
		</ul>

		<ul class="navbar-nav ms-auto">
			<li class="nav-item dropdown">
				<a href="javascript:void(0);" id="btn_toggle_todo"><i class="fas fa-clipboard-list"></i></a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-expanded="false">
					<img class="img-xs rounded-circle border" src="{{url('images/user-default.jpg')}}" alt="Imagen usuario">
				</a>
				<div id="dropdown-user-menu" class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-2 rounded mt-3" aria-labelledby="dropdown-user" style="min-width: 220px;">
					<div class="dropdown-header text-center mb-2">
						<img class="img-md rounded-circle w-50 mt-2" src="{{url('images/user-default.jpg')}}" alt="Imagen usuario">
						<p class="mb-1 mt-3 fw-bold">{{session('personal')->nombre}}</p>
						<p class="fw-bold text-muted mb-0">{{auth()->user()->usuario}}</p>
					</div>
					<a class="dropdown-item px-2" href="{{route('profile.index')}}"><i class="fas fa-user-circle mx-2"></i> Mi perfil</a>
					<a class="dropdown-item px-2" href="{{route('security.index')}}"><i class="fas fa-user-shield mx-2"></i> Seguridad</a>
					<button type="button" class="dropdown-item px-2" id="btn_cerrar_sesion"><i class="fas fa-sign-out-alt mx-2"></i> Cerrar sesión</button>
				</div>
			</li>
		</ul>

		<button type="button" class="btn btn-secondary btn-sm d-flex d-lg-none align-self-center ms-3" id="btn_toggle_sidebar">
			<i class="fas fa-bars"></i>
		</button>
	</div>
</nav>