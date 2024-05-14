(function () {
	// Elementos HTML.
	const btn_nuevo_cargo = document.getElementById("btn_nuevo_cargo");
	const switch_estatus = document.querySelectorAll(".switch_estatus");
	const btn_editar = document.querySelectorAll(".btn_editar");
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const formulario_registro = document.getElementById("formulario_registro");
	const modal_modificar = document.getElementById('modal_modificar') != null ? new bootstrap.Modal('#modal_modificar') : null;
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");
	// AUXILIAR.
	// [Departamentos].
	const btn_nuevo_dep = document.querySelectorAll('.btn_nuevo_dep');
	const modal_registrar_dep = document.getElementById('modal_registrar_dep') != null ? new bootstrap.Modal('#modal_registrar_dep') : null;
	const formulario_registro_dep = document.getElementById("formulario_registro_dep");

	// Eventos elementos HTML.
	// Agregar nuevo registro.
	if (btn_nuevo_cargo != null) {
		btn_nuevo_cargo.addEventListener("click", function (e) {
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
			const c_departamento = document.getElementById("c_departamento_r");
			const c_cargo = document.getElementById("c_cargo_r");
			const btn_guardar = document.getElementById("btn_registrar");

			// Validamos los campos.
			if (c_departamento.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el departamento!' });
				c_departamento.focus();
			} else if (c_cargo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del cargo!' });
				c_cargo.focus();
			} else if (c_cargo.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El cargo debe tener al menos 3 caracteres!' });
				c_cargo.focus();
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
			const c_departamento = document.getElementById("c_departamento_m");
			const c_cargo = document.getElementById("c_cargo_m");
			const btn_guardar = document.getElementById("btn_modificar");

			// Validamos los campos.
			if (c_departamento.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el departamento!' });
				c_departamento.focus();
			} else if (c_cargo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del cargo!' });
				c_cargo.focus();
			} else if (c_cargo.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El cargo debe tener al menos 3 caracteres!' });
				c_cargo.focus();
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

	// Agregar nuevo registro [Departamento] como un formulario auxiliar para registro rápido.
	if (btn_nuevo_dep.length > 0) {
		Array.from(btn_nuevo_dep).forEach(btn => {
			btn.addEventListener("click", function (e) {
				e.preventDefault();
				formulario_registro_dep.reset();
				document.getElementById('btn_click_dep').value = btn.getAttribute('data-form');
				if (document.getElementById('btn_click_dep').value == "create") {
					modal_registrar.hide();
				} else {
					modal_modificar.hide();
				}
				setTimeout(() => modal_registrar_dep.show(), 200);
			});
		});

		// Cuando se oculte este registrar, se cargará nuevamente la ventana principal correspondiente.
		document.getElementById('modal_registrar_dep').addEventListener('hidden.bs.modal', event => {
			if (document.getElementById('btn_click_dep').value == "create") {
				setTimeout(() => modal_registrar.show(), 200);
			} else {
				setTimeout(() => modal_modificar.show(), 200);
			}
		});
	}

	// Función registro rápido [Departamento].
	if (formulario_registro_dep != null) {
		formulario_registro_dep.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_departamento = document.getElementById("c_departamento_aux");
			const btn_guardar = document.getElementById("btn_registrar_dep");

			// Validamos los campos.
			if (c_departamento.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del departamento!' });
				c_departamento.focus();
			} else if (c_departamento.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El departamento debe tener al menos 3 caracteres!' });
				c_departamento.focus();
			} else {
				btn_guardar.classList.add("loading");
				btn_guardar.setAttribute('disabled', true);
				fetch(`${formulario_registro_dep.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro_dep) }).then(response => response.json()).then(data => {
					btn_guardar.classList.remove("loading");
					btn_guardar.removeAttribute('disabled');

					// Verificamos si ocurrió algún error.
					if (data.status == "error") {
						Toast.fire({ icon: data.status, title: data.response.message });
						return false;
					}

					// Creamos un nuevo elemento de tipo option con la info para agregar la nueva opción al campo select de ambos formularios.
					const optionR = document.createElement('option');
					optionR.setAttribute('value', data.response.data.iddepartamento);
					optionR.innerHTML = data.response.data.departamento;
					document.getElementById('c_departamento_r').append(optionR); // Campo departamento [registrar].
					// Nuevo
					const optionM = document.createElement('option');
					optionM.setAttribute('value', data.response.data.iddepartamento);
					optionM.innerHTML = data.response.data.departamento;
					document.getElementById('c_departamento_m').append(optionM); // Campo departamento [modificar].

					// Enviamos mensaje de exito.
					modal_registrar_dep.hide();
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

	// Consultar registro.
	if (btn_editar.length > 0) {
		Array.from(btn_editar).forEach(btn_ => {
			btn_.addEventListener('click', function (e) {
				e.preventDefault();
	
				// Capturamos el elemento que provoco el evento.
				const btn_consultar = this;
				const id_data = btn_consultar.getAttribute('data-id');
	
				// Realizamos la consulta AJAX.
				btn_consultar.classList.add('loading');
				btn_consultar.setAttribute('disabled', true);
				fetch(`${url_}/cargos/${id_data}/edit`, { method: 'get' }).then(response => response.json()).then((data) => {
					btn_consultar.classList.remove('loading');
					btn_consultar.removeAttribute('disabled');
	
					// Limpiamos el formulario y cargamos los datos consultados.
					formulario_actualizacion.reset();
					formulario_actualizacion.setAttribute('action', `${url_}/cargos/${id_data}`);
					document.getElementById('c_departamento_m').value = data.iddepartamento;
					document.getElementById('c_cargo_m').value = data.cargo;
					modal_modificar.show();
				});
			});
		});
	}

	// Cambiar estatus.
	if (switch_estatus.length > 0) {
		Array.from(switch_estatus).forEach(switch_ => {
			switch_.addEventListener('change', function () {
				// Capturamos el elemento que provoco el evento.
				switch_element = this;
				switch_element.checked = !switch_element.checked;
	
				// Pedimos confirmar que desea desactivar este registro.
				Swal.fire({
					title: '¿Seguro que quieres cambiar el estatus de este cargo?',
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
						fetch(`${url_}/cargos/estatus/${switch_element.value}`, {
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
	}
})();