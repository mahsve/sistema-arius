(function () {
	// Elementos HTML.
	const modal_clientes = new bootstrap.Modal('#modal_clientes');
	const btn_nuevo_mapa = document.getElementById('btn_nuevo_mapa');
	const tabla_clientes_tbody = document.querySelector('#tabla_clientes tbody');
	
	// Eventos elementos HTML.
	btn_nuevo_mapa.addEventListener('click', (e) => {
		e.preventDefault();

		// Realizamos la consulta AJAX.
		btn_nuevo_mapa.classList.add('loading');
		fetch(`${url_}/consultar_clientes`, { method: 'get' }).then(response => response.json()).then(data => {
			btn_nuevo_mapa.classList.remove('loading');

			// Cargamos la información en la tabla.
			tabla_clientes_tbody.innerHTML = "";
			if (data.length > 0) {
				for (let i = 0; i < data.length; i++) {
					let cliente = data[i];
					tabla_clientes_tbody.innerHTML += `
						<tr>
							<td class="py-1 px-2">${cliente.identificacion}</td>
							<td class="py-1 px-2">${cliente.nombre_completo}</td>
							<td class="py-1 px-2">
								<a href="${url_}/mapas_de_zonas/registrar/${cliente.identificacion}" class="btn btn-primary btn-sm"><i class="fas fa-user-check"></i></a>
							</td>
						</tr>
					`;
				}
			}

			// Mostramos la modal con la información.
			modal_clientes.show();
		});
	});
})();