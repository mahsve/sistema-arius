(function () {
	/**
	 * REGISTRAR CLIENTE
	 */
	// Elementos HTML.
	const btn_abrir_registrar_cliente = document.getElementById("btn_abrir_registrar_cliente");
	const modal_registrar_cliente = new bootstrap.Modal(document.getElementById("modal_registrar_cliente"));
	const formulario_registro_cl = document.getElementById("formulario_registro_cl");
	const c_tipo_identificacion_ = document.getElementById("c_tipo_identificacion");
	const btn_registrar_cliente = document.getElementById("btn_registrar_cliente");
	const cl_tipo_identificacion_ = document.getElementById("cl_tipo_identificacion");

	// Mascaras.
	var identificacionMask = IMask(document.getElementById('c_identificacion'), { mask: '00000000' });
	const telefono1Mask = IMask(document.getElementById('c_telefono1'), { mask: '000-0000' });
	const telefono2Mask = IMask(document.getElementById('c_telefono2'), { mask: '000-0000' });

	// Eventos elementos HTML.
	// Abrir la modal para registrar un nuevo cliente.
	btn_abrir_registrar_cliente.addEventListener('click', function (e) {
		e.preventDefault();

		// Limpiamos el formulario y abrimos la ventana para registrar nuevo cliente.
		formulario_registro_cl.reset();
		c_tipo_identificacion_.dispatchEvent(new Event('change')); // Disparamos el evento change para restablecer el tipo de identificacion.
		modal_registrar_cliente.show();
	});

	// Cambiar el tipo de identificación de manera dinamica.
	c_tipo_identificacion_.addEventListener("change", function () {
		const label_ = document.querySelector('#contenedor_identificacion label');
		const input_ = document.querySelector('#contenedor_identificacion input');
		const selec_ = document.querySelector('#contenedor_identificacion select');
		selec_.innerHTML = '';
		identificacionMask.destroy();
		if (this.value == "C") {
			label_.innerHTML = '<i class="fas fa-id-badge"></i> Cédula';
			input_.setAttribute("placeholder", "Ingrese la cédula");
			lista_cedula.forEach(text => selec_.innerHTML += `<option value="${text}">${text}</option>`);
			identificacionMask = IMask(document.getElementById('c_identificacion'), { mask: '00000000' });
		} else if (this.value == "R") {
			label_.innerHTML = '<i class="fas fa-id-badge"></i> RIF';
			input_.setAttribute("placeholder", "Ingrese el RIF");
			lista_rif.forEach(text => selec_.innerHTML += `<option value="${text}">${text}</option>`);
			identificacionMask = IMask(document.getElementById('c_identificacion'), { mask: '00000000-0' });
		}
	});

	// Enviar formulario.
	formulario_registro_cl.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos del formulario.
		const c_tipo_identificacion = document.getElementById("c_tipo_identificacion");
		const c_identificacion = document.getElementById("c_identificacion");
		const c_nombre_completo = document.getElementById("c_nombre_completo");
		const c_telefono1 = document.getElementById("c_telefono1");
		const c_telefono2 = document.getElementById("c_telefono2");
		const c_correo_electronico = document.getElementById("c_correo_electronico");
		const c_direccion = document.getElementById("c_direccion");
		const c_referencia = document.getElementById("c_referencia");

		// Validamos los campos.
		if (false) {

		} else {
			btn_registrar_cliente.classList.add("loading");
			btn_registrar_cliente.setAttribute('disabled', true);
			fetch(`${formulario_registro_cl.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro_cl) }).then(response => response.json()).then(data => {
				btn_registrar_cliente.classList.remove("loading");
				btn_registrar_cliente.removeAttribute('disabled');

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					return;
				}

				// Enviamos mensaje de exito.
				Swal.fire({ title: "Exito", text: "Cliente registrado exitosamente", icon: "success", timer: 2000 });

				// Capturamos y gestionamos la información.
				let prefijo_id = data.identificacion.substring(0, 1);
				let identificacion = data.identificacion.substring(2);
				let prefijo_tel1 = data.telefono1.substring(1, 4);
				let telefono1 = data.telefono1.substring(6);
				let prefijo_tel2 = "";
				let telefono2 = "";
				if (data.telefono2 != null && data.telefono2 != "null" && data.telefono2 != "") {
					prefijo_tel2 = data.telefono2.substring(1, 4);
					telefono2 = data.telefono2.substring(6);
				}

				// Ingresamos la información del cliente en el formulario.
				document.getElementById("cl_tipo_identificacion").value = data.tipo_identificacion;
				cl_tipo_identificacion_.dispatchEvent(new Event('change'));
				document.getElementById("cl_prefijo_identificacion").value = prefijo_id;
				document.getElementById("cl_identificacion").value = identificacion;
				document.getElementById("cl_nombre_completo").value = data.nombre;
				document.getElementById("cl_prefijo_telefono1").value = prefijo_tel1;
				document.getElementById("cl_telefono1").value = telefono1;
				document.getElementById("cl_prefijo_telefono2").value = prefijo_tel2;
				document.getElementById("cl_telefono2").value = telefono2;
				document.getElementById("cl_correo_electronico").value = data.correo;
				document.getElementById("c_direccion").value = data.direccion;
				document.getElementById("c_referencia").value = data.referencia;
				document.getElementById("id_cliente").value = data.identificacion;

				// Cerramos la modal.
				modal_registrar_cliente.hide();
			});
		}
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
			btn_buscar_cliente.setAttribute('disabled', true);
			input_buscar_cliente.setAttribute('readonly', true);
			fetch(`${url_}/mapas_de_zonas/clientes/${input_buscar_cliente.value}`).then(response => response.json()).then((data) => {
				btn_buscar_cliente.classList.remove('loading');
				btn_buscar_cliente.removeAttribute('disabled');
				input_buscar_cliente.removeAttribute('readonly');

				// Verificamos si encontró clientes.
				if (data == null) {
					tabla_clientes.innerHTML = `<tr><td colspan="5" class="text-center text-danger"><i class="fas fa-user-times me-2"></i> Clientes no encontrados</td></tr>`;
					return;
				}

				// Recorremos la cadena de resultados y la inyectamos en la tabla HTML.
				tabla_clientes.innerHTML = "";
				for (let i = 0; i < data.length; i++) {
					const cliente = data[i];
					tabla_clientes.innerHTML += `<tr>
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
							? `<button type="button" class="btn btn-primary btn-sm btn-icon btn_cliente_selecccionado" id="btn_agg_cliente_${i}" data-id="${i}" data-cliente="${cliente.identificacion}"><i class="fas fa-plus"></i></button>`
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
		const identificacion = button_.getAttribute('data-cliente');

		// Realizamos la consulta a la base de datos.
		button_.classList.add('loading');
		button_.setAttribute('disabled', true);
		fetch(`${url_}/mapas_de_zonas/cliente/${identificacion}`).then(response => response.json()).then((data) => {
			button_.classList.remove('loading');
			button_.removeAttribute('disabled');

			// Válidamos si realmente se encontraba la información del cliente.
			if (data == null) {
				Toast.fire({ icon: 'error', title: 'Ocurrió un error al consultar la información del cliente' });
				return;
			}

			// Capturamos y gestionamos la información.
			let prefijo_id = data.identificacion.substring(0, 1);
			let identificacion = data.identificacion.substring(2);
			let prefijo_tel1 = data.telefono1.substring(1, 4);
			let telefono1 = data.telefono1.substring(6);
			let prefijo_tel2 = "";
			let telefono2 = "";
			if (data.telefono2 != null && data.telefono2 != "null" && data.telefono2 != "") {
				prefijo_tel2 = data.telefono2.substring(1, 4);
				telefono2 = data.telefono2.substring(6);
			}

			// Ingresamos la información del cliente en el formulario.
			document.getElementById("cl_tipo_identificacion").value = data.tipo_identificacion;
			document.getElementById("cl_prefijo_identificacion").value = prefijo_id;
			document.getElementById("cl_identificacion").value = identificacion;
			document.getElementById("cl_nombre_completo").value = data.nombre;
			document.getElementById("cl_prefijo_telefono1").value = prefijo_tel1;
			document.getElementById("cl_telefono1").value = telefono1;
			document.getElementById("cl_prefijo_telefono2").value = prefijo_tel2;
			document.getElementById("cl_telefono2").value = telefono2;
			document.getElementById("cl_correo_electronico").value = data.correo;
			document.getElementById("c_direccion").value = data.direccion;
			document.getElementById("c_referencia").value = data.referencia;
			document.getElementById("id_cliente").value = data.identificacion;
			// Ejecutamos la función change del tipo de identificación del formulario principal una vez cargado los datos al formulario.
			cl_tipo_identificacion_.dispatchEvent(new Event('change'));

			// Cerramos la modal.
			modal_buscar_cliente.hide();
		});
	}
})();