(function () {
	//
	const btn_modal_tarea = document.getElementById("btn_modal_tarea");
	const modal_tarea = new bootstrap.Modal(document.getElementById("modal_tarea"));
	const formulario_registrar_tarea = document.getElementById("formulario_registrar_tarea");

	//
	btn_modal_tarea.addEventListener("click", function (e) {
		e.preventDefault();


		modal_tarea.show();
	});

	
})();