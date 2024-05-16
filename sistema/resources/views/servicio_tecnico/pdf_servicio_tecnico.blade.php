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
					<img src="{{public_path('images/logo_pdf.png')}}" style="width: 40mm"/>
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
		<h4 style="text-align: center; margin: 0mm;">SERVICIOS TECNICOS SOLICITADOS EN MONITOREO</h4>
	</div>

	<table border="1" style="width: 100%;">
		<tr>
			<th class="bg-1 bdr-1" style="width: 5mm; text-align:center; text-align: center;">N°</th>
			<th class="bg-1 bdr-1" style="width: 15mm; text-align:center;">ABONADO</th>
			<th class="bg-1 bdr-1" style="width: 65mm;">CLIENTE</th>
			<th class="bg-1 bdr-1" style="width: 15mm; text-align:center;">SOLICITUD</th>
			<th class="bg-1 bdr-1" style="width: 25mm; text-align:center;">MOTIVO</th>
			<th class="bg-1 bdr-1" style="width: 116mm;">EVENTO SUCEDIDO</th>
		</tr>
		<?php $var = 0 ?>
		@foreach($servicios as $index => $servicio)
		<?php $var++ ?>
		<tr style="width: 100%;">
			<td class="bg-1 bdr-1" style="text-align: right;"><b>{{($index + 1)}}</b></td>
			<td class="bdr-1" style="text-align: center;">{{$servicio->idcodigo}}</td>
			<td class="bdr-1" style="width: 65mm;">{{$servicio->nombre}}</td>
			<td class="bdr-1" style="text-align: center;">{{date('d/m/Y', strtotime($servicio->fecha))}}</td>
			<td class="bdr-1" style="width: 25mm;">{{$motivos[$servicio->motivo]}}</td>
			<td class="bdr-1" style="width: 110mm;">{{$servicio->descripcion}}</td>
		</tr>
		@endforeach
		<?php if ($var < 10) { ?>
			<?php for ($var = $var; $var < 10; $var++) { ?>
				<tr>
					<td class="bg-1 bdr-1" style="text-align: right;"><b><?= ($var + 1) ?></b></td>
					<td class="bdr-1"></td>
					<td class="bdr-1"></td>
					<td class="bdr-1"></td>
					<td class="bdr-1"></td>
					<td class="bdr-1"></td>
				</tr>
			<?php } ?>
		<?php } ?>
	</table>
</page>