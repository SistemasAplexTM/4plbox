<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Bodega</title>
	<style type="text/css">
		.content {
			border-collapse: collapse;
		}

		.content>thead {
			font-weight: bold;
		}
	</style>
</head>

<body>
	<table class="content" border="1">
		<thead>
			<tr>
				<th colspan="8" class="title">MASTER: </th>
				<th colspan="3" class="title">TOTAL GUIAS: {{ count($data) }}</th>
				<th colspan="2" class="title">NACIONALIZACIONES: </th>
			</tr>
			<tr>
				<th colspan="8" class="title">CONSOLIDADO: {{ $data[0]->consecutivo }}</th>
				<th colspan="3" class="title">FI: </th>
				<th colspan="2" class="title">OBSERVACION: </th>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>Guia#</td>
				<td>Tracking</td>
				<td>Peso lb</td>
				<td>&nbsp;</td>
				<td>Peso kl</td>
				<td>Nombre Destinatario</td>
				<td>Direccion Destinatario</td>
				<td>Tel. Destinatario</td>
				<td>Ciudad Destinatario</td>
				<td>Estado Destinatario</td>
				<td>Contenido</td>
				<td>Valor Declarado</td>
			</tr>
		</thead>
		<tbody>
			@foreach ($data as $key => $value)
			<tr>
				<td>&nbsp;</td>
				<td>{{ $value->num_guia }}</td>
				<td>{{ $value->tracking }}</td>
				<td>{{ $value->peso_lb }}</td>
				<td>&nbsp;</td>
				<td>{{ number_format($value->peso_lb * 0.453592, 2) }}</td>
				<td>{{ $value->cons }}</td>
				<td>{{ $value->cons_dir }}</td>
				<td>{{ $value->cons_ciu }}</td>
				<td>{{ $value->cons_depto }}</td>
				<td>{{ $value->cons_tel }}</td>
				<td>{{ $value->contenido }}</td>
				<td>{{ $value->declarado }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>

</html>