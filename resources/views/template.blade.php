<!DOCTYPE html>
<html lang="es">

<head>
	<title>@yield('title')</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon -->
	<link rel="shortcut icon" href="{{url('images/' . env('FAVICON'))}}">
	<!-- Base CSS -->
	<link rel="stylesheet" href="{{url('vendors/css/vendor.bundle.base.css')}}">
	<!-- Libraries and plugings CSS -->
	@yield('styles')
	<!-- Dashboard CSS -->
	<link rel="stylesheet" href="{{url('css/vertical-layout-light/style.css')}}">
	<link rel="stylesheet" href="{{url('css/vertical-layout-light/theme.dark.css')}}">
</head>

<body class="sidebar-dark">
	<div class="container-scroller">
		<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
			<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
				<div class="me-3">
					<button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
						<i data-feather="menu"></i>
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
						<h1 class="welcome-text">Bienvenido <span class="text-black fw-bold">{{session('user')->usuario}}</span></h1>
						<h3 class="welcome-sub-text">Su resumen de desempeño esta semana</h3>
					</li>
				</ul>

				<ul class="navbar-nav ms-auto">
					<li class="nav-item">
						<form class="search-form" action="#">
							<i data-feather="search" class="menu-icon"></i>
							<input type="search" class="form-control" placeholder="Search Here" title="Search here">
						</form>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
							<i data-feather="message-square"></i>
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
					<li class="nav-item dropdown">
						<a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
							<i data-feather="bell"></i>
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
						<a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
							<img class="img-xs rounded-circle" src="{{url('images/faces/face8.jpg')}}" alt="Profile image">
						</a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown" style="min-width: 200px;">
							<div class="dropdown-header text-center">
								<img class="img-md rounded-circle" src="{{url('images/faces/face8.jpg')}}" alt="Profile image">
								<p class="mb-1 mt-3 font-weight-semibold">{{session('user')->usuario}}</p>
								<p class="fw-light text-muted mb-0">{{session('user')->cedula}}</p>
							</div>
							<a class="dropdown-item" href="{{url('perfil')}}"><i class="dropdown-item-icon icon-account-outline text-primary me-2"></i> Mi perfil</a>
							<a class="dropdown-item" href="{{url('logout')}}"><i class="dropdown-item-icon icon-logout text-primary me-2"></i>Cerrar sesión</a>
						</div>
					</li>
				</ul>

				<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
					<i data-feather="menu"></i>
				</button>
			</div>
		</nav>

		<div class="container-fluid page-body-wrapper px-0">
			<div id="right-sidebar" class="settings-panel">
				<i class="settings-close ti-close"></i>
				<ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="todo-tab" data-bs-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="chats-tab" data-bs-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
					</li>
				</ul>

				<div class="tab-content" id="setting-content">
					<div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
						<div class="add-items d-flex px-3 mb-0">
							<form class="form w-100">
								<div class="form-group d-flex">
									<input type="text" class="form-control todo-list-input" placeholder="Add To-do">
									<button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
								</div>
							</form>
						</div>
						<div class="list-wrapper px-3">
							<ul class="d-flex flex-column-reverse todo-list">
								<li>
									<div class="form-check">
										<label class="form-check-label">
											<input class="checkbox" type="checkbox">
											Team review meeting at 3.00 PM
										</label>
									</div>
									<i class="remove ti-close"></i>
								</li>
								<li>
									<div class="form-check">
										<label class="form-check-label">
											<input class="checkbox" type="checkbox">
											Prepare for presentation
										</label>
									</div>
									<i class="remove ti-close"></i>
								</li>
								<li>
									<div class="form-check">
										<label class="form-check-label">
											<input class="checkbox" type="checkbox">
											Resolve all the low priority tickets due today
										</label>
									</div>
									<i class="remove ti-close"></i>
								</li>
								<li class="completed">
									<div class="form-check">
										<label class="form-check-label">
											<input class="checkbox" type="checkbox" checked>
											Schedule meeting for next week
										</label>
									</div>
									<i class="remove ti-close"></i>
								</li>
								<li class="completed">
									<div class="form-check">
										<label class="form-check-label">
											<input class="checkbox" type="checkbox" checked>
											Project review
										</label>
									</div>
									<i class="remove ti-close"></i>
								</li>
							</ul>
						</div>
						<h4 class="px-3 text-muted mt-5 fw-light mb-0">Events</h4>
						<div class="events pt-4 px-3">
							<div class="wrapper d-flex mb-2">
								<i class="ti-control-record text-primary me-2"></i>
								<span>Feb 11 2018</span>
							</div>
							<p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
							<p class="text-gray mb-0">The total number of sessions</p>
						</div>
						<div class="events pt-4 px-3">
							<div class="wrapper d-flex mb-2">
								<i class="ti-control-record text-primary me-2"></i>
								<span>Feb 7 2018</span>
							</div>
							<p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
							<p class="text-gray mb-0 ">Call Sarah Graves</p>
						</div>
					</div>
					<!-- To do section tab ends -->
					<div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
						<div class="d-flex align-items-center justify-content-between border-bottom">
							<p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
							<small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 fw-normal">See All</small>
						</div>
						<ul class="chat-list">
							<li class="list active">
								<div class="profile"><img src="{{url('images/faces/face1.jpg')}}" alt="image"><span class="online"></span></div>
								<div class="info">
									<p>Thomas Douglas</p>
									<p>Available</p>
								</div>
								<small class="text-muted my-auto">19 min</small>
							</li>
							<li class="list">
								<div class="profile"><img src="{{url('images/faces/face2.jpg')}}" alt="image"><span class="offline"></span></div>
								<div class="info">
									<div class="wrapper d-flex">
										<p>Catherine</p>
									</div>
									<p>Away</p>
								</div>
								<div class="badge badge-success badge-pill my-auto mx-2">4</div>
								<small class="text-muted my-auto">23 min</small>
							</li>
							<li class="list">
								<div class="profile"><img src="{{url('images/faces/face3.jpg')}}" alt="image"><span class="online"></span></div>
								<div class="info">
									<p>Daniel Russell</p>
									<p>Available</p>
								</div>
								<small class="text-muted my-auto">14 min</small>
							</li>
							<li class="list">
								<div class="profile"><img src="{{url('images/faces/face4.jpg')}}" alt="image"><span class="offline"></span></div>
								<div class="info">
									<p>James Richardson</p>
									<p>Away</p>
								</div>
								<small class="text-muted my-auto">2 min</small>
							</li>
							<li class="list">
								<div class="profile"><img src="{{url('images/faces/face5.jpg')}}" alt="image"><span class="online"></span></div>
								<div class="info">
									<p>Madeline Kennedy</p>
									<p>Available</p>
								</div>
								<small class="text-muted my-auto">5 min</small>
							</li>
							<li class="list">
								<div class="profile"><img src="{{url('images/faces/face6.jpg')}}" alt="image"><span class="online"></span></div>
								<div class="info">
									<p>Sarah Graves</p>
									<p>Available</p>
								</div>
								<small class="text-muted my-auto">47 min</small>
							</li>
						</ul>
					</div>
					<!-- chat tab ends -->
				</div>
			</div>

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

			<div class="main-panel">
				<div class="content-wrapper px-4 pt-4 pb-0">
					@yield('content')
				</div>

				<footer class="footer">
					<div class="d-sm-flex justify-content-center justify-content-sm-between">
						<span class="text-center text-sm-left d-block d-sm-inline-block">{{env('TITLE')}}</span>
						<span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Derechos de autor © <?php date('Y') ?>. Todos los derechos reservados.</span>
					</div>
				</footer>
			</div>
		</div>
	</div>

	<!-- Base JS -->
	<script src="{{url('vendors/js/vendor.bundle.base.js')}}"></script>
	<!-- Default plugins JS -->
	<script src="{{url('js/off-canvas.js')}}"></script>
	<script src="{{url('js/hoverable-collapse.js')}}"></script>
	<script src="{{url('js/template.js')}}"></script>
	<script src="{{url('js/todolist.js')}}"></script>
	<!-- Libraries and plugings JS -->
	@yield('scripts')
	<!-- Icon JS -->
	<script src="{{url('icons/feather.min.js')}}"></script>
	<script>
		feather.replace();
	</script>
</body>

</html>