<!DOCTYPE html>
<html lang="es">

<head>
	<title>Arius Seguridad Integral</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- plugins:css -->
	<link rel="stylesheet" href="../../vendors/feather/feather.css">
	<link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
	<link rel="stylesheet" href="../../vendors/typicons/typicons.css">
	<link rel="stylesheet" href="../../vendors/simple-line-icons/css/simple-line-icons.css">
	<link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
	<!-- endinject -->
	<!-- Plugin css for this page -->
	<!-- End plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
	<!-- endinject -->
	<link rel="shortcut icon" href="../../images/favicon.png" />
</head>

<body>
	<div class="container-scroller">
		<div class="container-fluid page-body-wrapper full-page-wrapper">
			<div class="content-wrapper d-flex align-items-center auth px-0">
				<div class="row w-100 mx-0">
					<div class="col-lg-4 mx-auto">
						<div class="auth-form-light text-left py-5 px-4 px-sm-5">
							<div class="brand-logo text-center">
								<img src="../../images/logo.svg" alt="logo">
							</div>
							<h4>Bienvenido a su panel administrativo</h4>
							<h6 class="fw-light">Inicie sesión para continuar</h6>

							<form name="form-login" id="form-login" class="pt-3" method="post" action="login">
								<div class="form-group">
									<input type="text" class="form-control form-control-lg px-3" name="username" id="username" placeholder="Ingrese su nombre de usuario" minlength="6" required>
								</div>
								<div class="form-group">
									<input type="password" class="form-control form-control-lg px-3" name="password" id="password" placeholder="Ingrese su contraseña" minlength="6" required>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-lg w-100 font-weight-medium auth-form-btn">Iniciar sesión</button>
								</div>
								<div class="text-end">
									<a href="recuperar-cuenta" class="auth-link text-black">¡Olvidé mi contraseña!</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- content-wrapper ends -->
		</div>
		<!-- page-body-wrapper ends -->
	</div>
	<!-- container-scroller -->
	<!-- plugins:js -->
	<script src="../../vendors/js/vendor.bundle.base.js"></script>
	<!-- endinject -->
	<!-- Plugin js for this page -->
	<script src="../../vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<!-- End plugin js for this page -->
	<!-- inject:js -->
	<script src="../../js/off-canvas.js"></script>
	<script src="../../js/hoverable-collapse.js"></script>
	<script src="../../js/template.js"></script>
	<script src="../../js/settings.js"></script>
	<script src="../../js/todolist.js"></script>
	<!-- endinject -->
</body>

</html>