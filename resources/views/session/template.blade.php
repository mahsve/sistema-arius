<!DOCTYPE html>
<html lang="es">

<head>
	<title>@yield('title')</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon -->
	<link rel="shortcut icon" href="{{url('images/' . env('FAVICON'))}}">
	<!-- BASE CSS -->
	<link rel="stylesheet" href="{{url('vendors/css/vendor.bundle.base.css')}}">
	<!-- CUSTOM CSS -->
	<link rel="stylesheet" href="{{url('css/vertical-layout-light/style.css')}}">
</head>

<body>
	<div class="container-scroller">
		<div class="container-fluid page-body-wrapper full-page-wrapper">
			<div class="content-wrapper d-flex align-items-center auth px-0">
				<div class="row w-100 mx-0">
					<div class="col-lg-4 mx-auto">
						<div class="auth-form-light py-5 px-4 px-sm-5 border rounded">
							<div class="brand-logo text-center">
								<a href="{{url('iniciar-sesion')}}" class="d-block">
									<img src="{{url('images/' . env('LOGO_DARK'))}}" alt="Logo {{env('TITLE')}}" class="w-50">
								</a>
							</div>

							@yield('content')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Base JS -->
	<script src="../../vendors/js/vendor.bundle.base.js"></script>
	<!-- <script src="../../js/off-canvas.js"></script>
	<script src="../../js/hoverable-collapse.js"></script>
	<script src="../../js/template.js"></script>
	<script src="../../js/todolist.js"></script> -->
	<!-- endinject -->

	<!-- ICON JS -->
	<script src="{{url('icons/feather.min.js')}}"></script>
	<script>feather.replace();</script>
</body>

</html>