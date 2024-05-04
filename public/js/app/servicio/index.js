(function () {
	var no_fetch_submodulos_edit = false; // Evitar consultar los submódulos al presionar editar algún registro, para no borrar la opción ya cargada.
	// Elementos HTML.
	const btn_nuevo_servicio = document.getElementById("btn_nuevo_servicio");
	const btn_organizar_submodulos = document.getElementById("btn_organizar_submodulos");
	const switch_estatus = document.querySelectorAll(".switch_estatus");
	const btn_editar = document.querySelectorAll(".btn_editar");
	// Registrar.
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const formulario_registro = document.getElementById("formulario_registro");
	const c_submodulo_r = document.getElementById("c_submodulo_r");
	const c_operacion_r = document.getElementById("c_operacion_r");
	const c_modulo_r = document.getElementById('c_modulo_r');
	const c_submodulos_r = document.getElementById("c_submodulos_r");
	// Modificar.
	const modal_modificar = document.getElementById('modal_modificar') != null ? new bootstrap.Modal('#modal_modificar') : null;
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");
	const c_submodulo_m = document.getElementById("c_submodulo_m");
	const c_operacion_m = document.getElementById("c_operacion_m");
	const c_modulo_m = document.getElementById('c_modulo_m');
	const c_submodulos_m = document.getElementById("c_submodulos_m");
	// Organizar.
	const modal_organizar = document.getElementById('modal_organizar') != null ? new bootstrap.Modal('#modal_organizar') : null;
	const formulario_organizar = document.getElementById("formulario_organizar");
	const lista_submodulos = document.getElementById('lista-submodulos');
	// AUXILIAR.
	// [Módulos].
	const btn_nuevo_mod = document.querySelectorAll('.btn_nuevo_mod');
	const modal_registrar_mod = document.getElementById('modal_registrar_mod') != null ? new bootstrap.Modal('#modal_registrar_mod') : null;
	const formulario_registro_mod = document.getElementById("formulario_registro_mod");
	const c_icono_aux = document.getElementById('c_icono_aux');

	// Plugins.
	if (lista_submodulos != null) {
		new Sortable(lista_submodulos, {
			animation: 150,
			ghostClass: 'blue-background-class',
			onUpdate: function () {
				const list_orden = document.querySelectorAll('.o_orden');
				const list_html = document.querySelectorAll('.o_htmlorden');
				for (let i = 0; i < list_orden.length; i++) {
					list_orden[i].value = i + 1;
					list_html[i].innerHTML = i + 1;
				}
			},
		});
	}

	// Eventos elementos HTML.
	// Agregar nuevo registro.
	if (btn_nuevo_servicio != null) {
		btn_nuevo_servicio.addEventListener("click", function (e) {
			e.preventDefault();
			formulario_registro.reset();
			c_submodulo_r.dispatchEvent(new Event('change'));
			modal_registrar.show();
		});
	}

	// Agregamos el evento para mostrar campos al ser un servicio de tipo submódulo.
	// [REGISTRO].
	if (c_submodulo_r != null) {
		c_submodulo_r.addEventListener("change", function () {
			document.getElementById('contenedor_enlace_r').style.display = "";
			document.getElementById('contenedor_metodo_r').style.display = "none";
			document.getElementById('contenedor_submodulos_r').style.display = "none";
		});
	}
	// [MODIFICACION].
	if (c_submodulo_m != null) {
		c_submodulo_m.addEventListener("change", function () {
			document.getElementById('contenedor_enlace_m').style.display = "";
			document.getElementById('contenedor_metodo_m').style.display = "none";
			document.getElementById('contenedor_submodulos_m').style.display = "none";
		});
	}

	// Agregamos el evento para mostrar campos al ser un servicio de tipo operación.
	// [REGISTRO].
	if (c_operacion_r != null) {
		c_operacion_r.addEventListener("change", function () {
			document.getElementById('contenedor_enlace_r').style.display = "none";
			document.getElementById('contenedor_metodo_r').style.display = "";
			document.getElementById('contenedor_submodulos_r').style.display = "";
			c_modulo_r.dispatchEvent(new Event('change'));
		});
	}
	// [MODIFICACION].
	if (c_operacion_m != null) {
		c_operacion_m.addEventListener("change", function () {
			document.getElementById('contenedor_enlace_m').style.display = "none";
			document.getElementById('contenedor_metodo_m').style.display = "";
			document.getElementById('contenedor_submodulos_m').style.display = "";
			c_modulo_m.dispatchEvent(new Event('change'));
		});
	}

	// Función en el campo modulo, para buscar los submódulos dependientes de este al cambiar su estado.
	// [REGISTRO].
	if (c_modulo_r != null) {
		c_modulo_r.addEventListener('change', function () {
			c_submodulos_r.innerHTML = '<option value="">Seleccione el submódulo</option>';
			if (this.value != "" && c_operacion_r.checked) {
				c_submodulos_r.parentElement.classList.add("loading");
				fetch(`${url_}/servicios/submodulos/${this.value}`).then(response => response.json()).then(data => {
					c_submodulos_r.parentElement.classList.remove("loading");
					if (data.length == 0) {
						c_submodulos_r.innerHTML = '<option value="">Sin submódulos registrados</option>';
						return;
					}
					for (let i = 0; i < data.length; i++) {
						c_submodulos_r.innerHTML += `<option value="${data[i].idservicio}">${data[i].servicio}</option>`;
					}
				});
			}
		});
	}
	// [MODIFICACION].
	if (c_modulo_m != null) {
		c_modulo_m.addEventListener('change', function () {
			c_submodulos_m.innerHTML = '<option value="">Seleccione el submódulo</option>';
			if (this.value != "" && c_operacion_m.checked && no_fetch_submodulos_edit === false) {
				c_submodulos_m.parentElement.classList.add("loading");
				fetch(`${url_}/servicios/submodulos/${this.value}`).then(response => response.json()).then(data => {
					c_submodulos_m.parentElement.classList.remove("loading");
					if (data.length == 0) {
						c_submodulos_m.innerHTML = '<option value="">Sin submódulos registrados</option>';
						return;
					}
					for (let i = 0; i < data.length; i++) {
						c_submodulos_m.innerHTML += `<option value="${data[i].idservicio}">${data[i].servicio}</option>`;
					}
				});
			}
		});
	}

	// Evento para mostrar una visualización rapida del icono agregado en el formulario del módulo [Registrar].
	if (c_icono_aux != null) {
		c_icono_aux.addEventListener('keyup', function () {
			document.getElementById('preview_aux').setAttribute('class', `${this.value}`);
		});
	}

	// Mostrar ventana para organizar los módulos por un orden en especifico.
	if (btn_organizar_submodulos != null) {
		btn_organizar_submodulos.addEventListener("click", function (e) {
			e.preventDefault();
			modal_organizar.show();
		});
	}

	// Registrar dato.
	if (formulario_registro != null) {
		formulario_registro.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_submodulo = document.getElementById("c_submodulo_r"); // CHECKES.
			const c_operacion = document.getElementById("c_operacion_r"); // CHECKES.
			// Campos del formulario.
			const c_modulo = document.getElementById("c_modulo_r");
			const c_submodulos = document.getElementById("c_submodulos_r");
			const c_servicio = document.getElementById("c_servicio_r");
			const c_enlace = document.getElementById("c_enlace_r");
			const c_metodo = document.getElementById("c_metodo_r");
			const btn_guardar = document.getElementById("btn_registrar");

			// Validamos los campos.
			if (c_modulo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el módulo!' });
				c_modulo.focus();
			} else if (c_operacion.checked && c_submodulos.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el submódulo!' });
				c_servicio.focus();
			} else if (c_servicio.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del servicio!' });
				c_servicio.focus();
			} else if (c_servicio.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El servicio debe tener al menos 3 caracteres!' });
				c_servicio.focus();
			} else if (c_submodulo.checked && c_enlace.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el enlace del servicio!' });
				c_enlace.focus();
			} else if (c_submodulo.checked && c_enlace.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El enlace debe tener al menos 3 caracteres!' });
				c_enlace.focus();
			} else if (c_operacion.checked && c_metodo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el método en el controlador del servicio!' });
				c_enlace.focus();
			} else if (c_operacion.checked && c_metodo.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El método debe tener al menos 3 caracteres!' });
				c_enlace.focus();
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
			const c_submodulo = document.getElementById("c_submodulo_m"); // CHECKES.
			const c_operacion = document.getElementById("c_operacion_m"); // CHECKES.
			// Campos del formulario.
			const c_modulo = document.getElementById("c_modulo_m");
			const c_submodulos = document.getElementById("c_submodulos_m");
			const c_servicio = document.getElementById("c_servicio_m");
			const c_enlace = document.getElementById("c_enlace_m");
			const c_metodo = document.getElementById("c_metodo_m");
			const btn_guardar = document.getElementById("btn_modificar");

			// Validamos los campos.
			if (c_modulo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el módulo!' });
				c_modulo.focus();
			} else if (c_operacion.checked && c_submodulos.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el submódulo!' });
				c_servicio.focus();
			} else if (c_servicio.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del servicio!' });
				c_servicio.focus();
			} else if (c_servicio.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El servicio debe tener al menos 3 caracteres!' });
				c_servicio.focus();
			} else if (c_submodulo.checked && c_enlace.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el enlace del servicio!' });
				c_enlace.focus();
			} else if (c_submodulo.checked && c_enlace.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El enlace debe tener al menos 3 caracteres!' });
				c_enlace.focus();
			} else if (c_operacion.checked && c_metodo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el método en el controlador del servicio!' });
				c_enlace.focus();
			} else if (c_operacion.checked && c_metodo.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El método debe tener al menos 3 caracteres!' });
				c_enlace.focus();
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

	// Modificar orden.
	if (formulario_organizar != null) {
		formulario_organizar.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const btn_guardar = document.getElementById("btn_guardar");
			btn_guardar.classList.add("loading");
			btn_guardar.setAttribute('disabled', true);
			fetch(`${formulario_organizar.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_organizar) }).then(response => response.json()).then(data => {
				btn_guardar.classList.remove("loading");
				btn_guardar.removeAttribute('disabled');

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					if (data.response.error) console.log(`Error: ${data.response.error}`);
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
		});
	}

	// Agregar nuevo registro [Departamento] como un formulario auxiliar para registro rápido.
	if (btn_nuevo_mod.length > 0) {
		Array.from(btn_nuevo_mod).forEach(btn => {
			btn.addEventListener("click", function (e) {
				e.preventDefault();
				formulario_registro_mod.reset();
				document.getElementById('btn_click_mod').value = btn.getAttribute('data-form');
				if (document.getElementById('btn_click_mod').value == "create") {
					modal_registrar.hide();
				} else {
					modal_modificar.hide();
				}
				setTimeout(() => modal_registrar_mod.show(), 200);
			});
		});

		// Cuando se oculte este registrar, se cargará nuevamente la ventana principal correspondiente.
		document.getElementById('modal_registrar_mod').addEventListener('hidden.bs.modal', event => {
			if (document.getElementById('btn_click_mod').value == "create") {
				setTimeout(() => modal_registrar.show(), 200);
			} else {
				setTimeout(() => modal_modificar.show(), 200);
			}
		});
	}

	// Agregar nuevo registro [modulos] como un formulario auxiliar para registro rápido.
	if (formulario_registro_mod != null) {
		formulario_registro_mod.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_modulo = document.getElementById("c_modulo_aux");
			const btn_guardar = document.getElementById("btn_registrar_mod");

			// Validamos los campos.
			if (c_modulo.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese el nombre del módulo!' });
				c_modulo.focus();
			} else if (c_modulo.value.length < 3) {
				Toast.fire({ icon: 'error', title: '¡El módulo debe tener al menos 3 caracteres!' });
				c_modulo.focus();
			} else {
				btn_guardar.classList.add("loading");
				btn_guardar.setAttribute('disabled', true);
				fetch(`${formulario_registro_mod.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro_mod) }).then(response => response.json()).then(data => {
					btn_guardar.classList.remove("loading");
					btn_guardar.removeAttribute('disabled');

					// Verificamos si ocurrió algún error.
					if (data.status == "error") {
						Toast.fire({ icon: data.status, title: data.response.message });
						return false;
					}

					// Creamos un nuevo elemento de tipo option con la info para agregar la nueva opción al campo select de ambos formularios.
					const optionR = document.createElement('option');
					optionR.setAttribute('value', data.response.data.idmodulo);
					optionR.innerHTML = data.response.data.modulo;
					document.getElementById('c_modulo_r').append(optionR); // Campo módulo [registrar].
					// Nuevo
					const optionM = document.createElement('option');
					optionM.setAttribute('value', data.response.data.idmodulo);
					optionM.innerHTML = data.response.data.modulo;
					document.getElementById('c_modulo_m').append(optionM); // Campo módulo [modificar].

					// Enviamos mensaje de exito.
					modal_registrar_mod.hide();
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
	Array.from(btn_editar).forEach(btn => {
		btn.addEventListener('click', function (e) {
			e.preventDefault();

			// Capturamos el elemento que provoco el evento.
			const btn_consultar = this;
			const id_data = btn_consultar.getAttribute('data-id');

			// Realizamos la consulta AJAX.
			btn_consultar.classList.add('loading');
			btn_consultar.setAttribute('disabled', true);
			fetch(`${url_}/servicios/${id_data}/edit`, { method: 'get' }).then(response => response.json()).then((data) => {
				btn_consultar.classList.remove('loading');
				btn_consultar.removeAttribute('disabled');

				// Limpiamos el formulario y cargamos los datos consultados.
				formulario_actualizacion.reset();
				formulario_actualizacion.setAttribute('action', `${url_}/servicios/${id_data}`);
				document.getElementById('c_modulo_m').value = data.idmodulo;
				document.getElementById('c_servicio_m').value = data.servicio;
				// Si es un submódulo procedemos a cargar solo el enlace de este.
				if (data.idservicio_raiz == null) {
					document.getElementById('c_submodulo_m').checked = true;
					document.getElementById('c_submodulo_m').dispatchEvent(new Event('change'));
					document.getElementById('c_enlace_m').value = data.menu_url;
				} else {
					// De lo contrario procedemos a cargar el submódulo al que pertenece el proceso y el método del controlador.
					no_fetch_submodulos_edit = true;
					document.getElementById('c_operacion_m').checked = true;
					document.getElementById('c_operacion_m').dispatchEvent(new Event('change'));
					// Cargamos todos los submódulos pertenecientes al módulo principal en el campo select.
					for (let index = 0; index < data.submodulos.length; index++) {
						const submodulo = data.submodulos[index];
						document.getElementById('c_submodulos_m').innerHTML += `<option value="${submodulo.idservicio}">${submodulo.servicio}</option>`;
					}
					// Asigamos los valores en el campo.
					document.getElementById('c_submodulos_m').value = data.idservicio_raiz;
					document.getElementById('c_metodo_m').value = data.menu_url;
					no_fetch_submodulos_edit = false;
				}
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
				title: '¿Seguro que quieres cambiar el estatus de este servicio?',
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
					fetch(`${url_}/servicios/estatus/${switch_element.value}`, {
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
})();