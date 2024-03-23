(function () {
	// Elementos HTML.
	const tipo_contrato = document.getElementById("m_tipo_contrato");

	const table_utabla_usuariosser = document.querySelector('#tabla_usuarios tbody');
	const table_message = tabla_usuarios.innerHTML;
	const btn_agregar_usuario = document.getElementById('btn_agregar_usuario');
	
	tipo_contrato.addEventListener('change', function () {
		// Capturamos el elemento que provoco el evento.
		const select_element = this;
		// const id_data = button_element.getAttribute('data-id');

		// Realizamos la consulta AJAX.
		select_element.parentElement.classList.add('loading');
		fetch(`${url_}/mapas_de_zonas/consultar_codigo/${select_element.value}`, { method: 'get' }).then(response => response.json()).then((data) => {
			select_element.parentElement.classList.remove('loading');

			// Limpiamos el formulario y cargamos los datos consultados.
			document.getElementById('m_codigo').value = data;
		});
	});

	btn_agregar_usuario.addEventListener('click', (e) => {
		e.preventDefault();
	
		// Si tiene el mensaje "Sin usuarios", eliminamos primero ese mensaje.
		if (tabla_usuarios.children.length > 0 && tabla_usuarios.children[0].classList.contains('no-users')) tabla_usuarios.innerHTML = '';
	
		// Creamos una nueva fila y la agregamos a la tabla.
		tabla_usuarios.appendChild(createTrElementUser());
	});
	
	const createTrElementUser = () => {
		// Creamos un elemento TR.
		const tr_element = document.createElement('tr');
	
		// Generamos un ID para poder eliminar la fila en caso que presione el botón eliminar.
		const tr_id_rand = Math.random().toString().replace('.', '');
		tr_element.id = `tr-user-${tr_id_rand}`; // Le agregamos el ID a la fila creada.
	
		// Definimos toda la estructura de la nueva fila.
		tr_element.innerHTML = `
			<input type="hidden" name="idcontact_[]" value="0">
			<td class="py-2 px-2 text-end users-counter">${tabla_usuarios.children.length + 1}</td>
			<td class="py-2 px-1"><input type="text" class="form-control border-0" name="fullname_[]" id="fullname_${tr_id_rand}" placeholder="Nombre completo"></td>
			<td class="py-2 px-1"><input type="text" class="form-control border-0" name="cedula_[]" id="cedula_${tr_id_rand}" placeholder="Cédula"></td>
			<td class="py-2 px-1"><input type="text" class="form-control border-0" name="password_[]" id="password_${tr_id_rand}" placeholder="Contraseña"></td>
			<td class="py-2 px-1"><input type="text" class="form-control border-0" name="phone_[]" id="phone_${tr_id_rand}" placeholder="Teléfono"></td>
			<td class="py-2 px-1"><input type="text" class="form-control border-0" name="note_[]" id="note_${tr_id_rand}" placeholder="Nota (Opcional)"></td>
			<td class="py-0 px-1">
				<button type="button" class="btn btn-danger btn-sm px-2" onclick="deleteTrElementUser('${tr_id_rand}')">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
						<polyline points="3 6 5 6 21 6"></polyline>
						<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
					</svg>
				</button>
			</td>`;
	
		// Retornamos todo el elemento armado.
		return tr_element;
	};
	
	const deleteTrElementUser = (id) => {
		// Eliminamos la fila cliqueada.
		document.getElementById(`tr-user-${id}`).remove();
	
		// Reordenamos la enumeración del listado.
		Array.from(document.querySelectorAll('.users-counter')).forEach((element, index) => element.innerHTML = (index + 1));
	
		// En caso que quede vacío, cargamos un mensaje en la tabla "Sin usuarios".
		if (tabla_usuarios.children.length == 0) tabla_usuarios.innerHTML = table_message;
	};
	
	const table_zone = document.querySelector('#table-zone tbody');
	const table_message_zone = table_zone.innerHTML;
	const btn_new_zone = document.getElementById('btn-new-zone');
	
	btn_new_zone.addEventListener('click', (e) => {
		e.preventDefault();
	
		// Si tiene el mensaje "Sin usuarios", eliminamos primero ese mensaje.
		if (table_zone.children.length > 0 && table_zone.children[0].classList.contains('no-zones')) table_zone.innerHTML = '';
	
		// Creamos una nueva fila y la agregamos a la tabla.
		table_zone.appendChild(createTrElementZone());
	});
	
	const createTrElementZone = () => {
		// Creamos un elemento TR.
		const tr_element = document.createElement('tr');
	
		// Generamos un ID para poder eliminar la fila en caso que presione el botón eliminar.
		const tr_id_rand = Math.random().toString().replace('.', '');
		tr_element.id = `tr-zone-${tr_id_rand}`; // Le agregamos el ID a la fila creada.
	
		// Definimos toda la estructura de la nueva fila.
		tr_element.innerHTML = `
			<input type="hidden" name="idzone_[]" value="0">
			<td class="py-2 px-2 text-end zones-counter">${table_zone.children.length + 1}</td>
			<td class="py-2 px-1"><input type="text" class="form-control border-0" name="description_zone[]" id="description_${tr_id_rand}" placeholder="Descripción de la zona"></td>
			<td class="py-2 px-1"><input type="text" class="form-control border-0" name="config_zone[]" id="config_${tr_id_rand}" placeholder="Configuración"></td>
			<td class="py-2 px-1"><input type="text" class="form-control border-0" name="equipos_zone[]" id="equipos_${tr_id_rand}" placeholder="Equipos"></td>
			<td class="py-0 px-1">
				<button type="button" class="btn btn-danger btn-sm px-2" onclick="deleteTrElementZone('${tr_id_rand}')">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
						<polyline points="3 6 5 6 21 6"></polyline>
						<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
					</svg>
				</button>
			</td>`;
	
		// Retornamos todo el elemento armado.
		return tr_element;
	};
	
	const deleteTrElementZone = (id) => {
		// Eliminamos la fila cliqueada.
		document.getElementById(`tr-zone-${id}`).remove();
	
		// Reordenamos la enumeración del listado.
		Array.from(document.querySelectorAll('.zones-counter')).forEach((element, index) => element.innerHTML = (index + 1));
	
		// En caso que quede vacío, cargamos un mensaje en la tabla "Sin usuarios".
		if (table_zone.children.length == 0) table_zone.innerHTML = table_message_zone;
	}
})();