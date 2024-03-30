(function () {
	// Elementos HTML.
	const formulario_sesion = document.getElementById("formulario_sesion");
	const btn_sesion = document.getElementById("btn_sesion");

	// Eventos HTML.
	formulario_sesion.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos HTML.
		const usuario = document.getElementById("usuario");
		const contrasena = document.getElementById("contrasena");

		// Válidamos los campos del formulario.
		if (usuario.value == "") {
			Toast.fire({ icon: "error", title: "Ingrese su nombre de usuario" });
			usuario.focus();
		} else if (contrasena.value == "") {
			Toast.fire({ icon: "error", title: "Ingrese su contraseña" });
			contrasena.focus();
		} else {
			btn_sesion.classList.add("loading");
			fetch(`${formulario_sesion.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_sesion) }).then(response => response.json()).then(data => {
				btn_sesion.classList.remove("loading");

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					return false;
				}

				// Enviamos mensaje de exito.
				Swal.fire({
					title: "Exito",
					text: "¡Sesión iniciada exitosamente!",
					icon: "success", timer: 2000,
					willClose: () => location.reload(),
				});
			});
		}
	});
})();