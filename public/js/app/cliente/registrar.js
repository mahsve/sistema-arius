(function () {
	// Elementos HTML.
	const formulario_registro = document.getElementById("formulario_registro");
	const c_tipo_identificacion_ = document.getElementById("c_tipo_identificacion");
	const lista_cedula = ["V", "E"];
	const lista_rif = ["N", "G", "J", "X"];

	// Mascaras.
	const identificacionMask = IMask(document.getElementById('c_identificacion'), { mask: '00000000' });
	const telefono1Mask = IMask(document.getElementById('c_telefono1'), { mask: '000-0000' });
	const telefono2Mask = IMask(document.getElementById('c_telefono2'), { mask: '000-0000' });

	// Eventos elementos HTML.
	c_tipo_identificacion_.addEventListener("change", function () {
		const label_ = document.querySelector('#contenedor_identificacion label');
		const input_ = document.querySelector('#contenedor_identificacion input');
		const selec_ = document.querySelector('#contenedor_identificacion select');
		selec_.innerHTML = '';
		if (this.value == "C") {
			label_.innerHTML = 'Cédula';
			input_.setAttribute("placeholder", "Ingrese la cédula");
			lista_cedula.forEach(text => selec_.innerHTML += `<option value="${text}">${text}</option>`);
		} else if (this.value == "R") {
			label_.innerHTML = 'RIF';
			input_.setAttribute("placeholder", "Ingrese el RIF");
			lista_rif.forEach(text => selec_.innerHTML += `<option value="${text}">${text}</option>`);
		}
	});



	
	// Enviar formulario.
	formulario_registro.addEventListener("submit", function (e) {
		e.preventDefault();

		// Elementos del formulario.
		const c_tipo_identificacion = document.getElementById("c_tipo_identificacion");
		const c_identificacion = document.getElementById("c_identificacion");
		const c_nombre_completo = document.getElementById("c_nombre_completo");
		const c_telefono1 = document.getElementById("c_telefono1");
		const c_telefono2 = document.getElementById("c_telefono2");
		const c_correo_electronico = document.getElementById("c_correo_electronico");
		const c_direccion = document.getElementById("c_direccion");
		const c_referencia = document.getElementById("c_referencia");

		// Validamos los campos.
		// if (c_tipo_identificacion.value == "") {

		// } else {
			fetch(`${formulario_registro.getAttribute('action')}`, { method: 'post', body: new FormData(formulario_registro) }).then(response => response.json()).then(data => {
				console.log(data);
			});
		// }
	});

	// Ejecutamos los eventos necesarios [Una sola vez].
	c_tipo_identificacion_.dispatchEvent(new Event('change'));
})();