(function () {
	// Elementos HTML.
	const formulario_registro = document.getElementById("formulario_registro");
	const c_departamento_ = document.getElementById("c_departamento");
	const c_cargo_ = document.getElementById("c_cargo");
	const btn_guardar = document.getElementById("btn_guardar");

	// Mascaras.
	var identificacionMask = IMask(document.getElementById('c_identificacion'), { mask: '00000000' });
	const telefono1Mask = IMask(document.getElementById('c_telefono1'), { mask: '000-0000' });
	const telefono2Mask = IMask(document.getElementById('c_telefono2'), { mask: '000-0000' });

	// Eventos elementos HTML.
	c_departamento_.addEventListener('change', function () {
		c_cargo_.innerHTML = '<option value="">Seleccione una opción</option>';
		if (this.value != "") {
			c_cargo_.parentElement.classList.add("loading");
			fetch(`${url_}/personal/consultar_cargos/${this.value}`).then(response => response.json()).then(data => {
				c_cargo_.parentElement.classList.remove("loading");
				if (data.length == 0) {
					c_cargo_.innerHTML = '<option value="">Sin cargos registrados</option>';
					return ;
				}
				for (let i = 0; i < data.length; i++) {
					c_cargo_.innerHTML += `<option value="${data[i].idcargo}">${data[i].cargo}</option>`;
				}
			});
		}
	});

	// Enviar formulario.
	formulario_registro.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos del formulario.
		const c_identificacion = document.getElementById("c_identificacion");
		const c_nombre_completo = document.getElementById("c_nombre_completo");
		const c_telefono1 = document.getElementById("c_telefono1");
		const c_telefono2 = document.getElementById("c_telefono2");
		const c_correo_electronico = document.getElementById("c_correo_electronico");
		const c_departamento = document.getElementById("c_departamento");
		const c_cargo = document.getElementById("c_cargo");
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
					text: "Personal registrado exitosamente",
					icon: "success",
					timer: 2000
				});

				setTimeout(() => location.href = `${url_}/personal`, 2000);
			});
		}
	});
})();