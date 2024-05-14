(function () {
	// Elementos HTML.
	const btn_detalles = document.querySelectorAll(".btn_detalles");
	const btn_buscar_por_fecha = document.getElementById("btn_buscar_por_fecha");
	const modal_detalles = document.getElementById('modal_detalles') != null ? new bootstrap.Modal('#modal_detalles') : null;

	// Consultar registro.
	if (btn_detalles.length > 0) {
		Array.from(btn_detalles).forEach(btn_ => {
			btn_.addEventListener('click', function (e) {
				e.preventDefault();

				// Capturamos el elemento que provoco el evento.
				const btn_consultar = this;
				const id_data = btn_consultar.getAttribute('data-id');

				// Realizamos la consulta AJAX.
				btn_consultar.classList.add('loading');
				btn_consultar.setAttribute('disabled', true);
				fetch(`${url_}/bitacora/${id_data}`, { method: 'get' }).then(response => response.json()).then((data) => {
					btn_consultar.classList.remove('loading');
					btn_consultar.removeAttribute('disabled');

					// Limpiamos el formulario y cargamos los datos consultados.
					document.getElementById('mostrar-detalles-bitacora').innerHTML = `<div class="form-row">
						<div class="col-6">
							<div class="form-group mb-3">
								<label for="c_codigo_r"><i class="fas fa-calendar-day"></i> Fecha</label>
								<span class="d-block border rounded small fw-bold p-3 h-100 bg-dark text-white">${data.fecha}</span>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group mb-3">
								<label for="c_codigo_r"><i class="fas fa-laptop"></i> IP</label>
								<span class="d-block border rounded small fw-bold p-3 h-100 bg-dark text-white">${data.ip}</span>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group mb-3">
								<label for="c_codigo_r"><i class="fab fa-chrome"></i> Navegador</label>
								<span class="d-block border rounded small fw-bold p-3 h-100 bg-dark text-white">${data.navegador}</span>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group mb-3">
								<label for="c_codigo_r"><i class="fas fa-user"></i> Usuario</label>
								<span class="d-block border rounded small fw-bold p-3 h-100 bg-dark text-white">${data.usuario} - ${data.nombre}</span>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group mb-3">
								<label for="c_codigo_r"><i class="fas fa-exchange-alt"></i> Operación</label>
								<span class="d-block border rounded small fw-bold p-3 h-100 bg-dark text-white">${data.operacion}</span>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group mb-3">
								<label for="c_codigo_r"><i class="fas fa-history"></i> Descripción</label>
								<span class="d-block border rounded small fw-bold p-3 h-100 bg-dark text-white">${data.descripcion}</span>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group mb-3">
								<label for="c_codigo_r"><i class="fas fa-server"></i> User Agent</label>
								<span class="d-block border rounded small fw-bold p-3 h-100 bg-dark text-white">${data.user_agent}</span>
							</div>
						</div>
					</div>`;
					modal_detalles.show();
				});
			});
		});
	}

	// Manejar el fitro para buscar por fechas.
	if (btn_buscar_por_fecha != null) {
		btn_buscar_por_fecha.addEventListener("click", function (e) {
			e.preventDefault();

			// Elementos del formulario.
			const fecha_inicio = document.getElementById("fecha_inicio");
			const fecha_final = document.getElementById("fecha_final");

			// Validamos los campos.
			if (fecha_inicio.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese la fecha de inicio!' });
				fecha_inicio.focus();
			} else if (fecha_final.value == "") {
				Toast.fire({ icon: 'error', title: '¡Ingrese la fecha tope!' });
				fecha_final.focus();
			} else if (fecha_inicio.value > fecha_final.value) {
				Toast.fire({ icon: 'error', title: '¡La fecha de inicio no puede ser superior a la fecha tope!' });
				fecha_inicio.focus();
			} else {
				location.href = `${url_}/servicios_tecnico?fecha_inicio=${fecha_inicio.value}&fecha_tope=${fecha_final.value}`;
			}
		});
	}
})();