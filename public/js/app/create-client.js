const table_user = document.querySelector('#table-user tbody');
const table_message = table_user.innerHTML;
const btn_new_user = document.getElementById('btn-new-user');

btn_new_user.addEventListener('click', (e) => {
	e.preventDefault();

	// Si tiene el mensaje "Sin usuarios", eliminamos primero ese mensaje.
	if (table_user.children.length > 0 && table_user.children[0].classList.contains('no-users')) table_user.innerHTML = '';

	// Creamos una nueva fila y la agregamos a la tabla.
	table_user.appendChild(createTrElement());
});

const createTrElement = () => {
	// Creamos un elemento TR.
	const tr_element = document.createElement('tr');

	// Generamos un ID para poder eliminar la fila en caso que presione el botón eliminar.
	const tr_id_rand = Math.random().toString().replace('.', '');
	tr_element.id = `tr-user-${tr_id_rand}`; // Le agregamos el ID a la fila creada.

	// Definimos toda la estructura de la nueva fila.
	tr_element.innerHTML = `
		<input type="hidden" name="idcontact_[]" value="0">
		<td class="py-2 px-2 text-end users-counter">${table_user.children.length + 1}</td>
		<td class="py-2 px-1"><input type="text" class="form-control border-0" name="fullname_[]" id="fullname_${tr_id_rand}" placeholder="Nombre completo"></td>
		<td class="py-2 px-1"><input type="text" class="form-control border-0" name="cedula_[]" id="cedula_${tr_id_rand}" placeholder="Cédula"></td>
		<td class="py-2 px-1"><input type="text" class="form-control border-0" name="password_[]" id="password_${tr_id_rand}" placeholder="Contraseña"></td>
		<td class="py-2 px-1"><input type="text" class="form-control border-0" name="phone_[]" id="phone_${tr_id_rand}" placeholder="Teléfono"></td>
		<td class="py-2 px-1"><input type="text" class="form-control border-0" name="note_[]" id="note_${tr_id_rand}" placeholder="Nota (Opcional)"></td>
		<td class="py-0 px-1">
			<button type="button" class="btn btn-danger btn-sm rounded px-2" onclick="deleteTrElement('${tr_id_rand}')">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
					<polyline points="3 6 5 6 21 6"></polyline>
					<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
				</svg>
			</button>
		</td>`;

	// Retornamos todo el elemento armado.
	return tr_element;
}

const deleteTrElement = (id) => {
	// Eliminamos la fila cliqueada.
	document.getElementById(`tr-user-${id}`).remove();

	// Reordenamos la enumeración del listado.
	Array.from(document.querySelectorAll('.users-counter')).forEach((element, index) => element.innerHTML = (index + 1));

	// En caso que quede vacío, cargamos un mensaje en la tabla "Sin usuarios".
	if (table_user.children.length == 0) table_user.innerHTML = table_message;
}



const kind_of_client = document.getElementById("kind_of_client");
const id_container = document.getElementById('id_container');
kind_of_client.addEventListener('change', () => {
	if (kind_of_client.value == "N") {
		id_container.children[0].innerHTML = 'Cédula <span class="required">*</span>';
		id_container.children[1].setAttribute('placeholder', 'Ingrese la cédula');
	} else {
		id_container.children[0].innerHTML = 'RIF <span class="required">*</span>';
		id_container.children[1].setAttribute('placeholder', 'Ingrese el RIF');
	}
});