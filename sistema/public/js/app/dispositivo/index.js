(function () {
	// Elementos HTML.
	const btn_nuevo_dispositivo = document.getElementById("btn_nuevo_dispositivo");
	const switch_estatus = document.querySelectorAll(".switch_estatus");
	const btn_editar = document.querySelectorAll(".btn_editar");
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const c_tipo_reg = document.getElementById("c_tipo_r");
	const formulario_registro = document.getElementById("formulario_registro");
	const modal_modificar = document.getElementById('modal_modificar') != null ? new bootstrap.Modal('#modal_modificar') : null;
	const c_tipo_mod = document.getElementById("c_tipo_m");
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");
	// AUXILIAR.
	// [Configuraciones].
	const btn_nuevo_conf = document.querySelectorAll('.btn_nuevo_conf');
	const modal_registrar_conf = document.getElementById('modal_registrar_conf') != null ? new bootstrap.Modal('#modal_registrar_conf') : null;
	const formulario_registro_conf = document.getElementById("formulario_registro_conf");

	// Eventos elementos HTML.
	// Agregar nuevo registro.
	if (btn_nuevo_dispositivo != null) {
		btn_nuevo_dispositivo.addEventListener("click", function (e) {
			e.preventDefault();
			formulario_registro.reset();
			c_tipo_reg.dispatchEvent(new Event('change'));
			modal_registrar.show();
		});
	}

	// Evento en el campo tipo de dispositivo.
	// [REGISTRAR]
	if (c_tipo_reg != null) {
		c_tipo_reg.addEventListener("change", function (e) {
			e.preventDefault();

			// Si elige el tipo de dispositivo en general, mostrara la configuraciones disponibles, de lo contrario la ocultara.
			const contenedor = document.getElementById("contenedor_conf_r");
			if (c_tipo_reg.value == "Z") { // Dispositivos de las zonas en el mapa de zona.
				contenedor.style.display = "";
			} else {
				contenedor.style.display = "none";
			}
		});
	}
	// [MODIFICAR]
	if (c_tipo_mod != null) {
		c_tipo_mod.addEventListener("change", function (e) {
			e.preventDefault();

			// Si elige el tipo de dispositivo en general, mostrara la configuraciones disponibles, de lo contrario la ocultara.
			const contenedor = document.getElementById("contenedor_conf_m");
			if (c_tipo_mod.value == "Z") { // Dispositivos de las zonas en el mapa de zona.
				contenedor.style.display = "";
			} else {
				contenedor.style.display = "none";
			}
		});
	}

	// Registrar dato.
	if (formulario_registro != null) {
		formulario_registro.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			var configuraciones_marcadas = 0;
			const c_tipo = document.getElementById("c_tipo_r");
			const c_dispositivo = document.getElementById("c_dispositivo_r");
			const btn_guardar = document.getElementById("btn_registrar");

			// Verificar si no ha seleccionado una sola configuración en caso de ser un dispositivo general.
			if (c_tipo.value == 'Z') {
				Array.from(document.querySelectorAll(".configuraciones_r")).forEach(check => {
					if (check.checked) configuraciones_marcadas++;
				});
			}

			// Validamos los campos.
			if (c_tipo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el tipo de dispositivo!' });
			} else if (c_dispositivo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del dispositivo!' });
				c_dispositivo.focus();
			} else if (c_dispositivo.value.length < 2) {
				Toast.fire({ icon: 'error', title: '¡El dispositivo debe tener al menos 2 caracteres!' });
				c_dispositivo.focus();
			} else if (c_tipo.value == 'Z' && configuraciones_marcadas == 0) { // Si es de tipo general y no ha marcado ninguna configuración a agregar.
				Toast.fire({ icon: 'error', title: '¡Marque al menos una de las configuraciones a agregar!' });
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
			const c_dispositivo = document.getElementById("c_dispositivo_m");
			const btn_guardar = document.getElementById("btn_modificar");

			// Validamos los campos.
			if (c_dispositivo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del dispositivo!' });
				c_dispositivo.focus();
			} else if (c_dispositivo.value.length < 2) {
				Toast.fire({ icon: 'error', title: '¡El dispositivo debe tener al menos 2 caracteres!' });
				c_dispositivo.focus();
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

	// Agregar nuevo registro [Configuracion] como un formulario auxiliar para registro rápido.
	if (btn_nuevo_conf.length > 0) {
		Array.from(btn_nuevo_conf).forEach(btn => {
			btn.addEventListener("click", function (e) {
				e.preventDefault();
				formulario_registro_conf.reset();
				document.getElementById('btn_click_conf').value = btn.getAttribute('data-form');
				if (document.getElementById('btn_click_conf').value == "create") {
					modal_registrar.hide();
				} else {
					modal_modificar.hide();
				}
				setTimeout(() => modal_registrar_conf.show(), 200);
			});
		});

		// Cuando se oculte este registrar, se cargará nuevamente la ventana principal correspondiente.
		document.getElementById('modal_registrar_conf').addEventListener('hidden.bs.modal', event => {
			if (document.getElementById('btn_click_conf').value == "create") {
				setTimeout(() => modal_registrar.show(), 200);
			} else {
				setTimeout(() => modal_modificar.show(), 200);
			}
		});
	}

	// Función registro rápido [Configuración].
	if (formulario_registro_conf != null) {
		formulario_registro_conf.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_configuracion = document.getElementById("c_configuracion_aux");
			const btn_guardar = document.getElementById("btn_registrar_conf");

			// Validamos los campos.
			if (c_configuracion.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre de la configuración!' });
				c_configuracion.focus();
			} else if (c_configuracion.value.length < 2) {
				Toast.fire({ icon: 'error', title: '¡La configuración debe tener al menos 2 caracteres!' });
				c_configuracion.focus();
			} else {
				btn_guardar.classList.add("loading");
				btn_guardar.setAttribute('disabled', true);
				fetch(`${formulario_registro_conf.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro_conf) }).then(response => response.json()).then(data => {
					btn_guardar.classList.remove("loading");
					btn_guardar.removeAttribute('disabled');

					// Verificamos si ocurrió algún error.
					if (data.status == "error") {
						Toast.fire({ icon: data.status, title: data.response.message });
						if (data.response.error) console.error(`Error: ${data.response.error}`);
						return false;
					}

					// Creamos un nuevo elemento de tipo div con el elemento check para agregar la nueva opción al contenedor de las configuraciones disponibles de ambos formularios.
					const configuracion = data.response.data;
					const optionR = document.createElement('div');
					optionR.classList.add('col-6');
					optionR.innerHTML = `<div class="form-check d-flex align-items-center my-1">
						<input class="form-check-input m-0 configuraciones_r" type="checkbox" name="configuraciones[]" id="r_conf_${configuracion.idconfiguracion}" value="${configuracion.idconfiguracion}">
						<label class="form-check-label ms-2" for="r_conf_${configuracion.idconfiguracion}">${configuracion.configuracion}</label>
					</div>`;
					document.getElementById('configuraciones_r').append(optionR); // Contenedor configuraciones [registrar].
					// Nuevo
					const optionM = document.createElement('div');
					optionM.classList.add('col-6');
					optionM.innerHTML = `<div class="form-check d-flex align-items-center my-1">
						<input class="form-check-input m-0 configuraciones_m" type="checkbox" name="configuraciones[]" id="m_conf_${configuracion.idconfiguracion}" value="${configuracion.idconfiguracion}">
						<label class="form-check-label ms-2" for="m_conf_${configuracion.idconfiguracion}">${configuracion.configuracion}</label>
					</div>`;
					document.getElementById('configuraciones_m').append(optionM); // Contenedor configuraciones [modificar].

					// Enviamos mensaje de exito.
					modal_registrar_conf.hide();
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

	// Consultar registro.
	if (btn_editar.length > 0) {
		Array.from(btn_editar).forEach(btn_ => {
			btn_.addEventListener('click', function (e) {
				e.preventDefault();
	
				// Capturamos el elemento que provoco el evento.
				const btn_consultar = this;
				const id_data = btn_consultar.getAttribute('data-id');
	
				// Realizamos la consulta AJAX.
				btn_consultar.classList.add('loading');
				btn_consultar.setAttribute('disabled', true);
				fetch(`${url_}/dispositivos/${id_data}/edit`, { method: 'get' }).then(response => response.json()).then((data) => {
					btn_consultar.classList.remove('loading');
					btn_consultar.removeAttribute('disabled');
	
					// Limpiamos el formulario y cargamos los datos consultados.
					formulario_actualizacion.reset();
					formulario_actualizacion.setAttribute('action', `${url_}/dispositivos/${id_data}`);
					document.getElementById('c_tipo_m').value = data.tipo;
					document.getElementById('c_dispositivo_m').value = data.dispositivo;
					c_tipo_mod.dispatchEvent(new Event('change'));
					// Recorremos todos las configuraciones agregadas.
					for (let i = 0; i < data.configuraciones.length; i++) {
						const configuracion = data.configuraciones[i];
						document.getElementById(`m_conf_${configuracion.idconfiguracion}`).checked = true;
					}
					modal_modificar.show();
				});
			});
		});
	}

	// Cambiar estatus.
	if (switch_estatus.length > 0) {
		Array.from(switch_estatus).forEach(switch_ => {
			switch_.addEventListener('change', function () {
				// Capturamos el elemento que provoco el evento.
				switch_element = this;
				switch_element.checked = !switch_element.checked;
	
				// Pedimos confirmar que desea desactivar este registro.
				Swal.fire({
					title: '¿Seguro que quieres cambiar el estatus de este dispositivo?',
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
						fetch(`${url_}/dispositivos/estatus/${switch_element.value}`, {
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
	}
})();