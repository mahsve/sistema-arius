(function () {
	// Elementos HTML.
	const switch_estatus = document.querySelectorAll(".switch_estatus");
	const btn_editar = document.querySelectorAll(".btn_editar");
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const formulario_registro = document.getElementById("formulario_registro");
	const modal_modificar = document.getElementById('modal_modificar') != null ? new bootstrap.Modal('#modal_modificar') : null;
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");

	// Registrar dato.
	if (formulario_registro != null) {
		formulario_registro.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_fecha = document.getElementById("c_fecha_r");
			const c_motivo = document.getElementById("c_motivo_r");
			const btn_guardar = document.getElementById("btn_registrar");

			// Validamos los campos.
			if (c_fecha.value == "") {
				Toast.fire({ icon: 'error', title: '¡Selecccione la fecha de la solicitud!' });
				c_servicio.focus();
			} else if (c_motivo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el motivo de la solicitud del servicio!' });
				c_motivo.focus();
			} else if (c_motivo.value.length < 10) {
				Toast.fire({ icon: 'error', title: '¡El motivo de la solicitud debe tener al menos 10 caracteres!' });
				c_motivo.focus();
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
			const c_fecha = document.getElementById("c_fecha_m");
			const c_motivo = document.getElementById("c_motivo_m");
			const btn_guardar = document.getElementById("btn_modificar");

			// Validamos los campos.
			if (c_fecha.value == "") {
				Toast.fire({ icon: 'error', title: '¡Selecccione la fecha de la solicitud!' });
				c_servicio.focus();
			} else if (c_motivo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el motivo de la solicitud del servicio!' });
				c_motivo.focus();
			} else if (c_motivo.value.length < 10) {
				Toast.fire({ icon: 'error', title: '¡El motivo de la solicitud debe tener al menos 10 caracteres!' });
				c_motivo.focus();
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
	Array.from(btn_editar).forEach(btn_ => {
		btn_.addEventListener('click', function (e) {
			e.preventDefault();

			// Capturamos el elemento que provoco el evento.
			const btn_consultar = this;
			const id_data = btn_consultar.getAttribute('data-id');

			// Realizamos la consulta AJAX.
			btn_consultar.classList.add('loading');
			btn_consultar.setAttribute('disabled', true);
			fetch(`${url_}/departamentos/${id_data}/edit`, { method: 'get' }).then(response => response.json()).then((data) => {
				btn_consultar.classList.remove('loading');
				btn_consultar.removeAttribute('disabled');

				// Limpiamos el formulario y cargamos los datos consultados.
				formulario_actualizacion.reset();
				formulario_actualizacion.setAttribute('action', `${url_}/departamentos/${id_data}`);
				document.getElementById('c_servicio_m').value = data.departamento;
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
				title: '¿Seguro que quieres cambiar el estatus de este departamento?',
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
					fetch(`${url_}/departamentos/estatus/${switch_element.value}`, {
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


	/**
	 * BUSCAR CLIENTE
	 */
	const btn_abrir_buscar_cliente = document.getElementById("btn_abrir_buscar_cliente");
	const modal_buscar_cliente = new bootstrap.Modal(document.getElementById("modal_buscar_cliente"));
	const input_buscar_cliente = document.getElementById("input_buscar_cliente");
	const btn_buscar_cliente = document.getElementById("btn_buscar_cliente");
	const tabla_clientes = document.querySelector("#tabla_clientes tbody");

	// Eventos elementos HTML.
	// Abrir modal para buscar clientes.
	btn_abrir_buscar_cliente.addEventListener("click", (e) => {
		e.preventDefault();

		// Abrimos la modal y limpiamos la tabla de resultados.
		input_buscar_cliente.value = "";
		tabla_clientes.innerHTML = `<tr><td colspan="5" class="text-center"><i class="fas fa-clock me-2"></i> Esperando la consulta</td></tr>`;
		modal_buscar_cliente.show();
	});

	// Evento al presionar la tecla "Enter" buscar los clientes.
	input_buscar_cliente.addEventListener("keypress", (e) => e.keyCode == 13 ? btn_buscar_cliente.click() : null);

	// Procedemos a buscar los clientes en la base de datos.
	btn_buscar_cliente.addEventListener("click", function (e) {
		e.preventDefault();

		// Válidamos primero que el campo no este vacío.
		if (input_buscar_cliente.value == "") {
			Toast.fire({ icon: 'error', title: 'Ingrese el RIF, Cédula o nombre del cliente a buscar' });
			input_buscar_cliente.focus();
			tabla_clientes.innerHTML = `<tr><td colspan="5" class="text-center"><i class="fas fa-clock me-2"></i> Esperando la consulta</td></tr>`;
		} else {
			btn_buscar_cliente.classList.add('loading');
			fetch(`${url_}/servicios_tecnico_solicitados/clientes/${input_buscar_cliente.value}`).then(response => response.json()).then((data) => {
				btn_buscar_cliente.classList.remove('loading');
				if (data == null) {
					tabla_clientes.innerHTML = `<tr><td colspan="5" class="text-center text-danger"><i class="fas fa-user-times me-2"></i> Clientes no encontrados</td></tr>`;
					return;
				}

				// Recorremos la cadena de resultados y la inyectamos en la tabla HTML.
				tabla_clientes.innerHTML = "";
				for (let i = 0; i < data.length; i++) {
					const cliente = data[i];
					tabla_clientes.innerHTML += `<tr>
						<td class="py-1 px-2 text-center">${cliente.idcodigo}</td>
						<td class="py-1 px-2">${cliente.identificacion}</td>
						<td class="py-1 px-2">${cliente.nombre}</td>
						<td class="py-1 px-2">${cliente.telefono1}</td>
						<td class="py-1 px-2 text-center">
							${cliente.estatus == "A"
							? '<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>'
							: '<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>'
						}
						</td>
						<td class="py-1 px-2" style="width: 20px;">
							${cliente.estatus == "A"
							? `<button type="button" class="btn btn-primary btn-sm btn-icon btn_cliente_selecccionado" id="btn_agg_cliente_${i}" data-id="${i}" data-cliente="${cliente.idcodigo}"><i class="fas fa-plus"></i></button>`
							: '<button type="button" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-ban"></i></button>'
						}
						</td>
					</tr>`;
				}

				// Le agregamos evento click a todos los botones de la tabla con la función seleccionar cliente.
				Array.from(document.querySelectorAll('.btn_cliente_selecccionado')).forEach(btn => {
					btn.addEventListener('click', seleccionar_cliente);
				});
			});
		}
	});

	// Al presionar algunos de los clientes, cargar los datos en el formulario.
	function seleccionar_cliente() {
		const button_ = this;
		const codigo = button_.getAttribute('data-cliente');

		// Realizamos la consulta a la base de datos.
		button_.classList.add('loading');
		button_.setAttribute('disabled', true);
		fetch(`${url_}/servicios_tecnico_solicitados/mapa_de_zona/${codigo}`).then(response => response.json()).then((data) => {
			button_.classList.remove('loading');
			button_.removeAttribute('disabled');

			// Válidamos si realmente se encontraba la información del cliente.
			if (data == null) {
				Toast.fire({ icon: 'error', title: 'Ocurrió un error al consultar la información del cliente' });
				return;
			}

			// Cerramos la modal.
			modal_buscar_cliente.hide();
			formulario_registro.reset();
			document.getElementById('c_cliente_r').value = 'COD: ' + data.idcodigo + data.identificacion + ' - ' + data.nombre;
			document.getElementById('c_codigo_r').value = data.idcodigo;
			setTimeout(() => modal_registrar.show(), 500);
		});
	}
})();