(function () {
	// Elementos HTML.
	const btn_guardar_up = document.getElementById('btn_guardar');
	const btn_agregar_evento = document.getElementById('btn_agregar_evento');
	const add_html_eventos = document.getElementById('add_html_eventos');
	const codigo_r = document.getElementById("c_codigo_r");
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const formulario_registro = document.getElementById("formulario_registro");
	const modal_modificar = document.getElementById('modal_modificar') != null ? new bootstrap.Modal('#modal_modificar') : null;
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");
	const formulario_gestionar = document.getElementById("formulario_gestionar");
	// Elementos de la tabla.
	const tabla_eventos = document.querySelector('#tabla_eventos tbody');
	const tabla_eventos_vacio = tabla_eventos.innerHTML;

	// Registrar dato.
	if (btn_agregar_evento != null) {
		btn_agregar_evento.addEventListener('click', function (e) {
			e.preventDefault();

			// Limpiamos el formulario y mostramos la ventana emergente.
			formulario_registro.reset();
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
			const c_cliente = document.getElementById("c_cliente_r")
			const c_hora = document.getElementById("c_hora_r");
			const c_evento = document.getElementById("c_evento_r");
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
			} else if (c_hora.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese la hora del evento ocurrido!' });
				c_hora.focus();
			} else if (c_evento.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese la descripción del evento!' });
				c_hora.focus();
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
					});

					// Añadimos el evento en el HTML.
					evento_html();
					// Capturamos el ID rand para acceder a los id de cada td y empezar a rellenar los datos.
					const tc = document.querySelectorAll('#tabla_eventos tbody tr').length; // Capturamos el total de tr en la tabla.
					const idrand = document.querySelectorAll('#tabla_eventos tbody tr')[tc - 1].getAttribute('data-rand');
					document.getElementById(`evento_codigo_${idrand}`).innerHTML = c_codigo.value;
					document.getElementById(`evento_hora_${idrand}`).innerHTML = hora(c_hora.value);
					document.getElementById(`evento_cliente_${idrand}`).innerHTML = c_cliente.value.split(' - ')[1];
					document.getElementById(`evento_evento_${idrand}`).innerHTML = c_evento.value;
					document.getElementById(`btn_editar_evento_${idrand}`).setAttribute('data-id', data.response.data.id);
					document.getElementById(`btn_eliminar_evento_${idrand}`).setAttribute('data-id', data.response.data.id);
				});
			}
		});
	}

	// Botón para añadir html en 
	if (add_html_eventos != null) {
		add_html_eventos.addEventListener('click', (e) => {
			e.preventDefault();
			evento_html();
		});
	}

	// Agregar el html del evento registrado en el DOM.
	function evento_html() {
		// ELEMENTOS.
		if (tabla_eventos.children.length > 0 && tabla_eventos.children[0].classList.contains('sin_eventos')) tabla_eventos.innerHTML = '';

		// GENERAMOS UN NUEVO ELEMENTO.
		const idrand = Math.random().toString().replace('.', ''); // GENERAMOS UN ID UNICO PARA MANEJAR LA FILA DEL USUARIO.
		const elemento = document.createElement('tr'); // GENERAMOS UN NUEVO ELEMENTO.
		elemento.id = `tr_evento_${idrand}`;
		elemento.setAttribute('data-rand', idrand);

		// Definimos toda la estructura de la nueva fila.
		elemento.innerHTML = `
			<td class="py-1 px-2 text-center h_eventos"><i class="fas fa-arrows-alt"></i></td>
			<td class="py-1 px-2 text-center n_eventos" id="evento_norden_${idrand}">${tabla_eventos.children.length + 1}</td>
			<td class="py-1 px-2 text-center">
				<input type="hidden" name="evento_orden[]" id="evento_orden_${idrand}" value="${tabla_eventos.children.length + 1}" class="evento_orden" data-id="${idrand}">
				<span id="evento_codigo_${idrand}"></span>
			</td>
			<td class="py-1 px-2"><span id="evento_hora_${idrand}"></span></td>
			<td class="py-1 px-2"><span id="evento_cliente_${idrand}" class="d-block text-truncate" style="max-width: 200px;"></span></td>
			<td class="py-1 px-2"><span id="evento_evento_${idrand}" class="d-block text-truncate"></span></td>
			<td class="py-1 px-2" style="width: 20px;">
				<button type="button" class="btn btn-primary btn-sm btn-icon" id="btn_editar_evento_${idrand}" data-rand="${idrand}"><i class="fas fa-edit"></i></button>
				<button type="button" class="btn btn-danger btn-sm btn-icon" id="btn_eliminar_evento_${idrand}" data-rand="${idrand}"><i class="fas fa-times"></i></button>
			</td>
		`;
		tabla_eventos.appendChild(elemento);

		// Agregamos los eventos a los elementos agregados a la tabla.
		document.getElementById(`btn_editar_evento_${idrand}`).addEventListener("click", consultar_evento);
		document.getElementById(`btn_eliminar_evento_${idrand}`).addEventListener("click", eliminar_evento);
	}

	// Consultar los datos del evento para proceder a editarlos.
	function consultar_evento(e) {
		e.preventDefault();

		// Capturamos el elemento que provoco el evento.
		const btn_consultar = this;
		const id_data = btn_consultar.getAttribute('data-id');
		const id_rand = btn_consultar.getAttribute('data-rand');

		// Realizamos la consulta AJAX.
		btn_consultar.classList.add('loading');
		btn_consultar.setAttribute('disabled', true);
		fetch(`${url_}/monitoreo/evento/${id_data}`, { method: 'get' }).then(response => response.json()).then((data) => {
			btn_consultar.classList.remove('loading');
			btn_consultar.removeAttribute('disabled');

			// Limpiamos el formulario y cargamos los datos consultados.
			formulario_actualizacion.reset();
			formulario_actualizacion.setAttribute('action', `${url_}/monitoreo/evento/${id_data}`);
			document.getElementById('c_codigo_m').value = data.idcodigo;
			document.getElementById('c_cliente_m').value = data.identificacion + " - " + data.nombre;
			document.getElementById('c_hora_m').value = data.hora;
			document.getElementById('c_evento_m').value = data.evento;
			document.getElementById('btn_modificar').setAttribute('data-rand', id_rand);
			modal_modificar.show();
		});
	}

	// Modificar dato.
	if (formulario_actualizacion != null) {
		formulario_actualizacion.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_hora = document.getElementById("c_hora_m");
			const c_evento = document.getElementById("c_evento_m");
			const btn_guardar = document.getElementById("btn_modificar");

			// Validamos los campos.
			if (c_hora.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese la hora del evento ocurrido!' });
				c_hora.focus();
			} else if (c_evento.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese la descripción del evento!' });
				c_hora.focus();
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
					});

					// Capturamos el ID rand para acceder a los id de cada td y empezar a rellenar los datos.
					const idrand = btn_guardar.getAttribute('data-rand');
					document.getElementById(`evento_hora_${idrand}`).innerHTML = hora(c_hora.value);
					document.getElementById(`evento_evento_${idrand}`).innerHTML = c_evento.value;
				});
			}
		});
	}

	function eliminar_evento(e) {
		e.preventDefault();

		// Capturamos el elemento que provoco el evento.
		const btn_consultar = this;
		const id_data = btn_consultar.getAttribute('data-id');
		const id_rand = btn_consultar.getAttribute('data-rand');
		const formData = new FormData();
		formData.append('_method', 'DELETE');

		Swal.fire({
			icon: 'question',
			title: "¿Seguro que quieres eliminar este evento?",
			showCancelButton: true,
			confirmButtonText: "Eliminar",
			cancelButtonText: "Cancelar",
		}).then((result) => {
			if (result.isConfirmed) {
				// Realizamos la consulta AJAX.
				btn_consultar.classList.add('loading');
				btn_consultar.setAttribute('disabled', true);
				fetch(`${url_}/monitoreo/evento/${id_data}`, {
					headers: { 'X-CSRF-TOKEN': token_ },
					method: 'post',
					body: formData
				}).then(response => response.json()).then((data) => {
					btn_consultar.classList.remove('loading');
					btn_consultar.removeAttribute('disabled');

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
					});

					// Capturamos el ID rand para acceder a los id de cada td y empezar a rellenar los datos.
					document.getElementById(`tr_evento_${id_rand}`).remove();
				});
			}
		});
	}

	// Guardar los cambios en general del reporte diario.
	if (btn_guardar_up != null) {
		btn_guardar_up.addEventListener("click", function (e) {
			e.preventDefault();

			btn_guardar_up.classList.add("loading");
			btn_guardar_up.classList.add('disabled');
			fetch(`${formulario_gestionar.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_gestionar) }).then(response => response.json()).then(data => {
				btn_guardar_up.classList.remove("loading");
				btn_guardar_up.classList.remove('disabled');

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
				});
			});
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