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
	</script>
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

	<!-- Base JS -->
	<script src="{{url('vendors/js/vendor.bundle.base.js')}}"></script>
	<script src="{{url('vendors/imask/dist/imask.min.js')}}"></script>
	<script src="{{url('libraries/sweetalert2/dist/sweetalert2.min.js')}}"></script>
	<!-- Default plugins JS -->
	<!-- <script src="{{url('js/off-canvas.js')}}"></script>
	<script src="{{url('js/hoverable-collapse.js')}}"></script>
	<script src="{{url('js/template.js')}}"></script>
	<script src="{{url('js/todolist.js')}}"></script> -->
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

		document.getElementById('dropdown-user-menu').addEventListener('click', (e) => {
			e.stopPropagation();
		})
	</script>
</body>

</html>