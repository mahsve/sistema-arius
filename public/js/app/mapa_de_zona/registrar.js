(function () {
	// Elementos HTML.
	const tipo_contrato = document.getElementById("m_tipo_contrato");
	const codigo_manual = document.getElementById("codigo_manual");
	const input_codigo = document.getElementById("m_codigo");

	// Eventos elementos HTML.
	// Consultar el código según el tipo de contrato a realizar.
	tipo_contrato.addEventListener('change', function () {
		// Capturamos el elemento que provoco el evento.
		const select_element = this;

		// Realizamos la consulta AJAX.
		select_element.parentElement.classList.add('loading');
		fetch(`${url_}/mapas_de_zonas/codigo/${select_element.value}`).then(response => response.json()).then((data) => {
			select_element.parentElement.classList.remove('loading');
			// Limpiamos el formulario y cargamos los datos consultados.
			document.getElementById('m_codigo').value = data;
		});
	});

	// Habilitar/Deshabilitar el campo para ingresar código manual.
	codigo_manual.addEventListener("change", function () {
		if (codigo_manual.checked) {
			input_codigo.removeAttribute("readonly");
		} else {
			input_codigo.setAttribute("readonly", true);
		}
	});



	/**
	 * AGREGAR USUARIOS.
	 */
	// Elementos HTML.
	const btn_agregar_usuario = document.getElementById('btn_agregar_usuario');
	const tabla_usuarios = document.querySelector('#tabla_usuarios tbody');
	const tabla_usuarios_vacio = tabla_usuarios.innerHTML;

	// Eventos elementos HTML.
	// Agregar nuevo fila para contactos del cliente.
	btn_agregar_usuario.addEventListener('click', (e) => {
		e.preventDefault();

		// ELEMENTOS.
		if (tabla_usuarios.children.length > 0 && tabla_usuarios.children[0].classList.contains('sin_usuarios')) tabla_usuarios.innerHTML = '';

		// GENERAMOS UN NUEVO ELEMENTO.
		const idrand = Math.random().toString().replace('.', ''); // GENERAMOS UN ID UNICO PARA MANEJAR LA FILA DEL USUARIO.
		const elemento = document.createElement('tr'); // GENERAMOS UN NUEVO ELEMENTO.
		elemento.id = `tr_usuario_${idrand}`;

		// Definimos toda la estructura de la nueva fila.
		elemento.innerHTML = `
			<td class="py-1 px-2 text-center n_usuarios">${tabla_usuarios.children.length + 1}</td>
			<td class="py-1 px-2">
				<div class="form-group input-group m-0">
					<select class="form-control text-uppercase text-center" name="usuario_prefijo_id[]" id="usuario_prefijoid_${idrand}" data-id="${idrand}" style="height: 31px; margin-top: 1px;">
						${lista_cedula.map(cd => `<option value="${cd}">${cd}</option>`).join('')}
					</select>
					<input type="text" class="form-control text-uppercase" name="usuario_cedula[]" id="usuario_cedula_${idrand}" data-id="${idrand}" placeholder="Cédula" style="width: calc(100% - 65px); height: 33px;">
				</div>
			</td>
			<td class="py-1 px-2"><input type="text" class="form-control text-uppercase" name="usuario_nombre[]" id="usuario_nombre_${idrand}" placeholder="Nombre completo"></td>
			<td class="py-1 px-2"><input type="text" class="form-control text-uppercase" name="usuarios_contrasena[]" id="usuarios_contrasena_${idrand}" placeholder="Contraseña"></td>
			<td class="py-1 px-2">
				<div class="input-group">
					<select class="form-control text-center" name="usuario_prefijotl[]" id="usuario_prefijotl_${idrand}" style="height: 31px; margin-top: 1px;">
						<option value="">COD.</option>
						<optgroup label="Móvil">
							${lista_prefijos['Móvil'].map(hg => `<option value="${hg}">${hg}</option>`).join('')}
						</optgroup>
						<optgroup label="Hogar">
							${lista_prefijos['Hogar'].map(hg => `<option value="${hg}">${hg}</option>`).join('')}
						</optgroup>
					</select>
					<input type="text" class="form-control text-uppercase" name="usuarios_telefono[]" id="usuarios_telefono_${idrand}" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
				</div>
			</td>
			<td class="py-1 px-2"><input type="text" class="form-control text-uppercase" name="usuarios_nota[]" id="usuarios_nota_${idrand}" placeholder="Nota (Opcional)"></td>
			<td class="py-1 px-2" style="width: 20px;">
				<button type="button" class="btn btn-danger btn-sm" id="btn_eliminar_usuario_${idrand}" data-id="${idrand}"><i class="fas fa-times"></i></button>
			</td>
		`;
		tabla_usuarios.appendChild(elemento);

		// Agregamos los eventos a los elementos agregados a la tabla.
		document.getElementById(`usuario_prefijoid_${idrand}`).addEventListener("change", consultar_usuario);
		document.getElementById(`usuario_cedula_${idrand}`).addEventListener("change", consultar_usuario);
		document.getElementById(`btn_eliminar_usuario_${idrand}`).addEventListener("click", eliminar_usuario);

		// Agregamos mascaras a los campos.
		IMask(document.getElementById(`usuario_cedula_${idrand}`), { mask: '00000000' })
		IMask(document.getElementById(`usuarios_telefono_${idrand}`), { mask: '000-0000' })
	});

	// Consultamos si ya existe registrado el usuario como cliente en la base de datos según la tabla de usuarios de contactos.
	function consultar_usuario() {
		// Capturamos el elemento que provoco el evento.
		const idrand = this.getAttribute('data-id');
		const campo_prefijo = document.getElementById(`usuario_prefijoid_${idrand}`);
		const campo_cedula = document.getElementById(`usuario_cedula_${idrand}`);

		// Válidamos primeramente que el tipo de nacionalidad no este vacía y el número de cédula tampoco.
		if (campo_prefijo.value != "" && campo_cedula.value != "" && campo_cedula.value.length >= 7) {
			// Realizamos la consulta AJAX.
			let union_cedula = campo_prefijo.value + "-" + campo_cedula.value;
			campo_cedula.parentElement.classList.add('loading');
			fetch(`${url_}/mapas_de_zonas/cedula/${union_cedula}`).then(response => response.json()).then((data) => {
				campo_cedula.parentElement.classList.remove('loading');

				// Verificamos si encontró algún usuario y cargamos los datos del usuario.
				if (data != null) {
					// Capturamos el telefono y lo dividimos en dos campos.
					let prefijo_tel1 = data.telefono1.substring(1, 4);
					let telefono1 = data.telefono1.substring(6);
					// Llenamos los campos con la información obtenida.
					document.getElementById(`usuario_nombre_${idrand}`).value = data.nombre;
					document.getElementById(`usuario_prefijotl_${idrand}`).value = prefijo_tel1;
					document.getElementById(`usuarios_telefono_${idrand}`).value = telefono1;
					// Deshabilitamos los campos.
					document.getElementById(`usuario_nombre_${idrand}`).setAttribute('readonly', true);
					document.getElementById(`usuario_prefijotl_${idrand}`).setAttribute('readonly', true);
					document.getElementById(`usuario_prefijotl_${idrand}`).setAttribute('disabled', true);
					document.getElementById(`usuarios_telefono_${idrand}`).setAttribute('readonly', true);
					campo_prefijo.setAttribute('readonly', true);
					campo_prefijo.setAttribute('disabled', true);
					campo_cedula.setAttribute('readonly', true);
				}
			});
		}
	}

	// Evento para eliminar usuario de la tabla.
	function eliminar_usuario() {
		// Capturamos el elemento que provoco el evento.
		const idrand = this.getAttribute('data-id');
		document.getElementById(`tr_usuario_${idrand}`).remove();

		// Reordenamos la enumeración del listado.
		Array.from(document.querySelectorAll('.n_usuarios')).forEach((contador, index) => contador.innerHTML = (index + 1));

		// En caso que quede vacío, cargamos un mensaje en la tabla "Sin usuarios".
		if (tabla_usuarios.children.length == 0) tabla_usuarios.innerHTML = tabla_usuarios_vacio;
	};



	/**
	 * AGREGAR ZONAS.
	 */
	// Elementos HTML.
	const btn_agregar_zona = document.getElementById('btn_agregar_zona');
	const tabla_zonas = document.querySelector('#tabla_zonas tbody');
	const tabla_zonas_vacio = tabla_zonas.innerHTML;

	// Eventos elementos HTML.
	// Agregar nuevo fila para contactos del cliente.
	btn_agregar_zona.addEventListener('click', (e) => {
		e.preventDefault();

		// ELEMENTOS.
		if (tabla_zonas.children.length > 0 && tabla_zonas.children[0].classList.contains('sin_zonas')) tabla_zonas.innerHTML = '';

		// GENERAMOS UN NUEVO ELEMENTO.
		const idrand = Math.random().toString().replace('.', ''); // GENERAMOS UN ID UNICO PARA MANEJAR LA FILA DEL USUARIO.
		const elemento = document.createElement('tr'); // GENERAMOS UN NUEVO ELEMENTO.
		elemento.id = `tr_zona_${idrand}`;

		// Definimos toda la estructura de la nueva fila.
		elemento.innerHTML = `
			<td class="py-1 px-2 text-center n_zonas">${tabla_zonas.children.length + 1}</td>
			<td class="py-1 px-2"><input type="text" class="form-control text-uppercase" name="zona_descripcion[]" id="zona_descripcion_${idrand}" placeholder="Descripción de la zona"></td>
			<td class="py-1 px-2">
				<div class="form-group m-0">
					<select class="form-control text-uppercase" name="zona_equipos[]" id="zona_equipos_${idrand}" data-id=${idrand}>
						<option value="0">SELC.  EQUIPO</option>
						${dispositivos.map(dv => `<option value="${dv.iddispositivo}">${dv.dispositivo}</option>`).join('')}
					</select>
				<div>
			</td>
			<td class="py-1 px-2">
				<select class="form-control text-uppercase" name="zona_configuracion[]" id="zona_configuracion_${idrand}" data-id="${idrand}">
					<option value="0">SELC. CONFIGURACIÓN</option>
				</select>
			</td>
			<td class="py-1 px-2"><input type="text" class="form-control text-uppercase" name="zona_nota[]" id="zona_nota_${idrand}" placeholder="Observación (opcional)"></td>
			<td class="py-1 px-2" style="width: 20px;">
				<button type="button" class="btn btn-danger btn-sm" id="btn_eliminar_zona_${idrand}" data-id="${idrand}"><i class="fas fa-times"></i></button>
			</td>
		`;
		tabla_zonas.appendChild(elemento);

		// Agregamos los eventos a los elementos agregados a la tabla.
		document.getElementById(`zona_equipos_${idrand}`).addEventListener("change", consultar_configuracion);
		document.getElementById(`btn_eliminar_zona_${idrand}`).addEventListener("click", eliminar_zona);
	});

	// Consultar configuración según el equipos seleccionado en la tabla.
	function consultar_configuracion() {
		// Capturamos el elemento que provoco el evento.
		const select_element = this;
		const select_id = select_element.getAttribute('data-id');
		const select_cg = document.getElementById(`zona_configuracion_${select_id}`);

		// Realizamos la consulta AJAX.
		select_element.parentElement.classList.add('loading');
		select_cg.innerHTML = '<option value="0">SELC. CONFIGURACIÓN</option>';
		fetch(`${url_}/mapas_de_zonas/configuracion/${select_element.value}`).then(response => response.json()).then((data) => {
			select_element.parentElement.classList.remove('loading');

			// Verificamos si encontró la información del usuario.
			if (data == null) {
				Toast.fire({ icon: 'warning', title: 'Este equipo no tiene configuraciones agregadas' });
				return;
			}

			// Cargamos los datos al select.
			for (let i = 0; i < data.length; i++) {
				const cg = data[i];
				select_cg.innerHTML += `<option value="${cg.idconfiguracion}">${cg.configuracion}</option>`;
			}
		});
	}

	// Evento para eliminar zona de la tabla.
	function eliminar_zona() {
		// Capturamos el elemento que provoco el evento.
		const idrand = this.getAttribute('data-id');
		document.getElementById(`tr_zona_${idrand}`).remove();

		// Reordenamos la enumeración del listado.
		Array.from(document.querySelectorAll('.n_zonas')).forEach((contador, index) => contador.innerHTML = (index + 1));

		// En caso que quede vacío, cargamos un mensaje en la tabla "Sin zonas".
		if (tabla_zonas.children.length == 0) tabla_zonas.innerHTML = tabla_zonas_vacio;
	}



	/**
	 * REGISTRAR CLIENTE
	 */
	// const btn_abrir_registrar_cliente = document.getElementById("btn_abrir_registrar_cliente");
	// const modal_registrar_cliente = new bootstrap.Modal(document.getElementById("modal_registrar_cliente"));

	// Elementos HTML.
	const formulario_registro_cl = document.getElementById("formulario_registro_cl");
	const c_tipo_identificacion_ = document.getElementById("c_tipo_identificacion");
	const btn_guardar_cl = document.getElementById("btn_guardar_cl");

	// Mascaras.
	var identificacionMask = IMask(document.getElementById('c_identificacion'), { mask: '00000000' });
	const telefono1Mask = IMask(document.getElementById('c_telefono1'), { mask: '000-0000' });
	const telefono2Mask = IMask(document.getElementById('c_telefono2'), { mask: '000-0000' });

	// Eventos elementos HTML.
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
			btn_guardar_cl.classList.add("loading");
			fetch(`${formulario_registro_cl.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro_cl) }).then(response => response.json()).then(data => {
				btn_guardar_cl.classList.remove("loading");
				console.log(data);

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({
						icon: data.status,
						title: data.response.message
					});
					return false;
				}

				// Enviamos mensaje de exito.
				Swal.fire({
					title: "Exito",
					text: "Cliente registrado exitosamente",
					icon: "success",
					timer: 2000
				});

				setTimeout(() => location.href = `${url_}/clientes`, 2000);
			});
		}
	});

	// Ejecutamos los eventos necesarios [Una sola vez].
	c_tipo_identificacion_.dispatchEvent(new Event('change'));

	

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
			fetch(`${url_}/mapas_de_zonas/clientes/${input_buscar_cliente.value}`).then(response => response.json()).then((data) => {
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
							? `<button type="button" class="btn btn-primary btn-sm btn_cliente_selecccionado p-2" id="btn_agg_cliente_${i}" data-id="${i}" data-cliente="${cliente.identificacion}"><i class="fas fa-plus"></i></button>`
							: '<button type="button" class="btn btn-danger btn-sm p-2"><i class="fas fa-ban"></i></button>'
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
		fetch(`${url_}/mapas_de_zonas/cliente/${identificacion}`).then(response => response.json()).then((data) => {
			button_.classList.remove('loading');

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
			document.getElementById("cl_tipo_identificacion").value = tipos_identificaciones[data.tipo_identificacion];
			document.getElementById("cl_prefijo_identificacion").value = prefijo_id;
			document.getElementById("cl_identificacion").value = identificacion;
			document.getElementById("cl_nombre_completo").value = data.nombre;
			document.getElementById("cl_prefijo_telefono1").value = prefijo_tel1;
			document.getElementById("cl_telefono1").value = telefono1;
			document.getElementById("cl_prefijo_telefono2").value = prefijo_tel2;
			document.getElementById("cl_telefono2").value = telefono2;
			document.getElementById("cl_correo_electronico").value = data.correo;

			// Cerramos la modal.
			modal_buscar_cliente.hide();
		});
	}
})();