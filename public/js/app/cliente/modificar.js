(function () {
	// Elementos HTML.
	const formulario_registro = document.getElementById("formulario_registro");
	const btn_guardar = document.getElementById("btn_guardar");

	// Mascaras.
	const telefono1Mask = IMask(document.getElementById('c_telefono1'), { mask: '000-0000' });
	const telefono2Mask = IMask(document.getElementById('c_telefono2'), { mask: '000-0000' });

	// Enviar formulario.
	formulario_registro.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos del formulario.
		const c_tipo_identificacion = document.getElementById("c_tipo_identificacion");
		const c_identificacion = document.getElementById("c_identificacion");
		const c_nombre_completo = document.getElementById("c_nombre_completo");
		const c_telefono1 = document.getElementById("c_telefono1");
		const c_telefono2 = document.getElementById("c_telefono2");
		const c_correo_electronico = document.getElementById("c_correo_electronico");
		const c_direccion = document.getElementById("c_direccion");
		const c_referencia = document.getElementById("c_referencia");

		// Validamos los campos.
		if (false) {

		} else {
			btn_guardar.classList.add("loading");
			fetch(`${formulario_registro.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro) }).then(response => response.json()).then(data => {
				btn_guardar.classList.remove("loading");

				console.log(data);

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({
						icon: data.status,
						title: data.response.message
					});
					return false;
				}

				// Enviamos mensaje de exito.
				Swal.fire({
					title: "Exito",
					text: "Cliente modificado exitosamente",
					icon: "success",
					timer: 2000
				});

				setTimeout(() => location.href = `${url_}/clientes`, 2000);
			});
		}
	});
})();