(function () {
	// Elementos HTML.
	const formulario_registro = document.getElementById("formulario_registro");
	const c_tipo_identificacion_ = document.getElementById("c_tipo_identificacion");
	const btn_guardar = document.getElementById("btn_guardar");

	// Mascaras.
	var identificacionMask = IMask(document.getElementById('c_identificacion'), { mask: '00000000' });
	const telefono1Mask = IMask(document.getElementById('c_telefono1'), { mask: '000-0000' });
	const telefono2Mask = IMask(document.getElementById('c_telefono2'), { mask: '000-0000' });

	// Eventos elementos HTML.
	c_tipo_identificacion_.addEventListener("change", function () {
		const label_ = document.querySelector('#contenedor_identificacion label');
		const input_ = document.querySelector('#contenedor_identificacion input');
		const selec_ = document.querySelector('#contenedor_identificacion select');
		selec_.innerHTML = '';
		identificacionMask.destroy();
		if (this.value == "C") {
			label_.innerHTML = '<i class="fas fa-id-badge"></i> Cédula';
			input_.setAttribute("placeholder", "Ingrese la cédula");
			lista_cedula.forEach(text => selec_.innerHTML += `<option value="${text}">${text}</option>`);
			identificacionMask = IMask(document.getElementById('c_identificacion'), { mask: '00000000' });
		} else if (this.value == "R") {
			label_.innerHTML = '<i class="fas fa-id-badge"></i> RIF';
			input_.setAttribute("placeholder", "Ingrese el RIF");
			lista_rif.forEach(text => selec_.innerHTML += `<option value="${text}">${text}</option>`);
			identificacionMask = IMask(document.getElementById('c_identificacion'), { mask: '00000000-0' });
		}
	});

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
					text: "Cliente registrado exitosamente",
					icon: "success",
					timer: 2000,
					willClose: () => location.href = `${url_}/clientes`,
				});
			});
		}
	});

	// Ejecutamos los eventos necesarios [Una sola vez].
	c_tipo_identificacion_.dispatchEvent(new Event('change'));
})();