(function () {
	// Elementos HTML.
	const formulario_recuperar = document.getElementById("formulario_recuperar");

	// Eventos HTML.
	formulario_recuperar.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos HTML.
		const usuario = document.getElementById("usuario");
		const btn_submit = document.getElementById("btn_submit");

		// Válidamos los campos del formulario.
		if (usuario.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese su nombre de usuario!" });
			usuario.focus();
		} else {
			btn_submit.classList.add("loading");
			btn_submit.setAttribute('disabled', true);
			fetch(`${formulario_recuperar.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_recuperar) }).then(response => response.json()).then(data => {
				btn_submit.classList.remove("loading");
				btn_submit.removeAttribute('disabled');

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					if (!data.response.type)
						Toast.fire({ icon: data.status, title: data.response.message });
					else {
						Swal.fire({ icon: data.status, title: data.response.title, html: data.response.message });
						usuario.value = "";
					}
					return;
				}

				// Enviamos mensaje de exito.
				Swal.fire({
					title: "Exito",
					icon: data.status,
					text: data.response.message,
					timer: 2000,
					willClose: () => location.href = `${url_}/preguntas_seguridad`,
				});
			});
		}
	});
})();