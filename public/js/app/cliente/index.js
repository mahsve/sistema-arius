(function () {
	// Elementos HTML.
	const switch_estatus = document.querySelectorAll(".switch_estatus");

	// Eventos elementos HTML.
	Array.from(switch_estatus).forEach(switch_ => {
		switch_.addEventListener('change', function () {
			// Capturamos el elemento que provoco el evento.
			switch_element = this;
			switch_element.checked = !switch_element.checked;

			// Pedimos confirmar que desea desactivar este registro.
			Swal.fire({
				title: '¿Seguro que quieres cambiar el estatus de este cliente?',
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
					fetch(`${url_}/clientes/estatus/${switch_element.value}`, {
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