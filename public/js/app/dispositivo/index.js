(function () {
	// Elementos HTML.
	const btn_nuevo_dispositivo = document.getElementById("btn_nuevo_dispositivo");
	const switch_estatus = document.querySelectorAll(".switch_estatus");
	const btn_editar = document.querySelectorAll(".btn_editar");
	const modal_registrar = new bootstrap.Modal('#modal_registrar');
	const formulario_registro = document.getElementById("formulario_registro");
	const modal_modificar = new bootstrap.Modal('#modal_modificar');
	const formulario_actualizacion = document.getElementById("formulario_actualizacion");

	// Eventos elementos HTML.
	// Agregar nuevo registro.
	btn_nuevo_dispositivo.addEventListener("click", function (e) {
		e.preventDefault();
		formulario_registro.reset();
		modal_registrar.show();
	});

	// Registrar dato.
	formulario_registro.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos del formulario.
		const c_dispositivo = document.getElementById("c_dispositivo_r");
		const btn_guardar = document.getElementById("btn_registrar");

		// Validamos los campos.
		if (c_dispositivo.value == "") {
			Toast.fire({ icon: 'error', title: 'Ingrese el nombre del dispositivo' });
			c_dispositivo.focus();
		} else if (c_dispositivo.value.length < 3) {
			Toast.fire({ icon: 'error', title: 'El dispositivo debe tener al menos 3 caracteres' });
			c_dispositivo.focus();
		} else {
			btn_guardar.classList.add("loading");
			fetch(`${formulario_registro.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro) }).then(response => response.json()).then(data => {
				btn_guardar.classList.remove("loading");

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					return false;
				}

				// Enviamos mensaje de exito.
				modal_registrar.hide();
				Swal.fire({ title: "Exito", text: "Dispositivo registrado exitosamente", icon: "success", timer: 2000 });
				setTimeout(() => location.reload(), 2000);
			});
		}
	});

	// Modificar dato.
	formulario_actualizacion.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos del formulario.
		const c_dispositivo = document.getElementById("c_dispositivo_m");
		const btn_guardar = document.getElementById("btn_modificar");

		// Validamos los campos.
		if (c_dispositivo.value == "") {
			Toast.fire({ icon: 'error', title: 'Ingrese el nombre del dispositivo' });
			c_dispositivo.focus();
		} else if (c_dispositivo.value.length < 3) {
			Toast.fire({ icon: 'error', title: 'El dispositivo debe tener al menos 3 caracteres' });
			c_dispositivo.focus();
		} else {
			btn_guardar.classList.add("loading");
			fetch(`${formulario_actualizacion.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_actualizacion) }).then(response => response.json()).then(data => {
				btn_guardar.classList.remove("loading");

				// Verificamos si ocurrió algún error.
				if (data.status == "error") {
					Toast.fire({ icon: data.status, title: data.response.message });
					return false;
				}

				// Enviamos mensaje de exito.
				modal_modificar.hide();
				Swal.fire({ title: "Exito", text: "Dispositivo modificado exitosamente", icon: "success", timer: 2000 });
				setTimeout(() => location.reload(), 2000);
			});
		}
	});

	// Consultar registro.
	Array.from(btn_editar).forEach(button_ => {
		button_.addEventListener('click', function (e) {
			e.preventDefault();

			// Capturamos el elemento que provoco el evento.
			const button_element = this;
			const id_data = button_element.getAttribute('data-id');

			// Realizamos la consulta AJAX.
			button_element.classList.add('loading');
			fetch(`${url_}/dispositivos/${id_data}/edit`, { method: 'get' }).then(response => response.json()).then((data) => {
				button_element.classList.remove('loading');

				// Limpiamos el formulario y cargamos los datos consultados.
				formulario_actualizacion.reset();
				formulario_actualizacion.setAttribute('action', `${url_}/dispositivos/${id_data}`);
				document.getElementById('c_dispositivo_m').value = data.dispositivo;
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
				title: '¿Seguro que quieres cambiar el estatus de este dispositivo?',
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
					fetch(`${url_}/dispositivos/estatus/${switch_element.value}`, {
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
						Toast.fire({ icon: "success", title: "Estatus actualizado exitosamente" });
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