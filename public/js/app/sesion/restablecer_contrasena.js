(function () {
	// Elementos HTML.
	const formulario_contrasenas = document.getElementById("formulario_contrasenas");
	const btn_toggle_pw = document.querySelectorAll(".toggle-password");

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

	// Eventos HTML.
	formulario_contrasenas.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos HTML.
		const contrasena1 = document.getElementById("contrasena1");
		const contrasena2 = document.getElementById("contrasena2");
		const btn_submit = document.getElementById("btn_submit");

		// Válidamos los campos del formulario.
		if (contrasena1.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese su nueva contraseña!" });
			contrasena1.focus();
		} else if (contrasena1.value.length < 6) {
			Toast.fire({ icon: "error", title: "¡La contraseña debe tener al menos 6 caracteres!" });
			contrasena1.focus();
		} else if (contrasena2.value == "") {
			Toast.fire({ icon: "error", title: "¡Por favor repita la contraseña!" });
			contrasena2.focus();
		} else if (contrasena1.value != contrasena2.value) {
			Toast.fire({ icon: "error", title: "¡Las contraseñas ingresadas no coinciden!" });
			contrasena1.focus();
		} else {
			btn_submit.classList.add("loading");
			btn_submit.setAttribute('disabled', true);
			fetch(`${formulario_contrasenas.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_contrasenas) }).then(response => response.json()).then(data => {
				btn_submit.classList.remove("loading");
				btn_submit.removeAttribute('disabled');

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					if (data.response.reload) location.reload();
					return;
				}

				// Enviamos mensaje de exito.
				Swal.fire({
					title: "Exito",
					icon: data.status,
					text: data.response.message,
					timer: 2000,
					willClose: () => location.href = `${url_}/iniciar_sesion`,
				});
			});
		}
	});
})();