(function () {
	// Elementos HTML.
	const formulario_registro = document.getElementById("formulario_registro");
	const c_departamento_ = document.getElementById("c_departamento");
	const c_cargo_ = document.getElementById("c_cargo");
	const btn_guardar = document.getElementById("btn_guardar");
	// AUXILIAR.
	// [Departamentos].
	const btn_nuevo_dep = document.querySelectorAll('.btn_nuevo_dep');
	const modal_registrar_dep = document.getElementById('modal_registrar_dep') != null ? new bootstrap.Modal('#modal_registrar_dep') : null;
	const formulario_registro_dep = document.getElementById("formulario_registro_dep");
	// [Cargos].
	const btn_nuevo_car = document.querySelectorAll('.btn_nuevo_car');
	const modal_registrar_car = document.getElementById('modal_registrar_car') != null ? new bootstrap.Modal('#modal_registrar_car') : null;
	const formulario_registro_car = document.getElementById("formulario_registro_car");

	// Mascaras.
	var identificacionMask = IMask(document.getElementById('c_identificacion'), { mask: '00000000' });
	const telefono1Mask = IMask(document.getElementById('c_telefono1'), { mask: '000-0000' });
	const telefono2Mask = IMask(document.getElementById('c_telefono2'), { mask: '000-0000' });
	const validar_correo = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

	// Eventos elementos HTML.
	c_departamento_.addEventListener('change', function () {
		c_cargo_.innerHTML = '<option value="">Seleccione una opción</option>';
		if (this.value != "") {
			c_cargo_.parentElement.classList.add("loading");
			fetch(`${url_}/personal/cargos/${this.value}`).then(response => response.json()).then(data => {
				c_cargo_.parentElement.classList.remove("loading");

				// Verificamos si no consiguió cargos para este departamento y cargamos un mensaje al usuario.
				if (data.length == 0) {
					c_cargo_.innerHTML = '<option value="">Sin cargos registrados</option>';
					return;
				}

				// De lo contrario procedemos a cargar todos los cargos encontrados para el departamento seleccionado.
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
		const c_prefijo_telefono1 = document.getElementById("c_prefijo_telefono1");
		const c_telefono1 = document.getElementById("c_telefono1");
		const c_prefijo_telefono2 = document.getElementById("c_prefijo_telefono2");
		const c_telefono2 = document.getElementById("c_telefono2");
		const c_correo_electronico = document.getElementById("c_correo_electronico");
		const c_departamento = document.getElementById("c_departamento");
		const c_cargo = document.getElementById("c_cargo");
		const c_direccion = document.getElementById("c_direccion");

		// Validamos los campos.
		if (c_identificacion.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese el número de Cédula!" });
			c_identificacion.focus();
		} else if (c_identificacion.value.length < 8) {
			Toast.fire({ icon: "error", title: "¡La cédula está incompleta!" });
			c_identificacion.focus();
		} else if (c_nombre_completo.value == "") {
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
		} else if (c_departamento.value == "") {
			Toast.fire({ icon: "error", title: "¡Seleccione el departamento!" });
			c_departamento.focus();
		} else if (c_cargo.value == "") {
			Toast.fire({ icon: "error", title: "¡Seleccione el cargo!" });
			c_cargo.focus();
		} else if (c_direccion.value == "") {
			Toast.fire({ icon: "error", title: "¡Ingrese la dirección física!" });
			c_direccion.focus();
		} else if (c_direccion.value.length < 10) {
			Toast.fire({ icon: "error", title: "¡La dirección debe tener al menos 10 caracteres!" });
			c_direccion.focus();
		} else {
			btn_guardar.classList.add("loading");
			btn_guardar.setAttribute('disabled', true);
			fetch(`${formulario_registro.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro) }).then(response => response.json()).then(data => {
				btn_guardar.classList.remove("loading");
				btn_guardar.removeAttribute('disabled');

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					return false;
				}

				// Enviamos mensaje de exito.
				Swal.fire({
					title: "Exito",
					icon: data.status,
					text: data.response.message,
					timer: 2000,
					willClose: () => location.href = `${url_}/personal`,
				});
			});
		}
	});

	// Agregar nuevo registro [Departamento] como un formulario auxiliar para registro rápido.
	if (btn_nuevo_dep.length > 0) {
		Array.from(btn_nuevo_dep).forEach(btn => {
			btn.addEventListener("click", function (e) {
				e.preventDefault();
				formulario_registro_dep.reset();
				document.getElementById('btn_click_dep').value = btn.getAttribute('data-form');
				if (document.getElementById('btn_click_dep').value == "modal") {
					modal_registrar_car.hide();
					setTimeout(() => modal_registrar_dep.show(), 200);
				} else {
					modal_registrar_dep.show();
				}
			});
		});

		// Cuando se oculte este registrar, se cargará nuevamente la ventana principal correspondiente.
		document.getElementById('modal_registrar_dep').addEventListener('hidden.bs.modal', event => {
			if (document.getElementById('btn_click_dep').value == "modal") {
				setTimeout(() => modal_registrar_car.show(), 200);
			}
		});
	}

	// Agregar nuevo registro [Cargo] como un formulario auxiliar para registro rápido.
	if (btn_nuevo_car.length > 0) {
		Array.from(btn_nuevo_car).forEach(btn => {
			btn.addEventListener("click", function (e) {
				e.preventDefault();
				formulario_registro_car.reset();
				document.getElementById('c_departamento_c').value = document.getElementById('c_departamento').value;
				document.getElementById('btn_click_car').value = btn.getAttribute('data-form');
				modal_registrar_car.show();
			});
		});
	}

	// Función registro rápido [Departamento].
	if (formulario_registro_dep != null) {
		formulario_registro_dep.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_departamento = document.getElementById("c_departamento_aux");
			const btn_guardar = document.getElementById("btn_registrar_dep");

			// Validamos los campos.
			if (c_departamento.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del departamento!' });
				c_departamento.focus();
			} else if (c_departamento.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El departamento debe tener al menos 3 caracteres!' });
				c_departamento.focus();
			} else {
				btn_guardar.classList.add("loading");
				btn_guardar.setAttribute('disabled', true);
				fetch(`${formulario_registro_dep.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro_dep) }).then(response => response.json()).then(data => {
					btn_guardar.classList.remove("loading");
					btn_guardar.removeAttribute('disabled');

					// Verificamos si ocurrió algún error.
					if (data.status == "error") {
						Toast.fire({ icon: data.status, title: data.response.message });
						return false;
					}

					// Creamos un nuevo elemento de tipo option con la info para agregar la nueva opción al campo select de ambos formularios.
					const optionR = document.createElement('option');
					optionR.setAttribute('value', data.response.data.iddepartamento);
					optionR.innerHTML = data.response.data.departamento;
					document.getElementById('c_departamento').append(optionR); // Campo departamento [personal].
					// Nuevo
					const optionM = document.createElement('option');
					optionM.setAttribute('value', data.response.data.iddepartamento);
					optionM.innerHTML = data.response.data.departamento;
					document.getElementById('c_departamento_c').append(optionM); // Campo departamento [cargo].

					// Enviamos mensaje de exito.
					modal_registrar_dep.hide();
					Swal.fire({
						title: "Exito",
						icon: data.status,
						text: data.response.message,
						timer: 2000,
					});
				});
			}
		});
	}

	// Función registro rápido [Departamento].
	if (formulario_registro_car != null) {
		formulario_registro_car.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_departamento = document.getElementById("c_departamento_c");
			const c_cargo = document.getElementById("c_cargo_aux");
			const btn_guardar = document.getElementById("btn_registrar_car");

			// Validamos los campos.
			if (c_departamento.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el departamento!' });
				c_departamento.focus();
			} else if (c_cargo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del cargo!' });
				c_cargo.focus();
			} else if (c_cargo.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El cargo debe tener al menos 3 caracteres!' });
				c_cargo.focus();
			} else {
				btn_guardar.classList.add("loading");
				btn_guardar.setAttribute('disabled', true);
				fetch(`${formulario_registro_car.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro_car) }).then(response => response.json()).then(data => {
					btn_guardar.classList.remove("loading");
					btn_guardar.removeAttribute('disabled');

					// Verificamos si ocurrió algún error.
					if (data.status == "error") {
						Toast.fire({ icon: data.status, title: data.response.message });
						return false;
					}

					// Verificamos si hay un departamento escogido y no tiene un solo cargo registrado.
					if (document.getElementById('c_departamento').value != "" && document.querySelectorAll('#c_cargo option').length == 1) {
						document.getElementById('c_cargo').innerHTML = `<option value="">Seleccione una opción</option>`; // Rescribimos la opciones a "Seleccione una opción".
					}

					// Verificamos primero si hay un departamento seleccionado.
					if (document.getElementById('c_departamento').value != "") {
						// Creamos un nuevo elemento de tipo option con la info para agregar la nueva opción al campo select de ambos formularios.
						const optionR = document.createElement('option');
						optionR.setAttribute('value', data.response.data.idcargo);
						optionR.innerHTML = data.response.data.cargo;
						document.getElementById('c_cargo').append(optionR); // Campo cargo [personal].
					}

					// Enviamos mensaje de exito.
					modal_registrar_car.hide();
					Swal.fire({
						title: "Exito",
						icon: data.status,
						text: data.response.message,
						timer: 2000,
					});
				});
			}
		});
	}
})();