(function () {
	// Elementos HTML.
	const btn_nuevo_modulo = document.getElementById("btn_nuevo_modulo");
	const btn_organizar_modulos = document.getElementById("btn_organizar_modulos");
	const switch_estatus = document.querySelectorAll(".switch_estatus");
	const btn_editar = document.querySelectorAll(".btn_editar");
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const formulario_registro = document.getElementById("formulario_registro");
	const c_icono_r = document.getElementById('c_icono_r');
	const modal_modificar = document.getElementById('modal_modificar') != null ? new bootstrap.Modal('#modal_modificar') : null;
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");
	const c_icono_m = document.getElementById('c_icono_m');
	const modal_organizar = document.getElementById('modal_organizar') != null ? new bootstrap.Modal('#modal_organizar') : null;
	const formulario_organizar = document.getElementById("formulario_organizar");
	const lista_modulos = document.getElementById('lista-modulos');

	// Plugins.
	if (lista_modulos != null) {
		new Sortable(lista_modulos, {
			animation: 150,
			ghostClass: 'blue-background-class',
			onUpdate: function () {
				const list_orden = document.querySelectorAll('.o_orden');
				const list_html = document.querySelectorAll('.o_htmlorden');
				for (let i = 0; i < list_orden.length; i++) {
					list_orden[i].value = i + 1;
					list_html[i].innerHTML = i + 1;
				}
			},
		});
	}

	// Eventos elementos HTML.
	// Agregar nuevo registro.
	if (btn_nuevo_modulo != null) {
		btn_nuevo_modulo.addEventListener("click", function (e) {
			e.preventDefault();
			formulario_registro.reset();
			document.getElementById('c_icono_r').dispatchEvent(new Event('keyup'));
			modal_registrar.show();
		});
	}

	// Evento para mostrar una visualización rapida del icono agregado en el formulario del módulo [Registrar].
	if (c_icono_r != null) {
		c_icono_r.addEventListener('keyup', function () {
			document.getElementById('preview_r').setAttribute('class', `${this.value}`);
		});
	}

	// Evento para mostrar una visualización rapida del icono agregado en el formulario del módulo [Modificar].
	if (c_icono_m != null) {
		c_icono_m.addEventListener('keyup', function () {
			document.getElementById('preview_m').setAttribute('class', `${this.value}`);
		});
	}

	// Mostrar ventana para organizar los módulos por un orden en especifico.
	if (btn_organizar_modulos != null) {
		btn_organizar_modulos.addEventListener("click", function (e) {
			e.preventDefault();
			modal_organizar.show();
		});
	}

	// Registrar dato.
	if (formulario_registro != null) {
		formulario_registro.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_modulo = document.getElementById("c_modulo_r");
			const btn_guardar = document.getElementById("btn_registrar");

			// Validamos los campos.
			if (c_modulo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del módulo!' });
				c_modulo.focus();
			} else if (c_modulo.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El módulo debe tener al menos 3 caracteres!' });
				c_modulo.focus();
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
			const c_modulo = document.getElementById("c_modulo_m");
			const btn_guardar = document.getElementById("btn_modificar");

			// Validamos los campos.
			if (c_modulo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del módulo!' });
				c_modulo.focus();
			} else if (c_modulo.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El módulo debe tener al menos 3 caracteres!' });
				c_modulo.focus();
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

	// Modificar orden.
	if (formulario_organizar != null) {
		formulario_organizar.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const btn_guardar = document.getElementById("btn_guardar");
			btn_guardar.classList.add("loading");
			btn_guardar.setAttribute('disabled', true);
			fetch(`${formulario_organizar.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_organizar) }).then(response => response.json()).then(data => {
				btn_guardar.classList.remove("loading");
				btn_guardar.removeAttribute('disabled');

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					if (data.response.error) console.error(`Error: ${data.response.error}`);
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
			fetch(`${url_}/modulos/${id_data}/edit`, { method: 'get' }).then(response => response.json()).then((data) => {
				btn_consultar.classList.remove('loading');
				btn_consultar.removeAttribute('disabled');

				// Limpiamos el formulario y cargamos los datos consultados.
				formulario_actualizacion.reset();
				formulario_actualizacion.setAttribute('action', `${url_}/modulos/${id_data}`);
				document.getElementById('c_modulo_m').value = data.modulo;
				document.getElementById('c_icono_m').value = data.icono;
				document.getElementById('c_icono_m').dispatchEvent(new Event('keyup'));
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
				title: '¿Seguro que quieres cambiar el estatus de este módulo?',
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
					fetch(`${url_}/modulos/estatus/${switch_element.value}`, {
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