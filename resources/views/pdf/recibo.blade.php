<style>
    #mvcIcon, #mvcMain{
        display: none;
    }
    #imgLogo{
        width: 500px;
        margin-top: 20px;
    }
    #tableContainer{
        font-size: 13px;
        font-family: sans-serif;
    }
    #tableRecibo{
        font-size: 13px;
        font-family: sans-serif;
        border: 1px solid #030303;
        padding: 10px;
        margin-top: 40px;
    }
    #tableRecibo td{
        padding: 8px;
    }
    #tableContainer,#tableRecibo{
        width: 100%;
    }
    #divLogo{
        width: 100px;
        height: 80px;
    }
    #logo{
        padding: 2px;
        width: 100%;
        height: 70px;
    }
    #infoAg{
        font-size: 15px;
        font-weight: bold;
        text-align: center;
    }
    #infoCli{
        padding: 2px;
    }
    #space{
        border: 1px dashed #000;
        padding: 1px;
        margin-bottom: 9px;
        margin-top: 7px;
    }
    #line{
        padding-top: 10px;
    }
    #line,#firma{
        text-align: center;
    }
    #detalle{
        padding: 2px;
    }
    #titulo{
        font-weight: bold;
    }
    .spacer{ margin-top: 30px}
</style>
<?php for ($i=1; $i < 3; $i++): ?>
  <table border="1" cellspacing="0" cellpadding="0" id="tableContainer">
  <thead>
  <tr>
  <td colspan="4">
  <table border="0" cellspacing="0" cellpadding="0" id="tableCabecera" style="width: 100%;">
  <tr>
  <td rowspan="3" style="width: 100px;">
  <div id="divLogo"><img id="logo" src=<?php echo asset('storage/' .$agencia->logo); ?>></div>
</td>
<td>
  <div id="infoAg"><?php echo $agencia->descripcion; ?></div>
</td>
<td><div id="infoAg">RECIBO DE ENTREGA:</div></td>
</tr>
<tr>
  <td><div id="infoAg"><?php echo $agencia->direccion . ' / ' . $agencia->ciudad . ' - ' . $agencia->pais; ?></div></td>
  <td><div id="infoAg"># <?php echo $recibo->numero_recibo; ?></div></td>
</tr>
<tr>
  <td><div id="infoAg"><?php echo $agencia->telefono; ?></div></td>
  <td><div id="infoAg"><?php echo 'Entrego: ' . $recibo->name; ?></div></td>
</tr>
</table>
</td>
</tr>
<tr>
  <td colspan="4" style="text-align: center;font-weight: bold;font-size: 15px;">
    INFORMACIÓN DEL CLIENTE
  </td>
</tr>
<tr>
  <td colspan="4">
    <table border="0" cellspacing="0" cellpadding="0" id="tableCabecera" style="width: 100%;">
      <tr>
        <td>
          <div id="infoCli"><strong>Fecha:</strong> <?php echo substr($recibo->created_at, 0 , 10); ?></div>
        </td>
        <td>
          <div id="infoCli"><strong>Transportador:</strong> <?php echo $recibo->transportador; ?></div>
        </td>
      </tr>
      <tr>
        <td>
          <div id="infoCli">
            <strong>Nombre:</strong>
            <?php echo ($recibo->consignee_id) ? $consignee->primer_nombre . ' ' . $consignee->segundo_nombre . ' ' . $consignee->primer_apellido . ' ' . $consignee->segundo_apellido  : $recibo->cliente; ?>
          </div>
        </td>
        <td><div id="infoCli"><strong>Dirección:</strong> <?php echo $cliente->direccion; ?></div></td>
      </tr>
      <tr>
        <td><div id="infoCli"><strong>Teléfono:</strong> <?php echo $cliente->telefono; ?></div></td>
        <td><div id="infoCli"><strong>Ciudad:</strong> <?php echo $cliente->ciudad; ?></div></td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <th>Item</th>
  <th>Número de Warehouse</th>
  <th>Cantidad</th>
  <th style="width: 50%;">Observación</th>
</tr>
</thead>
<tbody>
  <?php $cont = 1 ?>
  <?php foreach ($reciboD as $value): ?>
    <tr>
      <td id="detalle"><?php echo $cont++ ?></td>
      <td id="detalle">
        <?php
        echo $value->num_warehouse
        // if ($value->wrh_guia == 0) {
          //     echo warehouseDetalleTableClass::getNumWarehouseById($value->id_documento_wrh_guia);
          // } else {
            //     echo guiaTableClass::getNumGuia($value->id_documento_wrh_guia);
            // }
            ?>
          </td>
          <td id="detalle"><?php echo $value->cantidad ?></td>
          <td id="detalle"><?php echo $value->trackings ?></td>
        </tr>
      <?php endforeach; ?>
      <?php
      $cantR = count($reciboD);
      $filas = 10 - $cantR;
      if ($filas > 0):
      ?>
      <?php for ($f = 1; $f <= $filas; $f++): ?>
        <tr>
          <td id="detalle"><?php echo $cont++ ?></td>
          <td id="detalle"><?php echo '' ?></td>
          <td id="detalle"><?php echo '' ?></td>
          <td id="detalle"></td>
        </tr>
      <?php endfor; ?>
    <?php endif; ?>
  </tbody>
</table>
<table style="width: 100%;">
  <tr>
    <td><div id="line">_______________________________</div></td>
    <td><div id="line">_______________________________</div></td>
  </tr>
  <tr>
    <td><div id="firma">RECIBI A CONFORMIDAD:</div></td>
    <td><div id="firma">C.C/NIT:</div></td>
  </tr>
</table>
<div class="spacer"></div>
<?php endfor ?>
<script  type="text/javascript">
    function printHTML() {
       if (window.print) {
           window.print();
       }
    }
    document.addEventListener("DOMContentLoaded", function (event) {
        printHTML();
    });
</script>
