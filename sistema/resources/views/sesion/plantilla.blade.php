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

<body>
	<div class="container-scroller">
		<div class="container-fluid page-body-wrapper full-page-wrapper">
			<div class="content-wrapper d-flex align-items-center auth px-0">
				<div class="row w-100 mx-0">
					<div class="col-lg-4 mx-auto">
						<div class="auth-form-light py-5 px-4 px-sm-5 border rounded">
							<!-- LOGO -->
							<div class="brand-logo text-center">
								<a href="{{route('session.login')}}" class="d-block">
									<img src="{{url('images/' . env('LOGO_LIGHT'))}}" alt="Logo {{env('TITLE')}}" class="w-50">
								</a>
							</div>

							<!-- CONTENIDO -->
							@yield('content')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Base JS -->
	<script src="{{url('vendors/js/vendor.bundle.base.js')}}"></script>
	<script src="{{url('libraries/sweetalert2/dist/sweetalert2.min.js')}}"></script>
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
	</script>
</body>

</html>