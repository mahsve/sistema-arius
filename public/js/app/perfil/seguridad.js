(function () {
	// Elementos HTML.
	const formulario_contrasenas = document.getElementById("formulario_contrasenas");
	const btn_guardar_contrasena = document.getElementById("btn_guardar_contrasena");
	const formulario_preguntas = document.getElementById("formulario_preguntas");
	const btn_guardar_preguntas = document.getElementById("btn_guardar_preguntas");

	// Eventos HTML.
	// Formulario para actualizar contraseña.
	formulario_contrasenas.addEventListener("submit", (e) => {
		e.preventDefault();

		// Capturamos los elemetos del formulario.
		const nueva_contrasena = document.getElementById("nueva_contrasena");
		const repetir_contrasena = document.getElementById("repetir_contrasena");
		const actual_contrasena = document.getElementById("actual_contrasena");

		// Válidamos los campos.
		if (nueva_contrasena.value == "") {
			Toast.fire({ icon: 'error', title: 'Ingrese la nueva contraseña' });
			nueva_contrasena.focus();
		} else if (nueva_contrasena.value.length < 6) {
			Toast.fire({ icon: 'error', title: 'La nueva contraseña debe tener al menos 6 caracteres' });
			nueva_contrasena.focus();
		} else if (repetir_contrasena.value == "") {
			Toast.fire({ icon: 'error', title: 'Repita la nueva contraseña' });
			repetir_contrasena.focus();
		} else if (nueva_contrasena.value != repetir_contrasena.value) {
			Toast.fire({ icon: 'error', title: 'Las contraseñas no coincide' });
			repetir_contrasena.focus();
		} else if (actual_contrasena.value == "") {
			Toast.fire({ icon: 'error', title: 'Ingrese su contraseña actual para confirmar la acción' });
			actual_contrasena.focus();
		} else {
			btn_guardar_contrasena.classList.add("loading");
			fetch(`${formulario_contrasenas.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_contrasenas) }).then(response => response.json()).then(data => {
				btn_guardar_contrasena.classList.remove("loading");

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					return false;
				}

				// Enviamos mensaje de exito.
				Swal.fire({ title: "Exito", text: "Contraseña actualizada exitosamente", icon: "success", timer: 2000 });
				formulario_contrasenas.reset();
			});
		}
	});

	// Formulario para actualizar las preguntas de seguridad.
	formulario_preguntas.addEventListener("submit", (e) => {
		e.preventDefault();

		// Capturamos los elemetos del formulario.
		const pregunta_1 = document.getElementById("pregunta_1");
		const respuesta_1 = document.getElementById("respuesta_1");
		const pregunta_2 = document.getElementById("pregunta_2");
		const respuesta_2 = document.getElementById("respuesta_2");
		const actual_contrasena = document.getElementById("actual_contrasena2");

		// Válidamos los campos.
		if (pregunta_1.value == "") {
			Toast.fire({ icon: 'error', title: 'Ingrese la primera pregunta de seguridad' });
			pregunta_1.focus();
		} else if (respuesta_1.value == "") {
			Toast.fire({ icon: 'error', title: 'Ingrese la respuesta a su pregunta de seguridad' });
			respuesta_1.focus();
		} else if (pregunta_2.value == "") {
			Toast.fire({ icon: 'error', title: 'Ingrese la segunda pregunta de seguridad' });
			pregunta_2.focus();
		} else if (respuesta_2.value == "") {
			Toast.fire({ icon: 'error', title: 'Ingrese la respuesta a su pregunta de seguridad' });
			respuesta_2.focus();
		} else if (actual_contrasena.value == "") {
			Toast.fire({ icon: 'error', title: 'Ingrese su contraseña actual para confirmar la acción' });
			actual_contrasena.focus();
		} else {
			btn_guardar_preguntas.classList.add("loading");
			fetch(`${formulario_preguntas.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_preguntas) }).then(response => response.json()).then(data => {
				btn_guardar_preguntas.classList.remove("loading");

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					return false;
				}

				// Enviamos mensaje de exito.
				Swal.fire({ title: "Exito", text: "Preguntas de seguridad actualizadas exitosamente", icon: "success", timer: 2000 });
				pregunta_1.setAttribute("value", pregunta_1.value);
				pregunta_2.setAttribute("value", pregunta_2.value);
				formulario_preguntas.reset();
			});
		}
	});
})();