(function () {
	// Elementos HTML.
	const tipo_contrato = document.getElementById("m_tipo_contrato");
	const codigo_manual = document.getElementById("codigo_manual");
	const input_codigo = document.getElementById("m_codigo");
	const btn_next = document.getElementById("btn_next");
	const btn_prev = document.getElementById("btn_prev");
	const cl_tipo_identificacion_ = document.getElementById("cl_tipo_identificacion");

	// Activar plugins
	const telefonoAsigMask = IMask(document.getElementById('c_telefono_assig'), { mask: '000-0000' });
	const codigoMapaMask = IMask(input_codigo, { mask: '0000' });

	// Eventos elementos HTML.
	// Consultar el código según el tipo de contrato a realizar.
	tipo_contrato.addEventListener('change', function () {
		// Capturamos el elemento que provoco el evento.
		const select_element = this;

		// Verificamos primeramente que el campo no sea vacío.
		if (select_element.value != "") {
			// Realizamos la consulta AJAX.
			select_element.parentElement.classList.add('loading');
			fetch(`${url_}/mapas_de_zonas/codigo/${select_element.value}`).then(response => response.json()).then((data) => {
				select_element.parentElement.classList.remove('loading');
				// Limpiamos el formulario y cargamos los datos consultados.
				document.getElementById('m_codigo').value = data;
			});
		} else {
			document.getElementById('m_codigo').value = "";
		}
	});

	// Habilitar/Deshabilitar el campo para ingresar código manual.
	codigo_manual.addEventListener("change", function () {
		if (codigo_manual.checked) {
			input_codigo.removeAttribute("readonly");
		} else {
			input_codigo.setAttribute("readonly", true);
		}
	});

	// Cambiar el tipo de identificación de manera dinamica.
	cl_tipo_identificacion_.addEventListener("change", function () {
		const label_ = document.querySelector('#contenedor_identificacion_f label');
		const input_ = document.querySelector('#contenedor_identificacion_f input');
		const selec_ = document.querySelector('#contenedor_identificacion_f select');
		selec_.innerHTML = '';
		if (this.value == "C") {
			label_.innerHTML = '<i class="fas fa-id-badge"></i> Cédula';
			input_.setAttribute("placeholder", "Ingrese la cédula");
			lista_cedula.forEach(text => selec_.innerHTML += `<option value="${text}">${text}</option>`);
		} else if (this.value == "R") {
			label_.innerHTML = '<i class="fas fa-id-badge"></i> RIF';
			input_.setAttribute("placeholder", "Ingrese el RIF");
			lista_rif.forEach(text => selec_.innerHTML += `<option value="${text}">${text}</option>`);
		}
	});

	// Disparamos el evento change para restablecer el tipo de identificacion [Solo una vez al abrir el formulario].
	cl_tipo_identificacion_.dispatchEvent(new Event('change'));



	/**
	 * USUARIOS.
	 */
	// Elementos HTML.
	const btn_agregar_usuario = document.getElementById('btn_agregar_usuario');
	const tabla_usuarios = document.querySelector('#tabla_usuarios tbody');
	const tabla_usuarios_vacio = tabla_usuarios.innerHTML;
	const listaUsuarios = new Sortable(tabla_usuarios, {
		handle: '.h_usuarios',
		onUpdate: function (evt) {
			Array.from(document.querySelectorAll('.usuario_orden')).forEach((input, index) => {
				const idrand = input.getAttribute('data-id');
				document.querySelector(`#usuario_norden_${idrand}`).innerHTML = (index + 1);
				input.value = (index + 1);
			});
		}
	});

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
		elemento.setAttribute('data-rand', idrand);

		// Definimos toda la estructura de la nueva fila.
		elemento.innerHTML = `
			<td class="py-1 px-2 text-center h_usuarios"><i class="fas fa-arrows-alt"></i></td>
			<td class="py-1 px-2 text-center n_usuarios" id="usuario_norden_${idrand}">${tabla_usuarios.children.length + 1}</td>
			<td class="py-1 px-2">
				<input type="hidden" name="idcontacto[]" id="idcontacto_${idrand}">
				<input type="hidden" name="usuario_rcedula[]" id="usuario_rcedula_${idrand}">
				<input type="hidden" name="usuario_orden[]" id="usuario_orden_${idrand}" value="${tabla_usuarios.children.length + 1}" class="usuario_orden" data-id="${idrand}">
				<div class="form-group input-group m-0" style="min-width: 145px;">
					<select class="form-control text-uppercase text-center usuario_prefijo_id" name="usuario_prefijo_id[]" id="usuario_prefijoid_${idrand}" data-id="${idrand}" style="height: 31px; margin-top: 1px;">
						${lista_cedula.map(cd => `<option value="${cd}">${cd}</option>`).join('')}
					</select>
					<input type="text" class="form-control text-uppercase usuario_cedula" name="usuario_cedula[]" id="usuario_cedula_${idrand}" data-id="${idrand}" placeholder="Cédula" style="width: calc(100% - 65px); height: 33px;">
				</div>
			</td>
			<td class="py-1 px-2"><input type="text" class="form-control text-uppercase usuario_nombre" name="usuario_nombre[]" id="usuario_nombre_${idrand}" placeholder="Nombre completo"></td>
			<td class="py-1 px-2"><input type="text" class="form-control usuario_contrasena" name="usuario_contrasena[]" id="usuario_contrasena_${idrand}" placeholder="Contraseña"></td>
			<td class="py-1 px-2">
				<div class="input-group" style="min-width: 160px;">
					<select class="form-control text-center usuario_prefijotl" name="usuario_prefijotl[]" id="usuario_prefijotl_${idrand}" style="height: 31px; margin-top: 1px;">
						<option value="">COD.</option>
						<optgroup label="Móvil">
							${lista_prefijos['Móvil'].map(hg => `<option value="${hg}">${hg}</option>`).join('')}
						</optgroup>
						<optgroup label="Hogar">
							${lista_prefijos['Hogar'].map(hg => `<option value="${hg}">${hg}</option>`).join('')}
						</optgroup>
					</select>
					<input type="text" class="form-control text-uppercase usuarios_telefono" name="usuarios_telefono[]" id="usuarios_telefono_${idrand}" placeholder="Ingrese el teléfono" style="width: calc(100% - 100px); height: 33px;">
				</div>
			</td>
			<td class="py-1 px-2"><input type="text" class="form-control usuarios_nota" name="usuarios_nota[]" id="usuarios_nota_${idrand}" style="min-width: 200px;" placeholder="Nota (Opcional)"></td>
			<td class="py-1 px-2" style="width: 20px;">
				<button type="button" class="btn btn-danger btn-sm btn-icon" id="btn_eliminar_usuario_${idrand}" data-id="${idrand}"><i class="fas fa-times"></i></button>
			</td>
		`;
		tabla_usuarios.appendChild(elemento);

		// Agregamos los eventos a los elementos agregados a la tabla.
		document.getElementById(`usuario_prefijoid_${idrand}`).addEventListener("change", consultar_usuario);
		document.getElementById(`usuario_cedula_${idrand}`).addEventListener("change", consultar_usuario);
		document.getElementById(`btn_eliminar_usuario_${idrand}`).addEventListener("click", eliminar_usuario);

		// Eliminar border error.
		document.getElementById(`usuario_cedula_${idrand}`).addEventListener('change', function () { this.classList.remove('border-danger') });
		document.getElementById(`usuario_nombre_${idrand}`).addEventListener('change', function () { this.classList.remove('border-danger') });
		document.getElementById(`usuario_prefijotl_${idrand}`).addEventListener('change', function () { this.classList.remove('border-danger') });
		document.getElementById(`usuarios_telefono_${idrand}`).addEventListener('change', function () { this.classList.remove('border-danger') });

		// Agregamos mascaras a los campos.
		IMask(document.getElementById(`usuario_cedula_${idrand}`), { mask: '00000000' });
		IMask(document.getElementById(`usuarios_telefono_${idrand}`), { mask: '000-0000' });
	});

	// Consultamos si ya existe registrado el usuario como cliente en la base de datos según la tabla de usuarios de contactos.
	function consultar_usuario() {
		// Capturamos el elemento que provoco el evento.
		const idrand = this.getAttribute('data-id');
		const campo_prefijo = document.getElementById(`usuario_prefijoid_${idrand}`);
		const campo_cedula = document.getElementById(`usuario_cedula_${idrand}`);

		// Válidamos primeramente que el tipo de nacionalidad no este vacía y el número de cédula tampoco.
		if (campo_cedula.value == "") {
			Toast.fire({ icon: 'error', title: '¡Ingrese un número de cédula!' });
			campo_cedula.focus();
		} else if (campo_cedula.value.length < 8) {
			Toast.fire({ icon: 'error', title: '¡El número de cédula debe tener 8 dígitos!' });
			campo_cedula.focus();
		} else {
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
					document.getElementById(`usuario_rcedula_${idrand}`).value = data.identificacion;
					document.getElementById(`usuario_nombre_${idrand}`).value = data.nombre;
					document.getElementById(`usuario_prefijotl_${idrand}`).value = prefijo_tel1;
					document.getElementById(`usuarios_telefono_${idrand}`).value = telefono1;
					// Deshabilitamos los campos.
					document.getElementById(`usuario_nombre_${idrand}`).setAttribute('disabled', true);
					document.getElementById(`usuario_prefijotl_${idrand}`).setAttribute('disabled', true);
					document.getElementById(`usuario_prefijotl_${idrand}`).setAttribute('disabled', true);
					document.getElementById(`usuarios_telefono_${idrand}`).setAttribute('disabled', true);
					campo_prefijo.setAttribute('disabled', true);
					campo_prefijo.setAttribute('disabled', true);
					campo_cedula.setAttribute('disabled', true);
					// Quitamos border rojo al consultar los datos y encontrar el registro.
					document.getElementById(`usuario_nombre_${idrand}`).classList.remove('border-danger');
					document.getElementById(`usuario_prefijotl_${idrand}`).classList.remove('border-danger');
					document.getElementById(`usuarios_telefono_${idrand}`).classList.remove('border-danger');
				}
			});
		}
	}

	// Evento para eliminar usuario de la tabla.
	function eliminar_usuario() {
		// Capturamos el elemento que provoco el evento.
		const idrand = this.getAttribute('data-id');
		document.getElementById(`tr_usuario_${idrand}`).remove();

		// En caso que quede vacío, cargamos un mensaje en la tabla "Sin usuarios".
		if (tabla_usuarios.children.length == 0) {
			tabla_usuarios.innerHTML = tabla_usuarios_vacio;
			return;
		}

		// Reordenamos la enumeración del listado.
		Array.from(document.querySelectorAll('.usuario_orden')).forEach((input, index) => {
			const idrand = input.getAttribute('data-id');
			document.querySelector(`#usuario_norden_${idrand}`).innerHTML = (index + 1);
			input.value = (index + 1);
		});
	};



	/**
	 * ZONAS.
	 */
	// Elementos HTML.
	const btn_agregar_zona = document.getElementById('btn_agregar_zona');
	const tabla_zonas = document.querySelector('#tabla_zonas tbody');
	const tabla_zonas_vacio = tabla_zonas.innerHTML;
	const listaZonas = new Sortable(tabla_zonas, {
		handle: '.h_zonas',
		onUpdate: function (evt) {
			Array.from(document.querySelectorAll('.zona_orden')).forEach((input, index) => {
				const idrand = input.getAttribute('data-id');
				document.querySelector(`#zona_norden_${idrand}`).innerHTML = (index + 1);
				input.value = (index + 1);
			});
		}
	});

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
		elemento.setAttribute('data-rand', idrand);

		// Definimos toda la estructura de la nueva fila.
		elemento.innerHTML = `
			<td class="py-1 px-2 text-center h_zonas"><i class="fas fa-arrows-alt"></i></td>
			<td class="py-1 px-2 text-center n_zonas" id="zona_norden_${idrand}">${tabla_zonas.children.length + 1}</td>
			<td class="py-1 px-2">
				<input type="hidden" name="idzona[]" id="idzona_${idrand}">
				<input type="hidden" name="zona_orden[]" id="zona_orden_${idrand}" value="${tabla_zonas.children.length + 1}" class="zona_orden" data-id="${idrand}">
				<input type="text" class="form-control text-uppercase zona_descripcion" name="zona_descripcion[]" id="zona_descripcion_${idrand}" placeholder="Descripción de la zona" style="min-width: 150px;">
			</td>
			<td class="py-1 px-2">
				<div class="form-group m-0" style="min-width: 150px;">
					<select class="form-control text-uppercase zona_equipos" name="zona_equipos[]" id="zona_equipos_${idrand}" data-id=${idrand}>
						<option value="0">SELC.  EQUIPO</option>
						${dispositivos.map(dv => dv.tipo == 'Z' ? `<option value="${dv.iddispositivo}">${dv.dispositivo}</option>` : '').join('')}
					</select>
				<div>
			</td>
			<td class="py-1 px-2">
				<select class="form-control text-uppercase zona_configuracion" name="zona_configuracion[]" id="zona_configuracion_${idrand}" data-id="${idrand}" style="min-width: 150px;">
					<option value="0">SELC. CONFIGURACIÓN</option>
				</select>
			</td>
			<td class="py-1 px-2"><input type="text" class="form-control zona_nota" name="zona_nota[]" id="zona_nota_${idrand}" placeholder="Observación (opcional)" style="min-width: 200px;"></td>
			<td class="py-1 px-2" style="width: 20px;">
				<button type="button" class="btn btn-danger btn-sm btn-icon" id="btn_eliminar_zona_${idrand}" data-id="${idrand}"><i class="fas fa-times"></i></button>
			</td>
		`;
		tabla_zonas.appendChild(elemento);

		// Agregamos los eventos a los elementos agregados a la tabla.
		document.getElementById(`zona_equipos_${idrand}`).addEventListener("change", consultar_configuracion);
		document.getElementById(`btn_eliminar_zona_${idrand}`).addEventListener("click", eliminar_zona);

		// 
		document.getElementById(`zona_descripcion_${idrand}`).addEventListener('change', function () { this.classList.remove('border-danger') });
		document.getElementById(`zona_equipos_${idrand}`).addEventListener('change', function () { this.classList.remove('border-danger') });
		document.getElementById(`zona_configuracion_${idrand}`).addEventListener('change', function () { this.classList.remove('border-danger') });
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

		// En caso que quede vacío, cargamos un mensaje en la tabla "Sin zonas".
		if (tabla_zonas.children.length == 0) {
			tabla_zonas.innerHTML = tabla_zonas_vacio;
			return;
		}

		// Reordenamos la enumeración del listado.
		Array.from(document.querySelectorAll('.zona_orden')).forEach((input, index) => {
			const idrand = input.getAttribute('data-id');
			document.querySelector(`#zona_norden_${idrand}`).innerHTML = (index + 1);
			input.value = (index + 1);
		});
	}



	/**
	 * DATOS TÉCNICOS.
	 */
	// Elementos HTML.
	const omitir_datos = document.getElementById('omitir_datos_tecnicos');
	const m_reporta_ = document.getElementById("m_reporta");
	const btn_agregar_instalador = document.getElementById('btn_agregar_instalador');
	const tabla_tecnicos = document.querySelector('#tabla_tecnicos tbody');
	const tabla_tecnicos_vacio = tabla_tecnicos.innerHTML;
	const tecnicos = document.getElementById('lista_tecnicos');
	const btn_agregar_tecnico = document.getElementById('btn_agregar_tecnico');
	const modal_instaladores = new bootstrap.Modal(document.getElementById('modal_instaladores'));

	// Eventos elementos HTML.
	// Deshabilitar los campos si desea omitirlos.
	omitir_datos.addEventListener('change', () => {
		Array.from(document.querySelectorAll('.form-tecnicos')).forEach(field => {
			if (omitir_datos.checked) {
				field.setAttribute('disabled', true);
			} else {
				field.removeAttribute('disabled');
			}
		});
	});

	// Mostramos/Ocultamos los campos de telefono según el canal de reporte.
	m_reporta_.addEventListener('change', function () {
		const cta = document.getElementById('contenedor_telefono_asig');
		const pt_asg = document.getElementById('c_prefijo_telefono_asg');
		const t_sig = document.getElementById('c_telefono_assig');
		if (this.value == "0") {
			cta.style.display = '';
		} else {
			cta.style.display = 'none';
			pt_asg.value = "";
			t_sig.value = "";
		}
	});

	// Abrimos la modal para seleccionar el tecnico a agregar.
	btn_agregar_instalador.addEventListener('click', (e) => {
		e.preventDefault();
		tecnicos.value = "";
		modal_instaladores.show();
	});

	// Agregamos el técnico en la tabla.
	btn_agregar_tecnico.addEventListener('click', function (e) {
		e.preventDefault();

		// Válidamos que el campo tecnbico no este vacío.
		if (tecnicos.value == "") {
			Toast.fire({ icon: 'error', text: '¡Selecccione el técnico!' });
			tecnicos.focus();
		} else {
			let coincidencia = 0; // Guardamos todas las coincidencias encontradas.
			// Recorremos los campos ya agregado de cedula y verificamos si la nueva ya se encuentra agregada.
			Array.from(document.querySelectorAll('.cedula_instalador')).forEach(input => {
				if (input.value == lista_tecnicos[tecnicos.value].cedula) coincidencia++;
			});
			// Si ya se encuentra agregada, procedemos a denegar el proceso.
			if (coincidencia > 0) {
				Toast.fire({ icon: 'error', text: '¡Este técnico ya se encuentra agregado!' });
				return;
			}

			const tecnico_seleccionado = lista_tecnicos[tecnicos.value];
			html_tecnico(tecnico_seleccionado);
			modal_instaladores.hide();
		}
	});

	// Agregar el HTML en el DOM.
	function html_tecnico(_tecnico_) {
		// Válidamos la tabla.
		if (tabla_tecnicos.children.length > 0 && tabla_tecnicos.children[0].classList.contains('sin_tecnicos')) tabla_tecnicos.innerHTML = '';

		// GENERAMOS UN NUEVO ELEMENTO.
		const idrand = Math.random().toString().replace('.', ''); // GENERAMOS UN ID UNICO PARA MANEJAR LA FILA DEL INSTALADOR.
		const elemento = document.createElement('tr'); // GENERAMOS UN NUEVO ELEMENTO.
		elemento.id = `tr_instalador_${idrand}`;
		elemento.setAttribute('data-rand', idrand);

		// Definimos toda la estructura de la nueva fila.
		elemento.innerHTML = `
		<td class="py-1 px-2 text-center n_tecnicos" class="tecnico_norden" id="tecnico_norden_${idrand}" data-id="${idrand}">${tabla_tecnicos.children.length + 1}</td>
		<td class="py-1 px-2">
			<input type="hidden" name="idinstalador[]" id="idinstalador_${idrand}">
			<input type="hidden" class="cedula_instalador" name="cedula_instalador[]" id="cedula_instalador_${idrand}" value="${_tecnico_ ? _tecnico_.cedula : ''}" data-id="${idrand}">
			<span id="nombre_tecnico_${idrand}">${_tecnico_ ? _tecnico_.cedula : ""}</span>
		</td>
		<td class="py-1 px-2">
			<span id="cedula_tecnico_${idrand}">${_tecnico_ ? _tecnico_.nombre : ""}</span>
		</td>
		<td class="py-1 px-2">
			<span id="telefono_tecnico_${idrand}">${_tecnico_ ? _tecnico_.telefono1 : ""}</span>
		</td>
		<td class="py-1 px-2" style="width: 20px;">
			<button type="button" class="btn btn-danger btn-sm btn-icon" id="btn_eliminar_instalador_${idrand}" data-id="${idrand}"><i class="fas fa-times"></i></button>
		</td>`;
		tabla_tecnicos.appendChild(elemento);

		// Agregamos los eventos a los elementos agregados a la tabla.
		document.getElementById(`btn_eliminar_instalador_${idrand}`).addEventListener("click", eliminar_instalador);
	}

	// Evento para eliminar instalador de la tabla.
	function eliminar_instalador() {
		// Capturamos el elemento que provoco el evento.
		const idrand = this.getAttribute('data-id');
		document.getElementById(`tr_instalador_${idrand}`).remove();

		// En caso que quede vacío, cargamos un mensaje en la tabla "Sin usuarios".
		if (tabla_tecnicos.children.length == 0) {
			tabla_tecnicos.innerHTML = tabla_tecnicos_vacio;
			return;
		}

		// Reordenamos la enumeración del listado.
		Array.from(document.querySelectorAll('.n_tecnicos')).forEach((tr_, index) => {
			const idrand = tr_.getAttribute('data-id');
			document.querySelector(`#tecnico_norden_${idrand}`).innerHTML = (index + 1);
		});
	};



	/**
	 * VISITAS
	 */
	// Elementos HTML.
	const btn_abrir_modal_anio = document.getElementById('btn_abrir_modal_anio');
	const modal_visita1 = new bootstrap.Modal('#modal_visita1');
	const btn_agregar_anio = document.getElementById('btn_agregar_anio');
	const anio_input = document.getElementById('lista_anios');
	const contenedor_visitas = document.getElementById('contenedor_visitas');

	// Eventos a los elementos HTML.
	// Abrir la modal para poder seleccionar un año y agregarlo al mapa de zona.
	btn_abrir_modal_anio.addEventListener('click', function (e) {
		e.preventDefault();

		// Establecemos un valor que este disponible y mostramos la modal ya preparada.
		anio_input.value = document.querySelector(`#lista_anios option:not(:disabled)`).value;
		modal_visita1.show();
	});

	// Agregar la nueva tabla a las visitas con el año seleccionado en el mapa de zonas.
	btn_agregar_anio.addEventListener('click', (e) => {
		e.preventDefault();

		// Si esta el mensaje de "Sin visitas agregadas", procede a eliminarla.
		if (document.querySelector('#contenedor_visitas .sin_anios') != null) document.querySelector('#contenedor_visitas .sin_anios').remove();

		// Llamamos la función para agregar el HTML y ocultamos la modal de fecha.
		agregar_tabla_visitas(anio_input.value);
		modal_visita1.hide();
	});

	// Agregar la tabla de visita de cada año en el DOM.
	function agregar_tabla_visitas(anio) {
		// Agregamos la nueva tabla en el contenedor.
		const idrand = Math.random().toString().replace('.', '');
		contenedor_visitas.innerHTML += `
		<div class="table-responsive border rounded mb-4 table-visitas">
			<table id="tabla_visitas_${idrand}" class="table table-hover m-0">
				<thead>
					<tr>
						<td colspan="6" class="text-uppercase fw-bold py-1">
							<div class="d-flex justify-content-between align-items-center">
								<h4 class="d-inline-block m-0">REGISTROS DE VISITAS TÉCNICAS - ${anio}</h4>
								<button type="button" class="btn btn-primary btn-sm my-1" id="btn_agg_visita_${idrand}" data-rand="${idrand}" data-anio="${anio}"><i class="fas fa-toolbox me-2"></i>Agregar</button>
							</div>
						</td>
					</tr>
					<tr>
						<th class="py-2 px-1 text-center" width="40px"><i class="fas fa-list-ol"></i> N°</th>
						<th class="py-2 px-1" width="130px"><i class="fas fa-calendar-day"></i> <span class="required">Fecha</span></th>
						<th class="py-2 px-1"><i class="fas fa-laptop-house"></i> <span class="required">Servicio</span></th>
						<th class="py-2 px-1"><i class="fas fa-user-tie"></i> <span class="required">Técnicos</span></th>
						<th class="py-2 px-1"><i class="fas fa-sticky-note"></i> <span>Pendientes</span></th>
						<th class="py-2 px-1 text-center" width="40px"><i class="fas fa-cogs"></i></th>
					</tr>
				</thead>
				<tbody>
					<tr class="sin_visitas">
						<td colspan="8" class="text-center"><i class="fas fa-times"></i> Sin visitas registradas</td>
					</tr>
				</tbody>
				<tfoot>
					<tr class="sin_visitas border-top">
						<td colspan="8" class="text-center py-3"> Lista visitas ${anio}</td>
					</tr>
				</tfoot>
			</table>
		</div>
		`;

		// Ocultamos la modal y deshabilitamos el año agregado para evitar repetir datos.
		document.querySelector(`#lista_anios option[value="${anio}"]`).setAttribute('disabled', true);

		// Agregamos evento al botón.
		document.getElementById(`btn_agg_visita_${idrand}`).addEventListener('click', agregar_visita);
	}

	// Función para registrar una nueva visita en el mapa de zona.
	function agregar_visita(e) {
		e.preventDefault();

		// Elementos HTML.
		const idrand_table = this.getAttribute('data-rand');
		const tabla_visitas = document.querySelector(`#tabla_visitas_${idrand_table} tbody`);

		// ELEMENTOS.
		if (tabla_visitas.children.length > 0 && tabla_visitas.children[0].classList.contains('sin_visitas')) tabla_visitas.innerHTML = '';

		// GENERAMOS UN NUEVO ELEMENTO.
		const idrand = Math.random().toString().replace('.', ''); // GENERAMOS UN ID UNICO PARA MANEJAR LA FILA DEL USUARIO.
		const idseltec = Math.random().toString().replace('.', '');
		const elemento = document.createElement('tr'); // GENERAMOS UN NUEVO ELEMENTO.
		elemento.id = `tr_visita_${idrand}`;
		elemento.setAttribute('data-rand', idrand);
		elemento.setAttribute('data-table', idrand_table);

		// Definimos toda la estructura de la nueva fila.
		elemento.innerHTML = `
		<td class="py-1 px-1 text-center n_visitas" style="vertical-align: top;" data-id="${idrand}" id="visita_norden_${idrand}">${tabla_visitas.children.length + 1}</td>
		<td class="py-1 px-1" style="vertical-align: top;">
			<input type="hidden" name="idvisita[]" id="idvisita_${idrand}">
			<input type="date" class="form-control text-uppercase visita_fecha" name="visita_fecha[]" id="visita_fecha_${idrand}" style="max-width: 130px;">
		</td>
		<td class="py-1 px-1">
			<textarea class="form-control visita_servicio" name="visita_servicio[]" id="visita_servicio_${idrand}" placeholder="Servicio prestado"></textarea>
		</td>
		<td class="py-1 px-1" style="vertical-align: top;">
			<input type="hidden" name="idselvis_tecnicos[]" value="${idseltec}">
			<select class="form-control text-uppercase visita_tecnicos" name="visita_tecnicos_${idseltec}[]" id="visita_tecnicos_${idrand}" multiple data-id="${idrand}" style="max-width: 200px;">
				${lista_tecnicos.map(tec => `<option value="${tec.cedula}">${tec.nombre}</option>`).join('')}
			</select>
		</td>
		<td class="py-1 px-1">
			<textarea class="form-control visita_pendiente" name="visita_pendiente[]" id="visita_pendiente_${idrand}" placeholder="Pendientes"></textarea>
		</td>
		<td class="py-1 px-1" style="vertical-align: top;">
			<button type="button" class="btn btn-danger btn-sm btn-icon" id="btn_eliminar_visita_${idrand}" data-id="${idrand}" data-table="${idrand_table}"><i class="fas fa-times"></i></button>
		</td>`;
		tabla_visitas.appendChild(elemento);

		// Agregamos los eventos a los elementos agregados a la tabla.
		new TomSelect(`#visita_tecnicos_${idrand}`, {
			plugins: ['remove_button'],
			persist: false,
			createOnBlur: false,
			create: false,
		});
		// document.getElementById(`visita_tecnicos_${idrand}`).addEventListener("change", consultar_configuracion);
		document.getElementById(`btn_eliminar_visita_${idrand}`).addEventListener("click", eliminar_visita);

		// // 
		// document.getElementById(`zona_descripcion_${idrand}`).addEventListener('change', function () { this.classList.remove('border-danger') });
		// document.getElementById(`zona_equipos_${idrand}`).addEventListener('change', function () { this.classList.remove('border-danger') });
		// document.getElementById(`zona_configuracion_${idrand}`).addEventListener('change', function () { this.classList.remove('border-danger') });
	}

	// Eliminar visita de la tabla.
	function eliminar_visita(e) {
		// Capturamos el elemento que provoco el evento.
		const idrand = this.getAttribute('data-id');
		const idrand_table = this.getAttribute('data-table');
		const tabla_visitas = document.querySelector(`#tabla_visitas_${idrand_table} tbody`);

		document.getElementById(`tr_visita_${idrand}`).remove();
		// En caso que quede vacío, cargamos un mensaje en la tabla "Sin visitas".
		if (tabla_visitas.children.length == 0) {
			tabla_visitas.innerHTML = `<tr class="sin_visitas"><td colspan="8" class="text-center"><i class="fas fa-times"></i> Sin visitas registradas</td></tr>`;
			return;
		}

		// Reordenamos la enumeración del listado.
		Array.from(document.querySelectorAll('.n_visitas')).forEach((tr_, index) => {
			const idrand = tr_.getAttribute('data-id');
			document.querySelector(`#visita_norden_${idrand}`).innerHTML = (index + 1);
		});
	}



	/**
	 * FORMULARIO REGISTRAR.
	 */
	const formulario_registro = document.getElementById('formulario_registro');
	formulario_registro.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos del formulario.
		const m_ingreso = document.getElementById("m_ingreso");
		const m_tipo_contrato = document.getElementById("m_tipo_contrato");
		const m_codigo = document.getElementById("m_codigo");
		const m_asesor = document.getElementById("m_asesor");
		const m_cliente = document.getElementById("id_cliente");
		const c_direccion = document.getElementById("c_direccion");
		const btn_guardar = document.getElementById("btn_save");

		// Validamos los campos de los contactos.
		let usuarios_n = document.querySelectorAll('.n_usuarios');
		let usuarios_vacios = 0;
		for (let i = 0; i < usuarios_n.length; i++) {
			const cedula = document.querySelectorAll('.usuario_cedula')[i];
			const nombre = document.querySelectorAll('.usuario_nombre')[i];
			const prefijo = document.querySelectorAll('.usuario_prefijotl')[i];
			const telefono = document.querySelectorAll('.usuarios_telefono')[i];

			// Remarcamos los campos incorrectos.
			if (cedula.value == "") {
				cedula.classList.add("border-danger");
				usuarios_vacios++;
			}
			if (nombre.value == "") {
				nombre.classList.add("border-danger");
				usuarios_vacios++;
			}
			if (prefijo.value == "") {
				prefijo.classList.add("border-danger");
				usuarios_vacios++;
			}
			if (telefono.value == "") {
				telefono.classList.add("border-danger");
				usuarios_vacios++;
			}
		}

		// Validamos los campos de las zonas.
		let zonas_n = document.querySelectorAll('.n_zonas');
		let zonas_vacias = 0;
		for (let i = 0; i < zonas_n.length; i++) {
			const descripcion = document.querySelectorAll('.zona_descripcion')[i];
			const equipos = document.querySelectorAll('.zona_equipos')[i];
			const configuracion = document.querySelectorAll('.zona_configuracion')[i];

			// Remarcamos los campos incorrectos.
			if (descripcion.value == "") {
				descripcion.classList.add("border-danger");
				zonas_vacias++;
			}
			if (equipos.value == "0") {
				equipos.classList.add("border-danger");
				zonas_vacias++;
			}
			if (configuracion.value == "0") {
				configuracion.classList.add("border-danger");
				zonas_vacias++;
			}
		}

		// Validamos los campos.
		if (m_ingreso.value == "") {
			Toast.fire({ icon: 'error', title: '¡La fecha de registro no debe estar vacía!' });
			document.getElementById('pills-cliente-tab').click();
			m_ingreso.focus();
		} else if (m_tipo_contrato.value == "") {
			Toast.fire({ icon: 'error', title: '¡Seleccione el tipo contrato!' });
			document.getElementById('pills-cliente-tab').click();
			m_tipo_contrato.focus();
		} else if (m_codigo.value == "") {
			Toast.fire({ icon: 'error', title: '¡El código de abonado no debe estar vacío!' });
			document.getElementById('pills-cliente-tab').click();
			m_codigo.focus();
		} else if (m_codigo.value.length < 4) {
			Toast.fire({ icon: 'error', title: '¡El código debe tener 4 números!' });
			document.getElementById('pills-cliente-tab').click();
			m_codigo.focus();
		} else if (m_asesor.value == "") {
			Toast.fire({ icon: 'error', title: '¡Seleccione el asesor responsable!' });
			document.getElementById('pills-cliente-tab').click();
			m_asesor.focus();
		} else if (m_cliente.value == "") {
			Toast.fire({ icon: 'error', title: '¡Debe asignar un cliente para el mapa de zona!' });
			document.getElementById('pills-cliente-tab').click();
		} else if (c_direccion.value == "") {
			Toast.fire({ icon: 'error', title: '¡La dirección no debe estar vacía!' });
			document.getElementById('pills-cliente-tab').click();
			c_direccion.focus();
		} else if (c_direccion.value.length < 10) {
			Toast.fire({ icon: 'error', title: '¡La dirección debe tener al menos 10 caracteres!' });
			document.getElementById('pills-cliente-tab').click();
			c_direccion.focus();
		} else if (usuarios_vacios > 0) {
			swal.fire({ icon: 'error', title: '¡Ateción!', html: '¡Debe llenar todos los campos del contacto!<br><small style="font-weight: bold;">Cédula, Nombre, Teléfono</small>' });
			document.getElementById('pills-usuarios-tab').click();
		} else if (zonas_vacias > 0) {
			swal.fire({ icon: 'error', title: '¡Ateción!', html: '¡Debe llenar todos los campos de la zona!<br><small style="font-weight: bold;">Descripción, Equipo, Configuración</small>' });
			document.getElementById('pills-zonas-tab').click();
		} else if (zonas_vacias > 0) {
			swal.fire({ icon: 'error', title: '¡Ateción!', html: '¡Debe llenar todos los campos de la zona!<br><small style="font-weight: bold;">Descripción, Equipo, Configuración</small>' });
			document.getElementById('pills-zonas-tab').click();
		} else {
			btn_guardar.classList.add("loading");
			btn_guardar.setAttribute('disabled', true);
			fetch(`${formulario_registro.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro) }).then(response => response.json()).then(data => {
				btn_guardar.classList.remove("loading");
				btn_guardar.removeAttribute('disabled');

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					if (data.response.error) console.error(`Error: ${data.response.error}`);
					return false;
				}

				// Enviamos mensaje de exito.
				Swal.fire({
					title: "Exito",
					icon: data.status,
					text: data.response.message,
					timer: 2000,
					willClose: () => location.href = `${url_}/mapas_de_zonas`,
				});
			});
		}
	});



	/**
	 * BOTONES VISTAS.
	 */
	// Botones step [Avanzar y retroceder en el formulario].
	var vista = 0;
	const listado_vistas = ["#pills-cliente-tab", "#pills-usuarios-tab", "#pills-zonas-tab", "#pills-tecnicos-tab", "#pills-visitas-tab"];
	// Botón para retroceder la vista.
	btn_prev.addEventListener("click", function () {
		if (listado_vistas[vista - 1]) {
			vista--;
			document.querySelector(listado_vistas[vista]).click();
		}
		actualizar_botones();
	});
	// Botón para adelantar la vista.
	btn_next.addEventListener("click", function () {
		if (listado_vistas[vista + 1]) {
			vista++;
			document.querySelector(listado_vistas[vista]).click();
		}
		actualizar_botones();
	});
	// Función para actualizar que botones mostrar/ocultar. 
	const actualizar_botones = () => {
		btn_prev.style.display = "";
		if (vista == 0) {
			btn_prev.style.display = "none";
		}
		// Verificamos si ya llego a la ultima pestaña.
		btn_next.style.display = "";
		if ((vista + 1) == listado_vistas.length) {
			btn_next.style.display = "none";
		}
	};
	// Al cliquear algunas de las tab, toma su posición y actualizar los botones nuevamente.
	Array.from(document.querySelectorAll('.tab_mapa')).forEach(tab => {
		tab.addEventListener('click', function () {
			vista = parseInt(this.getAttribute('data-vista'));
			actualizar_botones();
		});
	});
})();