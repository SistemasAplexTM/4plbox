<style media="screen">
table, th, td {
  border: 1px solid gray;
  padding: 5px;
}
th {
  background-color: #bfbfbf;
}
h2, h4 {
  margin: 0px;
  padding: 0px;
}
</style>
<body>
  <div class="">
    <h2>MASTER CARGO CORP</h2>
    <h2>REPORTE DIARIO DE CARGA</h2>
  </div>
  <div class="">
    <h4>COMEXCO INC</h4>
    <h4>2031 NW 112 AVE - MIAMI, FLORIDA, 31681</h4>
  </div>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
    <thead>
      <tr>
        <th>#</th>
        <th>Guía</th>
        <th>País</th>
        <th>Fecha</th>
        <th>Destinatario</th>
        <th>Piezas</th>
        <th>Peso</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $key => $value)
        <tr>
          <td>{{ $key }}</td>
          <td>{{ $value->num_guia }}</td>
          <td>{{ $value->pais }}</td>
          <td>{{ $value->pais }}</td>
          <td>{{ $value->pais }}</td>
          <td>{{ $value->pais }}</td>
          <td>{{ $value->pais }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
