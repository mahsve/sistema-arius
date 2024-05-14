(function () {
	// Elementos HTML.
	const formulario_sesion = document.getElementById("formulario_sesion");
	const btn_toggle_pw = document.querySelectorAll(".toggle-password");

	// Eventos HTML.
	// Mostrar/Ocultar contraseña
	Array.from(btn_toggle_pw).forEach(btn => {
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			const button = this;
			const idtoggle = button.getAttribute('data-toggle');
			const input_ = document.getElementById(idtoggle);
			if (input_.getAttribute('type') == "password") {
				input_.setAttribute('type', "text");
				button.classList.remove('fa-eye', 'fa-eye-slash');
				button.classList.add('fa-eye-slash');
			} else {
				input_.setAttribute('type', "password");
				button.classList.remove('fa-eye', 'fa-eye-slash');
				button.classList.add('fa-eye');
			}
		});
	});

	// Enviar formulario.
	formulario_sesion.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos HTML.
		const usuario = document.getElementById("usuario");
		const contrasena = document.getElementById("contrasena");
		const btn_submit = document.getElementById("btn_submit");

		// Válidamos los campos del formulario.
		if (usuario.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese su nombre de usuario!" });
			usuario.focus();
		} else if (contrasena.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese su contraseña!" });
			contrasena.focus();
		} else {
			btn_submit.classList.add("loading");
			btn_submit.setAttribute('disabled', true);
			fetch(`${formulario_sesion.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_sesion) }).then(response => response.json()).then(data => {
				btn_submit.classList.remove("loading");
				btn_submit.removeAttribute('disabled');

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					if (!data.response.type)
						Toast.fire({ icon: data.status, title: data.response.message });
					else
						Swal.fire({ icon: data.status, title: data.response.title, text: data.response.message });
					return false;
				}

				// Enviamos mensaje de exito.
				Swal.fire({
					title: "Exito",
					icon: data.status,
					text: data.response.message,
					timer: 2000,
					willClose: () => location.reload(),
				});
			});
		}
	});
})();