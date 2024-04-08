<style type="text/css">
	table {
		border-collapse: collapse;
	}

	table td,
	table th {
		padding: 0.8mm;
	}

	table.page_header {
		width: 100%;
		border: none;
		padding: 0mm
	}

	table.page_footer {
		width: 100%;
		border: none;
		padding: 0mm
	}

	/* ESTILOS GLOBALES */
	.bg-1 {
		color: white;
		background: #2874A6;
	}

	.bdr-0 {
		border-left-color: white;
		border-right-color: #2874A6;
		border-bottom-color: white;
	}

	.bdr-1 {
		border-color: #2874A6;
	}

	.bdr-none {
		border: 0px;
	}
</style>

<?php
$sucesos = [
	[
		'5056',
		'8:00',
		'CARSAL TOYOTA',
		'Z 3, 7, SRA SOBEIDA NO ATENDIO SE NOTIFICA VIA SMS',
	],
	[
		'1055',
		'8:38',
		'LA CASA DEL CUATRO',
		'SE RECIBE LLAMDA DEL SR HENDRICK NOTIFICANDO RESTAURACION DE SU SERVICIO DE INTERNET, A LA ESPERA DE LOS EVENTOS AL MOMENTO DE RESTAURAR SU TONO TELEFONICO PANADERIA PLAZA DE ARAURE Z 9',
	],
	[
		'5085',
		'9:50',
		'PANADERIA PLAZA DE ARAURE',
		'SRA ELIZABETH NO ATENDIO, SE ENVIA SMS',
	],
	[
		'1144',
		'10:09',
		'VEROK',
		'ACT ZONA "LOCAL", SRA NAYIBE DESVIO LA LLAMADA EN 2 OCACIONES, SE NOTIFICA VIA SMS',
	]
];
?>


<page backtop="2mm" backbottom="2mm" backleft="2mm" backright="2mm" style="font-size: 9pt">
	<page_header>
		<table class="page_header">
			<tr>
				<td style="width: 50%; text-align: left">
					<!-- <img src="{{url('')}}/images/{{$_ENV['LOGO_DARK']}}" alt="{{$_ENV['TITLE']}}" style="width: 50mm"> -->
				</td>
				<td style="width: 50%; text-align: right">
				</td>
			</tr>
		</table>
	</page_header>

	<page_footer>
		<table class="page_footer">
			<tr>
				<td style="width: 33%; text-align: left;">{{$_ENV['TITLE']}}</td>
				<td style="width: 34%; text-align: center">Página [[page_cu]]/[[page_nb]]</td>
				<td style="width: 33%; text-align: right">&copy; <?php echo date('Y'); ?></td>
			</tr>
		</table>
	</page_footer>

	<!-- TITULO REPORTE -->
	<div class="bg-1" style="width: 100%; padding: 1mm; margin-bottom: 3mm;">
		<h4 style="text-align: center; margin: 0mm;">REPORTES DIARIOS DE OPERADORES</h4>
	</div>

	<!-- DESCRIPCIÓN OPERADOR -->
	<table border="1" style="width: 100%; margin-bottom: 3mm;">
		<tr>
			<td class="bdr-1" style="width: 33mm;"><b>OPERADOR:</b></td>
			<td class="bdr-1" style="width: 46mm;"><b>ISRAEL QUEVEDO</b></td>
			<td class="bdr-1" style="width: 111mm;"><b>TURNO: 07:00 AM A 07:00 PM</b></td>
		</tr>
	</table>

	<!-- TABLA DETALLES -->
	<table border="1" style="width: 100%;">
		<!-- LEYENDA Y ESTADO AL RECIBIR -->
		<tr>
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-none" colspan="3" style="text-align: center; color: white; background: #04225E;">LEYENDA</td>
			<td class="bdr-none" colspan="2" style="text-align: center; color: white; background: black;"><b>ESTADO AL ENTREGAR TURNO</b></td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-0" style="background: #3498DB;">AP FUERA DE HORARIO SIN CLO</td>
			<td class="bdr-0" style="background: #3498DB;">PRUEBAS REALIZADAS</td>
			<td class="bdr-1" style="border-left: 1px solid black;"><b style="color: red;">SERVIDOR MENSAJERIA:</b></td>
			<td class="bdr-1" style="width: 0;"></td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-0" style="background: #3498DB;">AP Y CLO FUERA DE HORARIO</td>
			<td class="bdr-0" style="background: #3498DB;">TÉCNICOS EN EL ÁREA</td>
			<td class="bdr-1" style="border-left: 1px solid black;"><b style="color: red;">RADIO:</b></td>
			<td class="bdr-1" style="width: 0;"></td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-0" style="background: #3498DB;">EVENTUALIDADES EN LA EMPRESA</td>
			<td class="bdr-0" style="background: #3498DB;">EVENTOS ACUMULADOS</td>
			<td class="bdr-1" style="border-left: 1px solid black;"><b style="color: red;">LINEAS DE REPORTE: L1, L2, L3, L4:</b></td>
			<td class="bdr-1" style="width: 0;"></td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-0" style="background: #3498DB;">INFORMACION RECIBIDA POR</td>
			<td class="bdr-0" style="background: #3498DB;">PERSONAL LABORANDO</td>
			<td class="bdr-1" style="border-left: 1px solid black;"><b style="color: red;">OBSERVACIONES:</b></td>
			<td class="bdr-1" style="width: 0;"></td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-0" style="background: #3498DB;">ACTIVACIONES POR FALLO</td>
			<td class="bdr-0" style="background: #3498DB;">SIN NOVEDAD</td>
			<td class="bdr-1" style="border-left: 1px solid black;"> </td>
			<td class="bdr-1" style="width: 0;"></td>
		</tr>

		<!-- LISTADO DE SUCESOS -->
		<tr>
			<th class="bg-1 bdr-1" style="width: 4mm;  text-align:center; text-align: center;">N°</th>
			<th class="bg-1 bdr-1" style="width: 11mm; text-align:center;">CÓDIGO</th>
			<th class="bg-1 bdr-1" style="width: 10mm; text-align:center;">HORA</th>
			<th class="bg-1 bdr-1" style="width: 30mm;">CLIENTE</th>
			<th class="bg-1 bdr-1" style="width: 30mm;" colspan="2">EVENTOSUCEDIDO</th>
		</tr>
		<?php for ($var = 0; $var < count($sucesos); $var++) { ?>
			<tr style="width: 100%;">
				<td class="bg-1 bdr-1" style="text-align: right;"><b><?= ($var + 1) ?></b></td>
				<td class="bdr-1" style="text-align: center;"><?= $sucesos[$var][0] ?></td>
				<td class="bdr-1" style="text-align: center;"><?= $sucesos[$var][1] ?></td>
				<td class="bdr-1" style="width: 46mm;"><?= $sucesos[$var][2] ?></td>
				<td class="bdr-1" style="width: 111mm;" colspan="2"><?= $sucesos[$var][3] ?></td>
			</tr>
		<?php } ?>

		<!-- ESTADO AL ENTREGAR -->
		<tr>
			<td class="bdr-0" colspan="4"></td>
			<td class="bdr-1" colspan="2" style="text-align: center; color: white; background: black;"><b>ESTADO AL ENTREGAR TURNO</b></td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-0" colspan="4"></td>
			<td class="bdr-1" style="border-left: 1px solid black;"><b style="color: red;">SERVIDOR MENSAJERIA:</b></td>
			<td class="bdr-1" style="width: 65mm;"></td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-0" colspan="4"></td>
			<td class="bdr-1" style="border-left: 1px solid black;"><b style="color: red;">RADIO:</b></td>
			<td class="bdr-1" style="width: 65mm;"></td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-0" colspan="4"></td>
			<td class="bdr-1" style="border-left: 1px solid black;"><b style="color: red;">LINEAS DE REPORTE: L1, L2, L3, L4:</b></td>
			<td class="bdr-1" style="width: 65mm;"></td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-0" colspan="4"></td>
			<td class="bdr-1" style="border-left: 1px solid black;"><b style="color: red;">OBSERVACIONES:</b></td>
			<td class="bdr-1" style="width: 65mm;"></td>
		</tr>
	</table>
</page>