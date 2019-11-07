<style>
    * {
        font-size: 11px;
        font-family: sans-serif;
        font-weight: bold;
    }

    #mvcIcon,
    #mvcMain {
        display: none;
    }

    .divTable0 {
        float: left;
        border: solid 1px #000;
        width: 420px;
    }

    .container {
        width: 420px;
        height: 350px;
        margin: 0 auto;
    }

    .fecha {
        color: #FFFFFF;
        background-color: #000;
        width: 188px;
        font-size: 15px;
        height: 19px;
    }

    .peso {
        font-size: 26px;
        text-align: center;
        font-weight: bold;
        padding-top: 15px;
    }

    .paginacion {
        font-size: 20px;
        text-align: center;
    }

    .warehouse {
        margin-top: 8px;
        border-top: 1px dashed #000;
        text-align: center;
    }

    .remitente {
        font-size: 11px;
        padding-top: 20px;
    }

    .telefono {
        font-size: 13px;
        height: 16px;
    }

    .agencia {
        font-size: 15px;
        width: 200px;
        text-align: right;
        color: #FFFFFF;
        position: absolute;
        background-color: #000;
        height: 19px;
        font-weight: bold;
        margin-left: 220px;
        margin-top: -28px;
    }

    #destinatario {
        margin-top: 5px;
    }

    .recibe {
        height: 18px;
        background-color: #000;
        color: #FFFFFF;
        width: 85px;
        font-size: 15px;
        font-weight: bold;
    }

    .nomDesti {
        position: absolute;
        height: 15px;
        margin-left: 90px;
        width: 310px;
        font-size: 11px;
    }

    .dirDesti {
        margin-left: 90px;
        width: 310px;
        font-size: 11px;
    }

    .ciudad {
        font-size: 15px;
        font-weight: bold;
        vertical-align: middle;
        text-align: center;
    }

    .datosAdd {
        height: 136px;
        margin-top: 4px;
        background-color: #000;
        padding-top: 1px;
    }

    .codebar1 {
        width: 90px;
        height: 133px;
        background-color: #ffffff;
        margin-left: 3px;
    }

    .datos {
        background-color: #ffffff;
        position: absolute;
        width: 320px;
        height: 133px;
        margin-left: 97px;
        margin-top: 5px;
    }

    .pkgs,
    .fob,
    .des,
    .tracking,
    .servicio {
        margin-left: 5px;
    }

    .servicio {
        height: 28px;
    }

    .tracking,
    .servicio {
        font-size: 15px;
        font-weight: bold;
    }

    .tracking {
        height: 35px;
    }

    .iatacode {
        background-color: #000;
        color: #FFFFFF;
        font-size: 23px;
        font-weight: bold;
        position: absolute;
        height: 25px;
        margin-left: 200px;

    }

    .codebar2 {
        margin-top: 5px;
        margin-bottom: 2px;
        vertical-align: middle;
        text-align: center;
        width: 100%;
        height: 100px;
        position: absolute;

    }

    #imgbarcode {
        width: 400px;
        height: 90px;
    }

    .des {
        height: 82px;
    }

    #descripcion {
        font-size: 11px;
    }

    #barcode-name {
        font-size: 30px;
    }
</style>
<?php
	$cont = 0;
  $toalRegistros = count($detalle);
  $contRegistros = 0;
//   $shipper = null;
//   $consignee = null;
//   if($dato_consolidado != null){
//     if($dato_consolidado->shipper != null){
//       $shipper = json_decode($dato_consolidado->shipper);
//     }
//     if($dato_consolidado->consignee != null){
//       $consignee = json_decode($dato_consolidado->consignee);
//     }
//   }
?>
@foreach ($detalle as $value)
<?php $contRegistros++ ?>
<table border="0" cellpadding="0" cellspacing="0" class="reciboWarehouse" id="reciboWarehouse"
    style="page-break-after:{{ ($contRegistros === $toalRegistros) ? 'avoid' : 'always' }}" width="100%">
    <tr>
        <td>
            <div class="fecha">
                {{ substr($value->created_at, 0, -9) }}
            </div>
            <div class="agencia">
                {{ $value->agencia }}
            </div>
        </td>
    </tr>

    <tr>
        <td>
            @if(env('APP_CLIENT') != 'worldcargo')
            <div class="remitente">
                {{ ($shipper) ? $shipper->nombre_full : $value->ship_nomfull }}
            </div>
            @endif
        </td>
    </tr>
    <tr>
        <td>
            <div class="telefono">
                @if(env('APP_CLIENT') != 'worldcargo')
                @lang('general.phone'):
                {{-- {{ ($shipper != null) ? $shipper->telefono : $value->ship_tel }} --}}
                {{ ($shipper) ? $shipper->telefono : $value->ship_tel }}
                @endif
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div id="destinatario">
                <div class="recibe">
                    @lang('general.consignee'):
                </div>
                <div class="nomDesti">
                    {{ ($consignee) ? $consignee->nombre_full : $value->cons_nomfull }}
                </div>
                <div class="dirDesti">
                    {{ ($consignee) ? $consignee->direccion : $value->cons_dir }}
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="ciudad">
                {{ ($consignee) ? $consignee->ciudad :$value->cons_ciudad }}
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div class="datosAdd">
                <div class="codebar1">
                    <div class="peso">
                        &nbsp;
                        {{ (isset($consolidado) and $consolidado != null) ? $value->peso2 : $value->peso }} Lb
                    </div>
                    <div class="paginacion">{{ $cont + 1 . '-' . count($detalle) }}</div>
                    <div class="warehouse">Rec: </div>
                </div>
                <div class="datos">
                    <div class="pkgs">Pkgs: 1 </div>
                    <div class="fob">
                        Fob:
                        {{ number_format(((isset($consolidado) and $consolidado != null) ? $value->declarado2 : $value->valor),2) }}

                    </div>
                    <?php
                      $contenido = ((isset($consolidado) and $consolidado != null) ? $value->contenido2 : $value->contenido);
                      $leng = strlen($contenido);
                    ?>
                    <div class="des">
                        @if(env('APP_CLIENT') != 'worldcargo')
                        Desc:
                        <span id="descripcion">
                            {{ (($leng > 215) ? str_replace(',', '-', substr($contenido, 0, 215)) : str_replace(',', ', ', $contenido)) }}
                        </span>
                        @endif
                        <span id="descripcion">
                            <br>
                            **- trackings ({{ str_replace(',', ', ', $value->tracking) }})
                        </span>
                    </div>
                    <div class="servicio">

                        <div class="iatacode">
                            {{ $value->prefijo }}/{{ $value->cons_pais_code }}
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="codebar2">
                @if(env('APP_CLIENT') === 'worldcargo')
                <img id="barcode" style="height: 50px;padding-top: 15px;"
                    src="data:image/png;base64, {{ DNS1D::getBarcodePNG($value->codigo, "C128",2,40) }}"
                    alt="barcode" />
                <div id="barcode-name">{{ $value->codigo }}</div>
                @else
                <img id="barcode" style="height: 50px;padding-top: 5px;"
                    src="data:image/png;base64, {{ DNS1D::getBarcodePNG($value->codigo, "C128",2,40) }}"
                    alt="barcode" />
                <div id="barcode-name">{{ $value->codigo }}</div>
                @endif
            </div>
        </td>
    </tr>
    <?php $cont++ ?>
</table>
@endforeach