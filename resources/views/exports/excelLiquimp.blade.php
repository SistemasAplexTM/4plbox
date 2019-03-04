<!doctype html>
<html>
	<head>
	<meta charset="utf-8">
	<title>Documento sin título</title>
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
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
						<td>{{ $value->num_warehouse }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</body>
</html>
