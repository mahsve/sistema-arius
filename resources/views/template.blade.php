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
		<!-- Navbar -->
		@include('components.navbar')

		<div class="container-fluid page-body-wrapper px-0">
			<!-- Sidebar Todos -->
			@include('components.todos')

			<!-- Sidebar Menu -->
			@include('components.sidebar')

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