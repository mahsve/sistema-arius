(function () {
	// Elementos HTML.
	const btn_nuevo_usuario = document.getElementById("btn_nuevo_usuario");
	const btn_editar = document.querySelectorAll(".btn_editar");
	const btn_estatus = document.querySelectorAll(".btn_estatus");
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const formulario_registro = document.getElementById("formulario_registro");
	const modal_modificar = document.getElementById('modal_modificar') != null ? new bootstrap.Modal('#modal_modificar') : null;
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");
	const modal_estatus = document.getElementById('modal_estatus') != null ? new bootstrap.Modal('#modal_estatus') : null;
	const formulario_estatus = document.getElementById("formulario_estatus");

	// Eventos elementos HTML.
	// Agregar nuevo registro.
	if (btn_nuevo_usuario != null) {
		btn_nuevo_usuario.addEventListener("click", function (e) {
			e.preventDefault();
			formulario_registro.reset();
			modal_registrar.show();
		});
	}

	// Registrar dato.
	if (formulario_registro != null) {
		formulario_registro.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_cedula = document.getElementById("c_cedula_r");
			const c_rol = document.getElementById("c_rol_r");
			const btn_guardar = document.getElementById("btn_registrar");

			// Validamos los campos.
			if (c_cedula.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione la persona a crear el usuario!' });
				c_cedula.focus();
			} else if (c_rol.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el rol de este usuario!' });
				c_rol.focus();
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
			const c_rol = document.getElementById("c_rol_m");
			const btn_guardar = document.getElementById("btn_modificar");

			// Validamos los campos.
			if (c_rol.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el rol de este usuario!' });
				c_rol.focus();
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

	// Modificar estatus.
	if (formulario_estatus != null) {
		formulario_estatus.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_estatus = document.getElementById("c_estatus_s");
			const btn_guardar = document.getElementById("btn_estatus");

			// Validamos los campos.
			if (c_estatus.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione una opción para el usuario!' });
				c_estatus.focus();
			} else {
				btn_guardar.classList.add("loading");
				btn_guardar.setAttribute('disabled', true);
				fetch(`${formulario_estatus.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_estatus) }).then(response => response.json()).then(data => {
					btn_guardar.classList.remove("loading");
					btn_guardar.removeAttribute('disabled');

					// Verificamos si ocurrió algún error.
					if (data.status == "error") {
						Toast.fire({ icon: data.status, title: data.response.message });
						return false;
					}

					// Enviamos mensaje de exito al usuario.
					modal_estatus.hide();
					Swal.fire({
						title: "Exito",
						icon: data.status,
						text: data.response.message,
						timer: 2000,
					});

					// Actualizamos el DOM.
					const idrand = document.getElementById('idrand_s').value;
					if (data.response.data.estatus == "A") {
						document.querySelector(`#contenedor_badge${idrand}`).innerHTML = `<span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>`;
					} else if (data.response.data.estatus == "P") {
						document.querySelector(`#contenedor_badge${idrand}`).innerHTML = `<span class="badge badge-info"><i class="fas fa-clock"></i> Pendiente</span>`;
					} else if (data.response.data.estatus == "B") {
						document.querySelector(`#contenedor_badge${idrand}`).innerHTML = `<span class="badge badge-danger"><i class="fas fa-ban"></i> Bloqueado</span>`;
					} else {
						document.querySelector(`#contenedor_badge${idrand}`).innerHTML = `<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>`;
					}
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
			fetch(`${url_}/usuarios/${id_data}/edit`, { method: 'get' }).then(response => response.json()).then((data) => {
				btn_consultar.classList.remove('loading');
				btn_consultar.removeAttribute('disabled');

				// Limpiamos el formulario y cargamos los datos consultados.
				formulario_actualizacion.reset();
				formulario_actualizacion.setAttribute('action', `${url_}/usuarios/${id_data}`);
				document.getElementById('c_personal_m').value = data.cedula + " - " + data.nombre;
				document.getElementById('c_rol_m').value = data.idrol;
				modal_modificar.show();
			});
		});
	});

	// Consultar datos para cambiar estatus.
	Array.from(btn_estatus).forEach(btn_ => {
		btn_.addEventListener('click', function (e) {
			e.preventDefault();

			// Capturamos el elemento que provoco el evento.
			const btn_consultar = this;
			const id_data = btn_consultar.getAttribute('data-id');
			const id_rand = btn_consultar.getAttribute('data-rand');

			// Realizamos la consulta AJAX.
			btn_consultar.classList.add('loading');
			btn_consultar.setAttribute('disabled', true);
			fetch(`${url_}/usuarios/${id_data}/edit`, { method: 'get' }).then(response => response.json()).then((data) => {
				btn_consultar.classList.remove('loading');
				btn_consultar.removeAttribute('disabled');

				// Limpiamos el formulario y cargamos los datos consultados.
				formulario_estatus.reset();
				formulario_estatus.setAttribute('action', `${url_}/usuarios/estatus/${id_data}`);
				document.getElementById('c_personal_s').value = data.cedula + " - " + data.nombre;
				document.getElementById('idrand_s').value = id_rand;
				modal_estatus.show();
			});
		});
	});
})();