<style type="text/css">
	table {
		width: 100%;
		border-collapse: collapse;
	}

	table tr {
		width: 100%;
	}

	table td,
	table th {
		padding: 0.8mm;
	}

	table.page_header {
		width: 100%;
		border: none;
		padding: 0mm,
	}

	table.page_footer {
		width: 100%;
		border: none;
		padding: 0mm,
	}

	/* ESTILOS GLOBALES */
	.tc {
		text-align: center;
	}

	.bg-1 {
		color: white;
		background: #005791;
	}

	.bg-2 {
		color: black;
		background: #D6EAF8;
	}

	.bdr-0 {
		border-left-color: white;
		border-right-color: #004C7F;
		border-bottom-color: white;
	}

	.bdr-1 {
		border-color: #004C7F;
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
					<img src="{{public_path('images/logo.png')}}" style="width: 40mm"/>
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

	<!-- DATOS DEL CLIENTE -->
	<table border="1" style="width:100%; margin-bottom: 2mm;">
		<tr>
			<td class="bg-1 bdr-1" style="width: 20%; font-size: 8pt;"><b>NOMBRE:</b></td>
			<td class="bdr-1" style="width: 69.8%;">{{$cliente->nombre}}</td>
			<td class="bdr-1" style="width: 10.3%;"><b>COD. {{$mapa->idcodigo}}</b></td>
		</tr>
		<tr>
			<td class="bg-1 bdr-1" style="width: 20%; font-size: 8pt;"><b>DIRECCIÓN:</b></td>
			<td class="bdr-1" colspan="2">{{$mapa->direccion}}</td>
		</tr>
		<tr>
			<td class="bg-1 bdr-1" style="width: 20%; font-size: 8pt;"><b>PUNTO DE REFERENCIA:</b></td>
			<td class="bdr-1" colspan="2">{{$mapa->referencia}}</td>
		</tr>
		<tr>
			<td class="bg-1 bdr-1" style="width: 20%; font-size: 8pt;"><b>TELÉFONOS:</b></td>
			<td class="bdr-1" colspan="2">{{$cliente->telefono1}} {{$cliente->telefono2 != "" ? " / " . $cliente->telefono2 : ""}}</td>
		</tr>
	</table>

	<!-- USUARIOS -->
	<table border="1" style="margin-bottom: 3mm;">
		<tr>
			<td class="bg-1 bdr-1 tc"><b>N°</b></td>
			<td class="bg-1 bdr-1 tc"><b>NOMBRE Y APELLIDO</b></td>
			<td class="bg-1 bdr-1 tc"><b>C.I.</b></td>
			<td class="bg-1 bdr-1 tc"><b>CONTRASEÑA</b></td>
			<td class="bg-1 bdr-1 tc"><b>TELÉFONO</b></td>
		</tr>
		<?php $var = 0 ?>
		@foreach($usuarios as $usuario)
		<?php $var++ ?>
		<tr>
			<td class="bg-1 bdr-1" style="text-align: right; width: 4%;"><b>{{$var}}</b></td>
			<td class="bdr-1" style="width: 36%;">{{$usuario->nombre}}</td>
			<td class="bdr-1" style="width: 10%;">{{$usuario->idcliente}}</td>
			<td class="bdr-1" style="width: 25%;">{{$usuario->contrasena}}</td>
			<td class="bdr-1" style="width: 25%;">{{$usuario->telefono1}} {{$usuario->telefono2 != "" ? " / " . $usuario->telefono2 : ""}}</td>
		</tr>
		@endforeach
		<?php if ($var < 7) { ?>
			<?php for ($var = $var; $var < 7; $var++) { ?>
				<tr>
					<td class="bg-1 bdr-1" style="text-align: right; width: 4%;"><b><?= ($var + 1) ?></b></td>
					<td class="bdr-1" style="width: 36%;"></td>
					<td class="bdr-1" style="width: 10%;"></td>
					<td class="bdr-1" style="width: 25%;"></td>
					<td class="bdr-1" style="width: 25%;"></td>
				</tr>
			<?php } ?>
		<?php } ?>
		<tr>
			<td colspan="5" class="tc bg-1 bdr-1"><b>EN CASO DE PÁNICO, ZONAS PELIGROSAS, ENVIAR LA POLICÍA Y LUEGO VERIFICAR</b></td>
		</tr>
	</table>

	<!-- ZONAS -->
	<table border="1" style="margin-bottom: 3mm;">
		<tr>
			<td class="bg-1 bdr-1 tc"><b>N°</b></td>
			<td class="bg-1 bdr-1 tc"><b>DESCRIPCIÓN DE ZONA</b></td>
			<td class="bg-1 bdr-1 tc"><b>EQUIPOS</b></td>
			<td class="bg-1 bdr-1 tc"><b>CONFIG</b></td>
			<td class="bg-1 bdr-1 tc"><b>NOTA</b></td>
		</tr>
		<?php $var = 0 ?>
		@foreach($zonas as $zona)
		<?php $var++ ?>
		<tr>
			<td class="bg-1 bdr-1" style="text-align: right; width: 4%;"><b>{{$var}}</b></td>
			<td class="bdr-1" style="width: 31%;">{{$zona->zona}}</td>
			<td class="bdr-1" style="width: 20%;">{{$zona->dispositivo}}</td>
			<td class="bdr-1" style="width: 20%;">{{$zona->configuracion}}</td>
			<td class="bdr-1" style="width: 25%;">{{$zona->nota}}</td>
		</tr>
		@endforeach
		<?php if ($var < 7) { ?>
			<?php for ($var = $var; $var < 7; $var++) { ?>
				<tr>
					<td class="bg-1 bdr-1" style="text-align: right; width: 4%;"><b><?= ($var + 1) ?></b></td>
					<td class="bdr-1" style="width: 31%;"></td>
					<td class="bdr-1" style="width: 20%;"></td>
					<td class="bdr-1" style="width: 20%;"></td>
					<td class="bdr-1" style="width: 25%;"></td>
				</tr>
			<?php } ?>
		<?php } ?>
	</table>

	<!-- DETALLES TÉCNICOS -->
	<table border="1">
		<tr>
			<td colspan="6" class="tc bg-1 bdr-1"><b>OTROS DATOS</b></td>
		</tr>
		<tr>
			<td colspan="4" class="tc bg-2 bdr-1" style="width: 50%;"><b>HORARIO DE TRABAJO</b></td>
			<td colspan="2" class="tc bg-2 bdr-1" style="width: 50%;"><b>PANEL Y VERSIÓN DEL SISTEMA</b></td>
		</tr>
		<tr>
			<td class="tc bg-2 bdr-1" colspan="3" style="width: 22%;"><b>LUNES A VIERNES</b></td>
			<td class="tc bg-2 bdr-1" style="width: 20%;"><b>SABADO Y DOMINGO</b></td>
			<td class="bdr-1" colspan="2" rowspan="2" style="width: 50%;">{{$mapa->panel_version}}</td>
		</tr>
		<tr>
			<td class="bdr-1">08:00 AM</td>
			<td class="bdr-1">A</td>
			<td class="bdr-1">08:00 PM</td>
			<td class="bdr-1">8:00</td>
		</tr>
		<tr>
			<td class="bdr-1">08:00 AM</td>
			<td class="bdr-1">A</td>
			<td class="bdr-1">08:00 PM</td>
			<td class="bdr-1">8:00</td>
			<td class="bg-2 bdr-1" style="width: 25%;"><b>REPORTA POR:</b></td>
			<td class="bdr-1" style="width: 25%;">{{$mapa->idcodigo}}</td>
		</tr>
		<tr>
			<td class="bg-2 bdr-1" colspan="3"><b>MODELO DE TECLADO:</b></td>
			<td class="bdr-1">_</td>
			<td class="bg-2 bdr-1" style="width: 25%;"><b>TELEFONO ASIG:</b></td>
			<td class="bdr-1" style="width: 25%;">0255-6658137</td>
		</tr>
		<tr>
			<td class="tc bg-2 bdr-1" colspan="4" style="width: 50%;"><b>TÉCNICO INSTALADOR:</b></td>
			<td class="tc bg-2 bdr-1" colspan="2" style="width: 50%;"><b>FECHA DE INSTALACIÓN:</b></td>
		</tr>
		<tr>
			<td class="tc bdr-1" colspan="4" style="width: 50%;">
				@foreach($instaladores as $index => $instalador)
				{{$index > 0 ? "/" : ""}} {{$instalador->nombre}}
				@endforeach
			</td>
			<td class="tc bdr-1" colspan="2" style="width: 50%;">26/05/2017</td>
		</tr>
		<tr>
			<td class="tc bg-2 bdr-1" colspan="4" style="width: 50%;"><b>ASESOR:</b></td>
			<td class="tc bg-2 bdr-1" colspan="2" style="width: 50%;"><b>FECHA DE ENTREGA:</b></td>
		</tr>
		<tr>
			<td class="tc bdr-1" colspan="4" style="width: 50%;">MARIA</td>
			<td class="tc bdr-1" colspan="2" style="width: 50%;">26/05/2017</td>
		</tr>
		<tr>
			<td class="bg-2 bdr-1" colspan="3"><b>UBICACIÓN DE PANEL:</b></td>
			<td class="bdr-1" colspan="3">{{$mapa->ubicacion}}</td>
		</tr>
		<tr>
			<td class="bg-2 bdr-1" colspan="3"><b>PARTICIONES DEL SISTEMA:</b></td>
			<td class="bdr-1" colspan="3">{{$mapa->panel_version}}</td>
		</tr>
		<tr>
			<td class="bg-2 bdr-1" colspan="3"><b>IMEI:</b></td>
			<td class="bdr-1" colspan="3">_</td>
		</tr>
		<tr>
			<td class="bg-2 bdr-1" colspan="3"><b>OBSERVACIONES:</b></td>
			<td class="bdr-1" colspan="3">_</td>
		</tr>

		<tr>
			<td class="bg-2 bdr-1" colspan="3"><b>LINEA PRINCIPAL:</b></td>
			<td class="bdr-1"></td>
			<td class="bg-2 bdr-1"><b>LINEA DE RESPALDO:</b></td>
			<td class="bdr-1">L2</td>
		</tr>
	</table>
</page>