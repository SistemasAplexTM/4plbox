<!DOCTYPE>
<html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Label WH{{ $invoice->consecutivo }}</title>
        <style>
            *{
                font-size: 15px;
                font-family: sans-serif;
            }
            .agency{
              font-size: 40px;
              font-weight: bold;
            }
            .agency_data{
              font-size: 25px;
              font-weight: bold;
            }
            .title{
              font-size: 15px;
              margin-left: 5px;
            }
            .consignee, .item{
              font-size: 25px;
              margin-left: 5px;
              font-weight: bold;
              margin-bottom: 20px;
            }
            .consecutive{
              font-size: 80px;
              margin-left: 5px;
              font-weight: bold;
            }
            .location, .piece{
              font-size: 50px;
              font-weight: bold;
              text-align: center;
            }
            .code{
              /* font-size: 50px;
              font-weight: bold; */
              text-align: center;
            }
            .barcode{
              text-align: center;
            }
            .barcode_name{
              font-size: 50px;
              text-align: center;
            }
            .content, .weight{
              font-size: 25px;
              font-weight: bold;
              margin-left: 5px;
            }
        </style>
      </head>
    <body>
        <?php
            $cont = 0;
            $totalRegistros = count($detalle);
            $contRegistros = 0;
            $piezas = $totalRegistros;
            $i = 1;
        ?>
        @foreach ($detalle as $value)
            {{-- @for($i = 1; $i <= $piezas; $i++) --}}
            <?php $contRegistros++ ?>
            <table border="0" cellpadding="0" cellspacing="0" id="invoice" style="page-break-after:{{ ($contRegistros === $totalRegistros) ? 'avoid' : 'always' }}" width="100%">
                <tr>
                    <td>
                        <div class="agency">{{ $invoice->agencia }}</div>
                        <div class="agency_data">{{ $invoice->agencia_dir }}</div>
                        <div class="agency_data">{{ $invoice->agencia_tel }}</div>
                        <div class="agency_data">{{ $invoice->agencia_zip }}</div>
                    </td>
                </tr>
                <tr>
                  <td>
                    <table border="1" cellpadding="0" cellspacing="0" width="100%" style="margin-top:20px;">
                      <tr>
                        <td style="width:80%">
                          <div class="title">CONSIGNEE</div>
                          <div class="consignee">
                            {{ $invoice->consignee }}
                          </div>
                        </td>
                        <td>
                          <div class="title">ITEM</div>
                          <div class="item">{{ $i }}</div>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          <div class="title">WAREHOUSE RECEIPT NUMBER</div>
                          <div class="consecutive">{{ $invoice->consecutive }}</div>
                        </td>
                      </tr>
                    </table>
                    <table border="1" cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td style="width:32%">
                          <div class="title">LOCATION</div>
                          <div class="location">&nbsp;</div>
                        </td>
                        <td>
                          <div class="title">PIECE</div>
                          <div class="piece">{{ $i }}</div>
                        </td>
                        <td style="width:32%">
                          <div class="title">TOTAL PIECES</div>
                          <div class="piece">{{ $totalRegistros }}</div>
                        </td>
                      </tr>
                    </table>
                  <table border="1" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td>
                        <div class="title">TRACKING NUMBER (System ID-Warehouse Receipt-Item ID)</div>
                        <div class="barcode">
                          <img style="height: 100px;padding-top: 25px;" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($value->num_warehouse, "C128",2,40) }}" alt="barcode" />
                          <div class="barcode_name">{{ $value->num_warehouse }}</div>
                        </div>
                      </td>
                    </tr>
                  </table>
                  <table border="1" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td style="width:80%;">
                        <div class="title">DESCRIPTION</div>
                        <div class="content">&nbsp;</div>
                      </td>
                      <td>
                        <div class="title">WEIGHT</div>
                        <div class="weight">{{ $value->peso }} Lb</div>
                      </td>
                    </tr>
                  </table>
                  <table border="1" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td style="width:25%;">
                        <div class="title">DIMENSIONS</div>
                        <div class="content">{{ $value->largo.'x'.$value->ancho.'x'.$value->alto }}</div>
                      </td>
                      <td style="width:25%;">
                        <div class="title">PART NUMBER</div>
                        <div class="content">&nbsp;</div>
                      </td>
                      <td style="width:25%;">
                        <div class="title">MODEL</div>
                        <div class="weight">&nbsp;</div>
                      </td>
                      <td style="width:25%;">
                        <div class="title">SERIAL NUMBER</div>
                        <div class="weight">&nbsp;</div>
                      </td>
                    </tr>
                  </table>
                  <table border="1" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td>
                        <div class="title">NOTES</div>
                        <div class="content">&nbsp;</div>
                      </td>
                    </tr>
                  </table>
                  </td>
                </tr>
                <?php $cont++ ?>
                <?php $i++ ?>
            </table>
            {{-- @endfor --}}
        @endforeach
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
    </body>
</html>
