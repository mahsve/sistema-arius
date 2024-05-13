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

<page backtop="19mm" backbottom="1mm" backleft="2mm" backright="2mm" style="font-size: 9pt">
	<page_header>
		<table class="page_header">
			<tr>
				<td style="width: 50%; text-align: left">
					<img src="{{public_path('images/logo.png')}}" style="width: 40mm" />
				</td>
				<td style="width: 50%; text-align: right">
				</td>
			</tr>
		</table>
	</page_header>

	<page_footer>
		<table class="page_footer">
			<tr>
				<td class="bg-1" style="width: 33%; padding: 3mm; text-align: left;">{{$_ENV['TITLE']}}</td>
				<td class="bg-1" style="width: 34%; padding: 3mm; text-align: center">Página [[page_cu]]/[[page_nb]]</td>
				<td class="bg-1" style="width: 33%; padding: 3mm; text-align: right">&copy; <?php echo date('Y'); ?></td>
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
			<td class="bdr-1" style="width: 4mm; border-top-color: white; border-bottom-color: white; border-left-color: white;"></td>
			<td class="bdr-1" style="width: 50mm;"><b>OPERADOR:</b></td>
			<td class="bdr-1" style="width: 50mm;"><b>{{$reporte->operador}}</b></td>
			<td class="bdr-1" style="width: 145.5mm;"><b>TURNO: {{$reporte->turno}}</b></td>
		</tr>
	</table>

	<!-- TABLA DETALLES -->
	<table border="1" style="width: 100%;">
		<!-- LEYENDA Y ESTADO AL RECIBIR -->
		<tr>
			<td class="bdr-none" style="width: 5mm; border-top-color: white; "></td>
			<td class="bdr-none" colspan="2" style="width: 100mm; text-align: center; color: white; background: #04225E;">LEYENDA</td>
			<td class="bdr-none" colspan="2" style="width: 146mm; text-align: center; color: white; background: black;"><b>ESTADO AL ENTREGAR TURNO</b></td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-1" style="width: 50mm; background: #00B0F0;">AP FUERA DE HORARIO SIN CLO</td>
			<td class="bdr-1" style="width: 50mm; background: #FF0000;">PRUEBAS REALIZADAS</td>
			<td class="bdr-1" style=""><b style="color: red;">SERVIDOR MENSAJERIA:</b></td>
			<td class="bdr-1" style="width: 100mm;">{{$reporte->servidormensajeria}}</td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-1" style="background: #00B0F0;">AP Y CLO FUERA DE HORARIO</td>
			<td class="bdr-1" style="background: #FFC000;">TÉCNICOS EN EL ÁREA</td>
			<td class="bdr-1" style=""><b style="color: red;">RADIO:</b></td>
			<td class="bdr-1" style="width: 0;">{{$reporte->radio}}</td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-1" style="background: black; color: white;">EVENTUALIDADES EN LA EMPRESA</td>
			<td class="bdr-1" style="background: #FFFF00;">EVENTOS ACUMULADOS</td>
			<td class="bdr-1" style=""><b style="color: red;">LINEAS DE REPORTE: L1, L2, L3, L4:</b></td>
			<td class="bdr-1" style="width: 0;">{{$reporte->lineasreportes}}</td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-1" style="background: #92D050;">INFORMACION RECIBIDA POR</td>
			<td class="bdr-1" style="background: #FFC5C5;">PERSONAL LABORANDO</td>
			<td class="bdr-1" style="vertical-align: middle;" rowspan="2"><b style="color: red;">OBSERVACIONES:</b></td>
			<td class="bdr-1" style="vertical-align: middle; width: 0;" rowspan="2">{{$reporte->observaciones}}</td>
		</tr>
		<tr style="font-size: 7pt;">
			<td class="bdr-none" style="border-top-color: white;"></td>
			<td class="bdr-1" style="background: #B2A1C7;">ACTIVACIONES POR FALLO</td>
			<td class="bdr-1">SIN NOVEDAD</td>
		</tr>
	</table>

	<table border="1" style="width: 100%;">
		<tr>
			<th class="bg-1 bdr-1" style="width: 5mm; text-align:center; text-align: center;">N°</th>
			<th class="bg-1 bdr-1" style="width: 15mm; text-align:center;">CÓDIGO</th>
			<th class="bg-1 bdr-1" style="width: 15mm; text-align:center;">HORA</th>
			<th class="bg-1 bdr-1" style="width: 65mm;">CLIENTE</th>
			<th class="bg-1 bdr-1" style="width: 30mm;" colspan="2">EVENTO SUCEDIDO</th>
		</tr>
		<?php $var = 0 ?>
		@foreach($eventos as $index => $evento)
		<?php $var++ ?>
		<tr style="width: 100%;">
			<td class="bg-1 bdr-1" style="text-align: right;"><b>{{($index + 1)}}</b></td>
			<td class="bdr-1" style="text-align: center;">{{$evento->idcodigo}}</td>
			<td class="bdr-1" style="text-align: center;">{{date('h:i A', strtotime($evento->hora))}}</td>
			<td class="bdr-1" style="width: 65mm;">{{$evento->cliente}}</td>
			<td class="bdr-1" style="width: 145.5mm;" colspan="2">{{$evento->evento}}</td>
		</tr>
		@endforeach
		<?php if ($var < 10) { ?>
			<?php for ($var = $var; $var < 10; $var++) { ?>
				<tr>
					<td class="bg-1 bdr-1" style="text-align: right;"><b><?= ($var + 1) ?></b></td>
					<td class="bdr-1"></td>
					<td class="bdr-1"></td>
					<td class="bdr-1"></td>
					<td class="bdr-1" colspan="2"></td>
				</tr>
			<?php } ?>
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