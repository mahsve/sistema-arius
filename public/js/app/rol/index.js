(function () {
	// Elementos HTML.
	const btn_nuevo_rol = document.getElementById("btn_nuevo_rol");
	const switch_estatus = document.querySelectorAll(".switch_estatus");
	const btn_editar = document.querySelectorAll(".btn_editar");
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const formulario_registro = document.getElementById("formulario_registro");
	const modal_modificar = document.getElementById('modal_modificar') != null ? new bootstrap.Modal('#modal_modificar') : null;
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");
	/**
	 * COMENTARIOS [DOCUMENTACIÓN].
	 */
	// .servicios_r								- clase que abarca todos los checks de los servicios independientemente del módulo.
	// .servicios_r_{{idmodulo}}	- clase que abarca todos los checks de los servicios del módulo indicado en el idmodulo.

	// Eventos elementos HTML.
	// Agregar nuevo registro.
	if (btn_nuevo_rol != null) {
		btn_nuevo_rol.addEventListener("click", function (e) {
			e.preventDefault();
			formulario_registro.reset();
			Array.from(document.querySelectorAll('.servicios_r')).forEach(check_ => check_.setAttribute('disabled', true));
			modal_registrar.show();
		});
	}

	// [MODULO REGISTRAR].
	// Agregamos evento a los check de los módulos para que activen y desactiven la opción de marcar los servicios de estos.
	Array.from(document.querySelectorAll('.modulo_r')).forEach(ck_modulo => {
		ck_modulo.addEventListener('change', function () {
			const idmodulo = this.value;
			Array.from(document.querySelectorAll(`.servicio_r_${idmodulo}`)).forEach(ck_servicio => {
				if (!ck_modulo.checked)
					ck_servicio.setAttribute("disabled", true);
				else
					ck_servicio.removeAttribute("disabled");
			});
		});
	});

	// Agregamos evento a los check de "Marcar todos" para marcar todos los servicios de un módulo.
	Array.from(document.querySelectorAll('.marcar_todos_r')).forEach(ck_marcar => {
		ck_marcar.addEventListener('change', function () {
			const idmodulo = this.getAttribute('data-modulo');
			Array.from(document.querySelectorAll(`.servicio_r_${idmodulo}`)).forEach(ck_servicio => ck_servicio.checked = ck_marcar.checked);
		});
	});

	// [MODULO MODIFICAR].
	// Agregamos evento a los check de los módulos para que activen y desactiven la opción de marcar los servicios de estos.
	Array.from(document.querySelectorAll('.modulo_m')).forEach(ck_modulo => {
		ck_modulo.addEventListener('change', function () {
			const idmodulo = this.value;
			Array.from(document.querySelectorAll(`.servicio_m_${idmodulo}`)).forEach(ck_servicio => {
				if (!ck_modulo.checked)
					ck_servicio.setAttribute("disabled", true);
				else
					ck_servicio.removeAttribute("disabled");
			});
		});
	});

	// Agregamos evento a los check de "Marcar todos" para marcar todos los servicios de un módulo.
	Array.from(document.querySelectorAll('.marcar_todos_m')).forEach(ck_marcar => {
		ck_marcar.addEventListener('change', function () {
			const idmodulo = this.getAttribute('data-modulo');
			Array.from(document.querySelectorAll(`.servicio_m_${idmodulo}`)).forEach(ck_servicio => ck_servicio.checked = ck_marcar.checked);
		});
	});

	// Registrar dato.
	if (formulario_registro != null) {
		formulario_registro.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_rol = document.getElementById("c_rol_r");
			const btn_guardar = document.getElementById("btn_registrar");

			// Validamos los campos.
			if (c_rol.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del rol!' });
				c_rol.focus();
			} else if (c_rol.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El rol debe tener al menos 3 caracteres!' });
				c_rol.focus();
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
			const c_rol = document.getElementById("c_rol_m");
			const btn_guardar = document.getElementById("btn_modificar");

			// Validamos los campos.
			if (c_rol.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del rol!' });
				c_rol.focus();
			} else if (c_rol.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El rol debe tener al menos 3 caracteres!' });
				c_rol.focus();
			} else {
				btn_guardar.classList.add("loading");
				btn_guardar.setAttribute('disabled', true);
				fetch(`${formulario_actualizacion.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_actualizacion) }).then(response => response.json()).then(data => {
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
	Array.from(btn_editar).forEach(btn => {
		btn.addEventListener('click', function (e) {
			e.preventDefault();

			// Capturamos el elemento que provoco el evento.
			const btn_consultar = this;
			const id_data = btn_consultar.getAttribute('data-id');

			// Realizamos la consulta AJAX.
			btn_consultar.classList.add('loading');
			btn_consultar.setAttribute('disabled', true);
			fetch(`${url_}/roles/${id_data}/edit`, { method: 'get' }).then(response => response.json()).then((data) => {
				btn_consultar.classList.remove('loading');
				btn_consultar.removeAttribute('disabled');

				// Limpiamos el formulario y cargamos los datos consultados.
				formulario_actualizacion.reset();
				formulario_actualizacion.setAttribute('action', `${url_}/roles/${id_data}`);
				document.getElementById('c_rol_m').value = data.rol;
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
				title: '¿Seguro que quieres cambiar el estatus de este rol?',
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
					fetch(`${url_}/roles/estatus/${switch_element.value}`, {
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