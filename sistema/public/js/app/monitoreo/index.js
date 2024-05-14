(function () {
	// Elementos HTML.
	const btn_nueva_ficha = document.getElementById('btn_nueva_ficha');
	const btn_cerrar = document.querySelectorAll(".btn_cerrar");
	const modal_registrar = document.getElementById('modal_registrar') != null ? new bootstrap.Modal('#modal_registrar') : null;
	const formulario_registro = document.getElementById("formulario_registro");

	// Registrar dato.
	if (btn_nueva_ficha != null) {
		btn_nueva_ficha.addEventListener('click', function (e) {
			e.preventDefault();

			// Limpiamos el formulario y mostramos la ventana emergente.
			formulario_registro.reset();
			modal_registrar.show();
		});
	}

	// Registrar dato.
	if (formulario_registro != null) {
		formulario_registro.addEventListener("submit", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const c_fecha = document.getElementById("c_fecha_r");
			const c_operador = document.getElementById("c_operador_r")
			const c_turno = document.getElementById("c_turno_r");
			const btn_guardar = document.getElementById("btn_registrar");

			// Validamos los campos.
			if (c_fecha.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese la fecha del registro!' });
				c_fecha.focus();
			} else if (c_operador.value.length < 4) {
				Toast.fire({ icon: 'error', title: '¡Seleccione el operador responsable!' });
				c_operador.focus();
			} else if (c_turno.value == "") {
				Toast.fire({ icon: 'error', title: '¡Seleccione el turno de trabajo!' });
				c_turno.focus();
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
						willClose: () => location.href = `${url_}/monitoreo/gestionar/${data.response.data.id}`,
					});
				});
			}
		});
	}

	// Cambiar estatus.
	Array.from(btn_cerrar).forEach(btn_ => {
		btn_.addEventListener('click', function () {
			const btn_status = this;

			// Pedimos confirmar que desea desactivar este registro.
			Swal.fire({
				title: '¿Seguro que quieres cerrar este reporte de monitoreo?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Cerrar',
				cancelButtonText: 'Cancelar',
			}).then((result) => {
				if (result.isConfirmed) {
					const form_data = new FormData();
					form_data.append('_method', 'PUT');

					// Realizamos la consulta AJAX.
					btn_status.classList.add("loading");
					btn_status.setAttribute("disabled", true);
					fetch(`${url_}/monitoreo/estatus/${btn_status.getAttribute('data-id')}`, { headers: { 'X-CSRF-TOKEN': token_ }, method: 'post', body: form_data, }).then(response => response.json()).then(data => {
						btn_status.classList.remove("loading");
						btn_status.removeAttribute("disabled");

						// Verificamos si ocurrió algún error.
						if (data.status == "error") {
							Toast.fire({ icon: data.status, title: data.response.message });
							return false;
						}

						// Actualizamos el DOM y mandamos mensaje de exito.
						const idrand = btn_status.getAttribute('data-rand');
						btn_status.remove();
						document.getElementById(`btn_edit_${idrand}`).remove();
						document.querySelector(`#contenedor_badge${idrand}`).innerHTML = `<span class="badge badge-info"><i class="fas fa-folder"></i> Cerrado</span>`;
						Swal.fire({
							title: "Exito",
							icon: data.status,
							text: data.response.message,
							timer: 2000,
						});

						// Enviamos mensaje de exito al usuario.
						// Toast.fire({ icon: data.status, title: data.response.message });
						// if (data.response.data.estatus == "A") {
						// } else {
						// 	document.querySelector(`#contenedor_badge${idrand}`).innerHTML = `<span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>`;
						// }
					});
				}
			});
		});
	});
})();