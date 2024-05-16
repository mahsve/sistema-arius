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
	<link rel="stylesheet" href="{{url('libraries/fontawesome/css/all.min.css')}}">
	<link rel="stylesheet" href="{{url('libraries/sweetalert2/dist/sweetalert2.min.css')}}">
	@yield('styles')
	<!-- Dashboard CSS -->
	<link rel="stylesheet" href="{{url('css/app/style.css')}}">
	<link rel="stylesheet" href="{{url('css/app/theme.dark.css')}}">
	<link rel="stylesheet" href="{{url('css/personalizacion.css')}}">
</head>

<body class="sidebar-dark">
	<div class="container-scroller">
		<!-- Navbar -->
		@include('componentes.navbar')

		<div class="container-fluid page-body-wrapper px-0">
			<!-- Sidebar Todos -->
			@include('componentes.todos')

			<!-- Sidebar Menu -->
			@include('componentes.sidebar')

			<div class="main-panel">
				<div class="content-wrapper px-4 pt-4 pb-0">
					@yield('content')
				</div>

				<footer class="footer bg-white">
					<div class="d-sm-flex justify-content-center justify-content-sm-between">
						<span class="text-center text-sm-left d-block d-sm-inline-block">{{env('TITLE')}}</span>
						<span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Derechos de autor © <?php date('Y') ?>. Todos los derechos reservados.</span>
					</div>
				</footer>
			</div>
		</div>
	</div>

	<!-- REGISTRAR TAREA -->
	<div class="modal fade" id="modal_tarea" tabindex="-1" aria-labelledby="modal_tarea_label" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header border-0 pb-0">
					<h1 class="modal-title text-uppercase fs-5" id="modal_tarea_label"><i class="fas fa-tasks"></i> Nueva tarea</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body py-3">
					<form class="forms-sample" name="formulario_registrar_tarea" id="formulario_registrar_tarea" method="POST" action="{{route('tareas.store')}}">
						@csrf
						<div class="form-group mb-2">
							<label for="c_titulo_r" class="required"><i class="fas fa-heading"></i> Titulo</label>
							<input type="text" class="form-control text-uppercase" name="c_titulo_r" id="c_titulo_r" placeholder="Ingrese el titulo de la tarea" minlength="3" required>
						</div>
						<div class="form-group mb-2">
							<label for="c_fecha_r"><i class="fas fa-calendar-day"></i> Fecha tope</label>
							<input type="date" class="form-control text-uppercase" name="c_fecha" id="c_fecha_r">
						</div>
						<div class="form-group">
							<label for="c_descripcion_r" class="required"><i class="fas fa-sticky-note"></i> Descripción</label>
							<textarea class="form-control text-uppercase" name="c_descripcion" id="c_descripcion_r" placeholder="Ingrese la descripción"></textarea>
						</div>
						<input type="hidden" name="id_usuario" value="{{auth()->user()->idusuario}}">
						<div class="text-end">
							<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
							<button type="submit" class="btn btn-primary btn-sm" id="btn_registrar_tarea"><i class="fas fa-save me-2"></i>Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Base JS -->
	<script src="{{url('vendors/js/vendor.bundle.base.js')}}"></script>
	<script src="{{url('vendors/imask/dist/imask.min.js')}}"></script>
	<script src="{{url('libraries/sweetalert2/dist/sweetalert2.min.js')}}"></script>
	<script src="{{url('js/app/tareas/gestion.js')}}"></script>
	<!-- Default plugins JS -->
	<script src="{{url('js/core/off-canvas.js')}}"></script>
	<script src="{{url('js/core/hoverable-collapse.js')}}"></script>
	<script src="{{url('js/core/template.js')}}"></script>
	<script src="{{url('js/core/todolist.js')}}"></script>
	<?php /*
	*/ ?>
	<script id="contenedor_script_variables">
		const url_ = '{{url("/")}}';
		const token_ = '{{csrf_token()}}';
		document.getElementById('contenedor_script_variables').remove();
	</script>
	<!-- Libraries and plugings JS -->
	@yield('scripts')
	<script>
		const Toast = Swal.mixin({
			toast: true,
			position: "top-end",
			showConfirmButton: false,
			timer: 3000,
			timerProgressBar: true,
			didOpen: (toast) => {
				toast.onmouseenter = Swal.stopTimer;
				toast.onmouseleave = Swal.resumeTimer;
			}
		});

		// Opción para deshabilitar el cerrar el dropdown a la hora de clickear la información del usuario en el navbar.
		document.getElementById('dropdown-user-menu').addEventListener('click', (e) => {
			e.stopPropagation();
		});

		// Agregamos el evento para cerrer sesión con fetch. 
		document.getElementById('btn_cerrar_sesion').addEventListener('click', function(e) {
			e.preventDefault();

			const btn_sesion = this;

			btn_sesion.classList.add("loading");
			fetch(`${url_}/cerrar_sesion`).then(response => response.json()).then(data => {
				btn_sesion.classList.remove("loading");

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({
						icon: data.status,
						title: data.response.message
					});
					return false;
				}

				// Enviamos mensaje de exito.
				Swal.fire({
					title: "Exito",
					text: "¡Sesión cerrada exitosamente!",
					icon: "success",
					timer: 2000,
					willClose: () => location.reload(),
				});
			});
		});

		// Botón para cerrar y abrir el sidebar en la versión móvil.
		document.getElementById('btn_toggle_sidebar').addEventListener('click', function(e) {
			e.preventDefault();
			document.getElementById('sidebar').classList.toggle('active');
		});

		// right-sidebar
		if (false) {
			document.getElementById("btn_toggle_todo").addEventListener('click', function(e) {
				e.preventDefault();
				const sidebar_todo = document.getElementById("right-sidebar");
				console.log(sidebar_todo.style.right);
				if (!sidebar_todo.style.right || sidebar_todo.style.right != '0px' || sidebar_todo.style.right == "") {
					sidebar_todo.style.right = 0;
				} else {
					sidebar_todo.style.right = null;
				}
			});
		}
	</script>
</body>

</html>