(function () {
	// Elementos HTML.
	const formulario_perfil = document.getElementById("formulario_perfil");
	const btn_guardar = document.getElementById("btn_guardar");

	// Mascaras.
	IMask(document.getElementById('c_telefono1'), { mask: '000-0000' });
	IMask(document.getElementById('c_telefono2'), { mask: '000-0000' });
	const validar_correo = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

	// Enviar formulario.
	formulario_perfil.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos del formulario.
		const c_nombre_completo = document.getElementById("c_nombre_completo");
		const c_prefijo_telefono1 = document.getElementById("c_prefijo_telefono1");
		const c_telefono1 = document.getElementById("c_telefono1");
		const c_prefijo_telefono2 = document.getElementById("c_prefijo_telefono2");
		const c_telefono2 = document.getElementById("c_telefono2");
		const c_correo_electronico = document.getElementById("c_correo_electronico");
		const c_direccion = document.getElementById("c_direccion");

		// Validamos los campos.
		if (c_nombre_completo.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese el nombre completo!" });
			c_nombre_completo.focus();
		} else if (c_nombre_completo.value.length < 10) {
			Toast.fire({ icon: "error", title: "¡El nombre debe tener al menos 10 caracteres!" });
			c_nombre_completo.focus();
		} else if (c_prefijo_telefono1.value == "") {
			Toast.fire({ icon: "error", title: "¡Seleccione el código del primer teléfono!" });
			c_prefijo_telefono1.focus();
		} else if (c_telefono1.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese el número del primer teléfono!" });
			c_telefono1.focus();
		} else if (c_telefono1.value.length < 8) {
			Toast.fire({ icon: "error", title: "¡Ingrese el número del primer teléfono completo!" });
			c_telefono1.focus();
		} else if (c_telefono2.value != "" && c_prefijo_telefono2.value == "") {
			Toast.fire({ icon: "error", title: "¡Seleccione el código del segundo teléfono!" });
			c_prefijo_telefono2.focus();
		} else if (c_prefijo_telefono2.value != "" && c_telefono2.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese el número del segundo teléfono!" });
			c_telefono2.focus();
		} else if (c_prefijo_telefono2.value != "" && c_telefono2.value.length < 8) {
			Toast.fire({ icon: "error", title: "¡Ingrese el número del segundo teléfono completo!" });
			c_telefono2.focus();
		} else if (c_correo_electronico.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese el correo electrónico!" });
			c_correo_electronico.focus();
		} else if (!validar_correo.test(c_correo_electronico.value)) {
			Toast.fire({ icon: "error", title: "¡Ingrese un correo electrónico válido!\nEj: usuario@email.com" });
			c_correo_electronico.focus();
		} else if (c_direccion.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese la dirección física!" });
			c_direccion.focus();
		} else if (c_direccion.value.length < 10) {
			Toast.fire({ icon: "error", title: "¡La dirección debe tener al menos 10 caracteres!" });
			c_direccion.focus();
		} else {
			btn_guardar.classList.add("loading");
			btn_guardar.setAttribute('disabled', true);
			fetch(`${formulario_perfil.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_perfil) }).then(response => response.json()).then(data => {
				btn_guardar.classList.remove("loading");
				btn_guardar.removeAttribute('disabled');

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					return;
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