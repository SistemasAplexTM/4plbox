<!doctype html>
<html>
	<head>
	<meta charset="utf-8">
	<title>Liquimp</title>
	<style type="text/css">
		.content{
			border-collapse: collapse;
		}
		.content > thead{
			font-weight: bold;
		}
	</style>
	</head>

	<body>
		<table class="content" border="1">
			<thead>
				<tr>
					<td>Guia#</td>
					<td>P.A</td>
					<td>IVA - Arancel</td>
					<td>Descripción</td>
					<td>Valor Declarado</td>
					<td>Peso lb</td>
					<td>Peso kl</td>
					<td>Piezas</td>
					<td>Nombre Remitente</td>
					<td>Direccion Remitente</td>
					<td>Ciudad Remitente</td>
					<td>Estado Remitente</td>
					<td>Zip Remitente</td>
					<td>Tel. Remitente</td>
					<td>Nombre Destinatario</td>
					<td>Direccion Destinatario</td>
					<td>Ciudad Destinatario</td>
					<td>Estado Destinatario</td>
					<td>Tel. Destinatario</td>
					<td>Zip Destinatario</td>
				</tr>
			</thead>
			<tbody>
				@foreach ($data as $key => $value)
					<tr>
						<td>{{ $value->num_guia }}</td>
						<td>{{ $value->pa }}</td>
						<td>{{ $value->iva }} - {{ $value->arancel }}</td>
						<td>{{ $value->contenido }}</td>
						<td>{{ $value->declarado }}</td>
						<td>{{ $value->peso_lb }}</td>
						<td>{{ number_format($value->peso_lb * 0.453592, 2) }}</td>
						<td>{{ $value->piezas }}</td>
						<td>{{ $value->ship }}</td>
						<td>{{ $value->ship_dir }}</td>
						<td>{{ $value->ship_ciu }}</td>
						<td>{{ $value->ship_depto }}</td>
						<td>{{ $value->ship_zip }}</td>
						<td>{{ $value->ship_tel }}</td>
						<td>{{ $value->cons }}</td>
						<td>{{ $value->cons_dir }}</td>
						<td>{{ $value->cons_ciu }}</td>
						<td>{{ $value->cons_depto }}</td>
						<td>{{ $value->cons_tel }}</td>
						<td>{{ $value->cons_zip }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</body>
</html>
