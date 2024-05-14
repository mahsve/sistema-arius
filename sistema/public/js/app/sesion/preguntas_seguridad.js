(function () {
	const formulario_preguntas = document.getElementById("formulario_preguntas");
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

	// Enviar el formulario con la nueva contraseña.
	formulario_preguntas.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos HTML.
		const respuesta1 = document.getElementById("respuesta1");
		const respuesta2 = document.getElementById("respuesta2");
		const btn_submit = document.getElementById("btn_submit");

		// Válidamos los campos del formulario.
		if (respuesta1.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese la respuesta de la primera pregunta!" });
			respuesta1.focus();
		} else if (respuesta2.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese la respuesta de la segunda pregunta!" });
			respuesta2.focus();
		} else {
			btn_submit.classList.add("loading");
			btn_submit.setAttribute('disabled', true);
			fetch(`${formulario_preguntas.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_preguntas) }).then(response => response.json()).then(data => {
				btn_submit.classList.remove("loading");
				btn_submit.removeAttribute('disabled');

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					if (!data.response.type)
						Toast.fire({ icon: data.status, title: data.response.message });
					else
						Swal.fire({ icon: data.status, title: data.response.title, text: data.response.message });
					if (data.response.reload) location.reload();
					return;
				}

				// Enviamos mensaje de exito.
				Swal.fire({
					title: "Exito",
					icon: data.status,
					text: data.response.message,
					timer: 2000,
					willClose: () => location.href = `${url_}/restablecer`,
				});
			});
		}
	});
})();