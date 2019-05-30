<style>
    *{font-size: 11px;}
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
      font-size: 12px;
        /* text-align: center; */
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
                <div id="divLogo"><img id="logo" src=<?php //echo asset('storage/' .$data->agencia['logo']); ?>></div>
              </td>
              <td>
                <div id="infoAg"><?php echo $data->agencia['nombre']; ?></div>
              </td>
              <td><div id="infoAg">Radicado: # <?php echo $data->id; ?></div></td>
            </tr>
            <tr>
              <td><div id="infoAg"><?php echo $data->agencia['direccion']; ?></div></td>
              <td><div id="infoAg">Fecha: {{ $data->fecha }}</div></td>
            </tr>
            <tr>
              <td><div id="infoAg"><?php echo $data->agencia['telefono']; ?></div></td>
              <td><div id="infoAg"><?php echo 'Elaboró: ' . $data->usuario['nombre']; ?></div></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan="4">
          <table border="0" cellspacing="0" cellpadding="0" id="tableCabecera" style="width: 100%;">
            <tr>
              <td colspan="2" style="text-align: center;font-weight: bold;font-size: 15px;">
                RESPONSABLE
              </td>
              <td colspan="2" style="text-align: center;font-weight: bold;font-size: 15px;">
                INFORMACIÓN DEL CLIENTE
              </td>
            </tr>
            <tr><td style="border-bottom: 1px solid #000;" colspan="4"></td></tr>
            <tr>
              <td colspan="2"><div id="infoCli"><strong>Nombre:</strong> {{ $data->empleado['nombre'] }}</td>
              <td colspan="2"><div id="infoCli"><strong>Nombre:</strong> {{ $data->cliente['nombre'] }}</td>
            </tr>
            <tr>
              <td colspan="2"><div id="infoCli"><strong>Teléfono:</strong> {{ $data->empleado['telefono'] }}</td>
              <td colspan="2"><div id="infoCli"><strong>Teléfono:</strong> {{ $data->cliente['telefono'] }}</td>
            </tr>
            <tr>
              <td colspan="2"><div id="infoCli"><strong></td>
              <td colspan="2"><div id="infoCli"><strong>Dirección:</strong> {{ $data->cliente['direccion'] }}</td>
            </tr>
          </table>
        </td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td valign="top" colspan="4" style="height:210px;">
          <table border="1" cellspacing="0" cellpadding="0" style="width: 100%;">
            <tr>
              <th style="text-align: center;">Cantidad</th>
              <th style="width:90%;text-align: center;">Descripción</th>
            </tr>
            <?php $cont = 1 ?>
            <?php if (isset($detalle)): ?>
              <?php foreach ($detalle as $val): ?>
                <tr>
                  <td id="detalle" style="text-align: center;"> {{ $val->cantidad }} </td>
                  <td id="detalle"> {{ $val->descripcion }} </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <table style="width: 100%;">
    <tr>
      <td><div id="line">____________________________________________</div></td>
      <td><div id="line">____________________________________________</div></td>
    </tr>
    <tr>
      <td>
        <div id="firma">RECIBI A CONFORMIDAD:</div>
        <div id="firma">C.C/NIT:</div>
        <div id="firma">FECHA Y HORA:</div>
      </td>
      <td><div id="firma">ENTREGADO POR:</div></td>
    </tr>
  </table>
  <div class="spacer"></div>
<?php endfor ?>
<script  type="text/javascript">
    // function printHTML() {
    //    if (window.print) {
    //        window.print();
    //    }
    // }
    // document.addEventListener("DOMContentLoaded", function (event) {
    //     printHTML();
    // });
</script>
