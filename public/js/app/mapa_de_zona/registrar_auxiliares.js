(function () {
	// AUXILIAR.
	// [Equipos].
	const btn_modal_equipos = document.getElementById('btn_modal_equipos');
	const btn_modal_teclados = document.getElementById('btn_modal_teclados');
	const modal_registrar_disp = document.getElementById('modal_registrar_disp') != null ? new bootstrap.Modal('#modal_registrar_disp') : null;
	const input_tipo = document.getElementById("c_tipo_aux");
	const formulario_registro_disp = document.getElementById("formulario_registro_disp");
	// [Configuraciones].
	const btn_nuevo_conf = document.querySelectorAll('.btn_nuevo_conf');
	const modal_registrar_conf = document.getElementById('modal_registrar_conf') != null ? new bootstrap.Modal('#modal_registrar_conf') : null;
	const formulario_registro_conf = document.getElementById("formulario_registro_conf");

	// Abrir modal para registrar dispositivos [Equipos de zona].
	btn_modal_equipos.addEventListener("click", function (e) {
		e.preventDefault();
		formulario_registro_disp.reset();
		input_tipo.value = "Z";
		input_tipo.dispatchEvent(new Event('change'));
		document.getElementById('text_label_devices').innerText = "Equipo general";
		modal_registrar_disp.show();
	});

	// Abrir modal para registrar dispositivos [Teclados].
	btn_modal_teclados.addEventListener("click", function (e) {
		e.preventDefault();
		formulario_registro_disp.reset();
		input_tipo.value = "T";
		input_tipo.dispatchEvent(new Event('change'));
		document.getElementById('text_label_devices').innerText = "Modelo teclado";
		modal_registrar_disp.show();
	});

	// Evento en el campo tipo de dispositivo.
	// [REGISTRAR]
	if (input_tipo != null) {
		input_tipo.addEventListener("change", function (e) {
			e.preventDefault();

			// Si elige el tipo de dispositivo en general, mostrara la configuraciones disponibles, de lo contrario la ocultara.
			const contenedor = document.getElementById("contenedor_conf_aux");
			if (input_tipo.value == "Z") { // Dispositivos de las zonas en el mapa de zona.
				contenedor.style.display = "";
			} else {
				contenedor.style.display = "none";
			}
		});
	}

	// Función registro rápido [Dispositivo].
	if (formulario_registro_disp != null) {
		formulario_registro_disp.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			var configuraciones_marcadas = 0;
			const c_tipo = document.getElementById("c_tipo_aux");
			const c_dispositivo = document.getElementById("c_dispositivo_aux");
			const btn_guardar = document.getElementById("btn_registrar_disp");

			// Verificar si no ha seleccionado una sola configuración en caso de ser un dispositivo general.
			if (c_tipo.value == 'Z') {
				Array.from(document.querySelectorAll(".configuraciones_r")).forEach(check => {
					if (check.checked) configuraciones_marcadas++;
				});
			}

			// Validamos los campos.
			if (c_dispositivo.value == "") {
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
				fetch(`${formulario_registro_disp.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro_disp) }).then(response => response.json()).then(data => {
					btn_guardar.classList.remove("loading");
					btn_guardar.removeAttribute('disabled');

					// Verificamos si ocurrió algún error.
					if (data.status == "error") {
						Toast.fire({ icon: data.status, title: data.response.message });
						return false;
					}

					// Recorremos todos los campos "Equipos" del listado de zonas para agregar la nueva opción registrada.
					if (c_tipo.value == 'Z') {
						Array.from(document.querySelectorAll('.zona_equipos')).forEach(inp => {
							const optionR = document.createElement('option'); // Creamos un nuevo elemento de tipo option con la info para agregar la nueva opción al campo select del formulario.
							optionR.setAttribute('value', data.response.data.iddispositivo);
							optionR.innerHTML = data.response.data.dispositivo;
							inp.append(optionR); // Campo equipos zonas.
						});
					} else {
						const optionR = document.createElement('option'); // Creamos un nuevo elemento de tipo option con la info para agregar la nueva opción al campo select del formulario.
						optionR.setAttribute('value', data.response.data.iddispositivo);
						optionR.innerHTML = data.response.data.dispositivo;
						document.getElementById('m_teclado').append(optionR); // Campo Teclados.
					}

					// Enviamos mensaje de exito.
					modal_registrar_disp.hide();
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

	// Agregar nuevo registro [Configuracion] como un formulario auxiliar para registro rápido.
	if (btn_nuevo_conf.length > 0) {
		Array.from(btn_nuevo_conf).forEach(btn => {
			btn.addEventListener("click", function (e) {
				e.preventDefault();
				modal_registrar_disp.hide();
				formulario_registro_conf.reset();
				setTimeout(() => modal_registrar_conf.show(), 200);
			});
		});

		// Cuando se oculte este registrar, se cargará nuevamente la ventana principal correspondiente.
		document.getElementById('modal_registrar_conf').addEventListener('hidden.bs.modal', event => {
			setTimeout(() => modal_registrar_disp.show(), 200);
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
})();