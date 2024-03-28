<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
	<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
		<div class="me-3">
			<button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
				<i class="fas fa-bars"></i>
			</button>
		</div>
		<div>
			<a class="navbar-brand brand-logo" href="{{url('/')}}">
				<img src="{{url('images/' . env('LOGO_LIGHT'))}}" alt="Logo {{env('TITLE')}}" style="filter: invert(1);">
			</a>
			<a class="navbar-brand brand-logo-mini" href="{{url('/')}}">
				<img src="{{url('images/' . env('LOGO_MINI'))}}" alt="Logo {{env('TITLE')}}">
			</a>
		</div>
	</div>

	<div class="navbar-menu-wrapper d-flex align-items-top border-bottom px-4 bg-white">
		<ul class="navbar-nav">
			<li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
				<h1 class="welcome-text">Bienvenido <span class="text-black fw-bold">{{session('usuario')->nombre}}</span></h1>
				<h3 class="welcome-sub-text">Su resumen de desempeño esta semana</h3>
			</li>
		</ul>

		<ul class="navbar-nav ms-auto">
			<!-- Search panel -->
			<li class="nav-item">
				<form class="search-form" action="#">
					<i class="menu-icon fas fa-search"></i>
					<input type="search" class="form-control" placeholder="Search Here" title="Search here">
				</form>
			</li>

			<!-- Messages -->
			<li class="nav-item dropdown">
				<a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="fas fa-inbox"></i>
					<span class="count"></span>
				</a>
				<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-2 rounded" aria-labelledby="countDropdown">
					<span class="dropdown-item py-3 bg-transparent">
						<p class="mb-0 font-weight-medium float-left">You have 7 unread mails </p>
						<span class="badge badge-pill badge-primary float-right">View all</span>
					</span>
					<div class="dropdown-divider mb-2"></div>
					<a class="dropdown-item preview-item">
						<div class="preview-thumbnail">
							<img src="{{url('images/faces/face10.jpg')}}" alt="image" class="img-sm profile-pic">
						</div>
						<div class="preview-item-content flex-grow py-2">
							<p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>
							<p class="fw-light small-text mb-0"> The meeting is cancelled </p>
						</div>
					</a>
					<a class="dropdown-item preview-item">
						<div class="preview-thumbnail">
							<img src="{{url('images/faces/face12.jpg')}}" alt="image" class="img-sm profile-pic">
						</div>
						<div class="preview-item-content flex-grow py-2">
							<p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>
							<p class="fw-light small-text mb-0"> The meeting is cancelled </p>
						</div>
					</a>
					<a class="dropdown-item preview-item">
						<div class="preview-thumbnail">
							<img src="{{url('images/faces/face1.jpg')}}" alt="image" class="img-sm profile-pic">
						</div>
						<div class="preview-item-content flex-grow py-2">
							<p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>
							<p class="fw-light small-text mb-0"> The meeting is cancelled </p>
						</div>
					</a>
				</div>
			</li>

			<!-- Notifications -->
			<li class="nav-item dropdown">
				<a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
					<i class="fas fa-bell"></i>
					<span class="count"></span>
				</a>
				<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-2 rounded" aria-labelledby="notificationDropdown">
					<span class="dropdown-item py-3 bg-transparent">
						<p class="mb-0 font-weight-medium float-left">You have 4 new notifications </p>
						<span class="badge badge-pill badge-primary float-right">View all</span>
					</span>
					<div class="dropdown-divider mb-2"></div>
					<a class="dropdown-item preview-item py-3">
						<div class="preview-thumbnail">
							<i class="icon-alert m-auto text-primary"></i>
						</div>
						<div class="preview-item-content">
							<h6 class="preview-subject fw-normal text-dark mb-1">Application Error</h6>
							<p class="fw-light small-text mb-0"> Just now </p>
						</div>
					</a>
					<a class="dropdown-item preview-item py-3">
						<div class="preview-thumbnail">
							<i class="icon-settings m-auto text-primary"></i>
						</div>
						<div class="preview-item-content">
							<h6 class="preview-subject fw-normal text-dark mb-1">Settings</h6>
							<p class="fw-light small-text mb-0"> Private message </p>
						</div>
					</a>
					<a class="dropdown-item preview-item py-3">
						<div class="preview-thumbnail">
							<i class="mdi mdi-airballoon m-auto text-primary"></i>
						</div>
						<div class="preview-item-content">
							<h6 class="preview-subject fw-normal text-dark mb-1">New user registration</h6>
							<p class="fw-light small-text mb-0"> 2 days ago </p>
						</div>
					</a>
				</div>
			</li>

			<li class="nav-item dropdown">
				<a class="nav-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-expanded="false">
					<img class="img-xs rounded-circle" src="{{url('images/faces/face8.jpg')}}" alt="Imagen usuario">
				</a>
				<div id="dropdown-user-menu" class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-2 rounded mt-3" aria-labelledby="dropdown-user" style="min-width: 220px;">
					<div class="dropdown-header text-center mb-2">
						<img class="img-md rounded-circle w-50 mt-2" src="{{url('images/faces/face8.jpg')}}" alt="Imagen usuario">
						<p class="mb-1 mt-3 font-weight-semibold">{{session('usuario')->nombre}}</p>
						<p class="fw-light text-muted mb-0">{{session('usuario')->usuario}}</p>
					</div>
					<a class="dropdown-item px-2" href="{{url('perfil')}}"><i class="fas fa-user ms-2 me-2" width="18px" height="18px"></i> Mi perfil</a>
					<a class="dropdown-item px-2" href="{{url('cerrar_sesion')}}"><i class="fas fa-sign-out-alt ms-2 me-2" width="18px" height="18px"></i> Cerrar sesión</a>
				</div>
			</li>
		</ul>

		<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
			<i class="fas fa-bars"></i>
		</button>
	</div>
</nav>