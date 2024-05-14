(function () {
	// Elementos HTML.
	const btn_nueva_solicitud = document.getElementById('btn_nueva_solicitud');
	const btn_detalles = document.querySelectorAll(".btn_detalles");
	const btn_editar = document.querySelectorAll(".btn_editar");
	const btn_buscar_por_fecha = document.getElementById("btn_buscar_por_fecha");
	const switch_estatus = document.querySelectorAll(".switch_estatus");
	// Formularios.
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const formulario_registro = document.getElementById("formulario_registro");
	const codigo_r = document.getElementById("c_codigo_r");
	const modal_modificar = document.getElementById('modal_modificar') != null ? new bootstrap.Modal('#modal_modificar') : null;
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");
	const modal_detalles = document.getElementById('modal_detalles') != null ? new bootstrap.Modal('#modal_detalles') : null;

	// Registrar dato.
	if (btn_nueva_solicitud != null) {
		btn_nueva_solicitud.addEventListener('click', function (e) {
			e.preventDefault();

			// Limpiamos el formulario y mostramos la ventana emergente.
			formulario_registro.reset();
			btn_modal_cliente.style.display = "";
			btn_borrar_cliente.style.display = "none";
			document.getElementById("c_codigo_auxr").value = "";
			modal_registrar.show();
		});
	}

	// Buscar datos del cliente al ingresa el código de abonado del mapa de zona.
	if (codigo_r != null) {
		codigo_r.addEventListener('change', () => {
			const c_cliente_r = document.getElementById("c_cliente_r");

			// Válidamos que el campo del código no este vacío.
			if (codigo_r.value == "") {
				c_cliente_r.value = "";
				Toast.fire({ icon: 'error', title: '¡Ingrese el código del mapa de zona del cliente!' });
				codigo_r.focus();
				return false;
			} else if (codigo_r.value.length < 4) {
				c_cliente_r.value = "";
				Toast.fire({ icon: 'error', title: '¡El código debe tener 4 caracteres!' });
				codigo_r.focus();
				return false;
			}

			// Realizamos la consulta a la base de datos.
			codigo_r.parentElement.classList.add("loading");
			fetch(`${url_}/servicios_tecnico/mapa_de_zona/${codigo_r.value}`).then(response => response.json()).then((data) => {
				codigo_r.parentElement.classList.remove("loading");

				// Válidamos si realmente se encontraba la información del cliente.
				if (data == null) {
					c_cliente_r.value = "";
					Toast.fire({ icon: 'error', title: '¡Cliente no encontrado en la base de datos!' });
					return;
				}

				// Cerramos la modal.
				btn_modal_cliente.style.display = "none";
				btn_borrar_cliente.style.display = "";
				document.getElementById("c_codigo_auxr").value = data.idcodigo;
				c_cliente_r.value = data.identificacion + ' - ' + data.nombre;
			});
		});
	}

	// Registrar dato.
	if (formulario_registro != null) {
		formulario_registro.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_codigo = document.getElementById("c_codigo_r");
			const c_codigo2 = document.getElementById("c_codigo_auxr")
			const c_fecha = document.getElementById("c_fecha_r");
			const c_motivo = document.getElementById("c_motivo_r");
			const btn_guardar = document.getElementById("btn_registrar");

			// Validamos los campos.
			if (c_codigo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el código del cliente!' });
				c_codigo.focus();
			} else if (c_codigo.value.length < 4) {
				Toast.fire({ icon: 'error', title: '¡El código debe tener 4 caracteres!' });
				c_codigo.focus();
			} else if (c_codigo2.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el código de un cliente registrado!' });
				c_codigo.focus();
			} else if (c_fecha.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese la fecha de la solicitud!' });
				c_fecha.focus();
			} else if (c_motivo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el motivo de la solicitud!' });
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
				Toast.fire({ icon: 'error', title: '¡Ingrese la fecha de la solicitud!' });
				c_fecha.focus();
			} else if (c_motivo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el motivo de la solicitud!' });
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

	// Mostrar detalles.
	Array.from(btn_detalles).forEach(btn_ => {
		btn_.addEventListener('click', function (e) {
			e.preventDefault();

			// Capturamos el elemento que provoco el evento.
			const btn_consultar = this;
			const id_data = btn_consultar.getAttribute('data-id');

			// Realizamos la consulta AJAX.
			btn_consultar.classList.add('loading');
			btn_consultar.setAttribute('disabled', true);
			fetch(`${url_}/servicios_tecnico/modificar/${id_data}`, { method: 'get' }).then(response => response.json()).then((data) => {
				btn_consultar.classList.remove('loading');
				btn_consultar.removeAttribute('disabled');

				// Agregamos los detalles en el HTML y abrimos la modal.
				document.getElementById('modal_detalles_label').innerHTML = `<i class="fas fa-file-invoice"></i> Solicitud #${id_data.toString().padStart(8, '0')}`;
				document.getElementById('detalles_solicitud').innerHTML = `<div class="form-row">
					<div class="col-12 col-xl-3">
						<div class="form-group mb-3">
							<label for="c_codigo_r" class="required"><i class="fas fa-barcode"></i> Código</label>
							<span class="d-block">${data.idcodigo}</span>
						</div>
					</div>
					<div class="col-12 col-xl-9">
						<div class="form-group mb-3">
							<label for="c_cliente_r" class="required"><i class="fas fa-address-card"></i> Nombre/Razón social</label>
							<span class="d-block">${data.nombre}</span>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group mb-3">
							<label for="c_fecha_r" class="required"><i class="fas fa-calendar-day"></i> Fecha</label>
							<span class="d-block">${data.fecha}</span>
						</div>
					</div>
					<div class="col-12 col-md-8">
						<div class="form-group mb-3">
							<label for="c_motivo_r" class="required"><i class="fas fa-tools"></i> Motivo</label>
							<span class="d-block">${motivos[data.motivo]}</span>
						</div>
					</div>
					<div class="col-12">
						<div class="form-group">
							<label for="c_descripcion_r"><i class="fas fa-sticky-note"></i> Descripción de la solicitud</label>
							<span class="d-block">${data.descripcion}</span>
						</div>
					</div>
				</div>`;
				modal_detalles.show();
			});
		});
	});

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
			fetch(`${url_}/servicios_tecnico/modificar/${id_data}`, { method: 'get' }).then(response => response.json()).then((data) => {
				btn_consultar.classList.remove('loading');
				btn_consultar.removeAttribute('disabled');

				// Limpiamos el formulario y cargamos los datos consultados.
				formulario_actualizacion.reset();
				formulario_actualizacion.setAttribute('action', `${url_}/servicios_tecnico/modificar/${id_data}`);
				document.getElementById('c_codigo_m').value = data.idcodigo;
				document.getElementById('c_cliente_m').value = data.nombre;
				document.getElementById('c_fecha_m').value = data.fecha;
				document.getElementById('c_motivo_m').value = data.motivo;
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

	// Manejar el fitro para buscar por fechas.
	if (btn_buscar_por_fecha != null) {
		btn_buscar_por_fecha.addEventListener("click", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const fecha_inicio = document.getElementById("fecha_inicio");
			const fecha_final = document.getElementById("fecha_final");

			// Validamos los campos.
			if (fecha_inicio.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese la fecha de inicio!' });
				fecha_inicio.focus();
			} else if (fecha_final.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese la fecha tope!' });
				fecha_final.focus();
			} else if (fecha_inicio.value > fecha_final.value) {
				Toast.fire({ icon: 'error', title: '¡La fecha de inicio no puede ser superior a la fecha tope!' });
				fecha_inicio.focus();
			} else {
				location.href = `${url_}/servicios_tecnico?fecha_inicio=${fecha_inicio.value}&fecha_tope=${fecha_final.value}`;
			}
		});
	}



	/**
	 * BUSCAR CLIENTE
	 */
	const btn_modal_cliente = document.getElementById("btn_modal_cliente");
	const btn_borrar_cliente = document.getElementById("btn_borrar_cliente");
	const modal_buscar_cliente = document.getElementById("modal_buscar_cliente") != null ? new bootstrap.Modal('#modal_buscar_cliente') : null;
	const input_buscar_cliente = document.getElementById("input_buscar_cliente");
	const btn_buscar_cliente = document.getElementById("btn_buscar_cliente");
	const tabla_clientes = document.querySelector("#tabla_clientes tbody");

	// Eventos elementos HTML.
	// Abrir modal para buscar clientes.
	if (btn_modal_cliente != null) {
		btn_modal_cliente.addEventListener("click", (e) => {
			e.preventDefault();

			// Ocultamos la ventana principa y procedemos a mostrar la ventana de búsqueda con los campos limpios.
			input_buscar_cliente.value = "";
			tabla_clientes.innerHTML = `<tr><td colspan="5" class="text-center"><i class="fas fa-clock me-2"></i> Esperando la consulta</td></tr>`;
			modal_registrar.hide();
			setTimeout(() => modal_buscar_cliente.show(), 200);
		});
	}

	// Cuando se oculte este registrar, se cargará nuevamente la ventana principal correspondiente.
	if (document.getElementById('modal_buscar_cliente') != null) {
		document.getElementById('modal_buscar_cliente').addEventListener('hidden.bs.modal', event => {
			setTimeout(() => modal_registrar.show(), 200);
		});
	}

	// Evento al presionar la tecla "Enter" buscar los clientes.
	if (input_buscar_cliente != null) {
		input_buscar_cliente.addEventListener("keypress", (e) => e.keyCode == 13 ? btn_buscar_cliente.click() : null);
	}

	// Procedemos a buscar los clientes en la base de datos.
	if (btn_buscar_cliente != null) {
		btn_buscar_cliente.addEventListener("click", function (e) {
			e.preventDefault();

			// Válidamos primero que el campo no este vacío.
			if (input_buscar_cliente.value == "") {
				Toast.fire({ icon: 'error', title: 'Ingrese el RIF, Cédula o nombre del cliente a buscar' });
				input_buscar_cliente.focus();
				tabla_clientes.innerHTML = `<tr><td colspan="5" class="text-center"><i class="fas fa-clock me-2"></i> Esperando la consulta</td></tr>`;
			} else {
				btn_buscar_cliente.classList.add('loading');
				fetch(`${url_}/servicios_tecnico/clientes/${input_buscar_cliente.value}`).then(response => response.json()).then((data) => {
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
	}

	// Borrar los datos del cliente del modal de servicios solicitados.
	if (btn_borrar_cliente != null) {
		btn_borrar_cliente.addEventListener('click', (e) => {
			e.preventDefault();
			btn_modal_cliente.style.display = "";
			btn_borrar_cliente.style.display = "none";
			document.getElementById('c_codigo_r').value = "";
			document.getElementById("c_codigo_auxr").value = "";
			document.getElementById('c_cliente_r').value = "";
		});
	}

	// Al presionar algunos de los clientes, cargar los datos en el formulario.
	function seleccionar_cliente() {
		const button_ = this;
		const codigo = button_.getAttribute('data-cliente');

		// Realizamos la consulta a la base de datos.
		button_.classList.add('loading');
		button_.setAttribute('disabled', true);
		fetch(`${url_}/servicios_tecnico/mapa_de_zona/${codigo}`).then(response => response.json()).then((data) => {
			button_.classList.remove('loading');
			button_.removeAttribute('disabled');

			// Válidamos si realmente se encontraba la información del cliente.
			if (data == null) {
				Toast.fire({ icon: 'error', title: '¡Ocurrió un error al consultar la información del cliente!' });
				return;
			}

			// Cerramos la modal.
			btn_modal_cliente.style.display = "none";
			btn_borrar_cliente.style.display = "";
			document.getElementById('c_codigo_r').value = data.idcodigo;
			document.getElementById("c_codigo_auxr").value = data.idcodigo;
			document.getElementById('c_cliente_r').value = data.identificacion + ' - ' + data.nombre;
			modal_buscar_cliente.hide();
		});
	}
})();