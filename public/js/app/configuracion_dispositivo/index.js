(function () {
	// Elementos HTML.
	const btn_nuevo_configuracion = document.getElementById("btn_nuevo_configuracion");
	const switch_estatus = document.querySelectorAll(".switch_estatus");
	const btn_editar = document.querySelectorAll(".btn_editar");
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const formulario_registro = document.getElementById("formulario_registro");
	const modal_modificar = document.getElementById('modal_modificar') != null ? new bootstrap.Modal('#modal_modificar') : null;
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");

	// Eventos elementos HTML.
	// Agregar nuevo registro.
	if (btn_nuevo_configuracion != null) {
		btn_nuevo_configuracion.addEventListener("click", function (e) {
			e.preventDefault();
			formulario_registro.reset();
			modal_registrar.show();
		});
	}

	// Registrar dato.
	if (formulario_registro != null) {
		formulario_registro.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_configuracion = document.getElementById("c_configuracion_r");
			const c_dispositivo = document.getElementById("c_dispositivo_r");
			const btn_guardar = document.getElementById("btn_registrar");

			// Validamos los campos.
			if (c_dispositivo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el dispositivo!' });
				c_dispositivo.focus();
			} else if (c_configuracion.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre de la configuración!' });
				c_configuracion.focus();
			} else if (c_configuracion.value.length < 2) {
				Toast.fire({ icon: 'error', title: '¡La configuración debe tener al menos 2 caracteres!' });
				c_configuracion.focus();
			} else {
				btn_guardar.classList.add("loading");
				btn_guardar.setAttribute('disabled', true);
				fetch(`${formulario_registro.getAttribute('action')}`, {
					method: 'post',
					body: new FormData(formulario_registro)
				}).then(response => response.json()).then(data => {
					btn_guardar.classList.remove("loading");
					btn_guardar.removeAttribute('disabled');

					// Verificamos si ocurrió algún error.
					if (data.status == "error") {
						Toast.fire({ icon: data.status, title: data.response.message });
						return false;
					}

					// Enviamos mensaje de exito.
					modal_registrar.hide();
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
	}

	// Modificar dato.
	if (formulario_actualizacion != null) {
		formulario_actualizacion.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_configuracion = document.getElementById("c_configuracion_m");
			const c_dispositivo = document.getElementById("c_dispositivo_m");
			const btn_guardar = document.getElementById("btn_modificar");

			// Validamos los campos.
			if (c_dispositivo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el dispositivo!' });
				c_dispositivo.focus();
			} else if (c_configuracion.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre de la configuración!' });
				c_configuracion.focus();
			} else if (c_configuracion.value.length < 2) {
				Toast.fire({ icon: 'error', title: '¡La configuración debe tener al menos 2 caracteres!' });
				c_configuracion.focus();
			} else {
				btn_guardar.classList.add("loading");
				btn_guardar.setAttribute('disabled', true);
				fetch(`${formulario_actualizacion.getAttribute('action')}`, {
					method: 'post',
					body: new FormData(formulario_actualizacion)
				}).then(response => response.json()).then(data => {
					btn_guardar.classList.remove("loading");
					btn_guardar.removeAttribute('disabled');

					// Verificamos si ocurrió algún error.
					if (data.status == "error") {
						Toast.fire({ icon: data.status, title: data.response.message });
						return false;
					}

					// Enviamos mensaje de exito.
					modal_modificar.hide();
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
	}

	// Consultar registro.
	Array.from(btn_editar).forEach(btn_ => {
		btn_.addEventListener('click', function (e) {
			e.preventDefault();

			// Capturamos el elemento que provoco el evento.
			const btn_consultar = this;
			const id_data = btn_consultar.getAttribute('data-id');

			// Realizamos la consulta AJAX.
			btn_consultar.classList.add('loading');
			btn_consultar.setAttribute('disabled', true);
			fetch(`${url_}/dispositivo_cog/${id_data}/edit`, { method: 'get' }).then(response => response.json()).then((data) => {
				btn_consultar.classList.remove('loading');
				btn_consultar.removeAttribute('disabled');

				// Limpiamos el formulario y cargamos los datos consultados.
				formulario_actualizacion.reset();
				formulario_actualizacion.setAttribute('action', `${url_}/dispositivo_cog/${id_data}`);
				document.getElementById('c_configuracion_m').value = data.configuracion;
				document.getElementById('c_dispositivo_m').value = data.iddispositivo;
				document.getElementById('c_descripcion_m').value = data.descripcion;
				modal_modificar.show();
			});
		});
	});

	// Cambiar estatus.
	Array.from(switch_estatus).forEach(switch_ => {
		switch_.addEventListener('change', function () {
			// Capturamos el elemento que provoco el evento.
			switch_element = this;
			switch_element.checked = !switch_element.checked;

			// Pedimos confirmar que desea desactivar este registro.
			Swal.fire({
				title: '¿Seguro que quieres cambiar el estatus de esta configuración?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Cambiar',
				cancelButtonText: 'Cancelar',
			}).then((result) => {
				if (result.isConfirmed) {
					const form_data = new FormData();
					const form_check = switch_element.parentElement;
					form_data.append('_method', 'PUT');
					form_check.classList.add('loading');

					// Realizamos la consulta AJAX.
					fetch(`${url_}/dispositivo_cog/estatus/${switch_element.value}`, {
						headers: { 'X-CSRF-TOKEN': token_ },
						method: 'post',
						body: form_data,
					}).then(response => response.json()).then(data => {
						form_check.classList.remove('loading');

						// Verificamos si ocurrió algún error.
						if (data.status == "error") {
							Toast.fire({ icon: data.status, title: data.response.message });
							return false;
						}

						// Enviamos mensaje de exito al usuario.
						Toast.fire({ icon: data.status, title: data.response.message });
						switch_element.checked = !switch_element.checked;
						const idrand = switch_element.getAttribute('data-id');
						if (switch_element.checked) {
							document.querySelector(`#contenedor_badge${idrand}`).innerHTML = `<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>`;
						} else {
							document.querySelector(`#contenedor_badge${idrand}`).innerHTML = `<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>`;
						}
					});
				}
			});
		});
	});
})();