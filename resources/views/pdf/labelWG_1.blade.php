<style>
    *{
        font-size: 15px;
        font-family: sans-serif;
        font-weight: bold;
    }
    .fecha{
        text-align: right;
    }
    .agencia{
        font-size: 25px
    }
    .datos_agencia{
        padding-left: 5px;
        font-size: 13px;
    }
    .space{
        background-color: black;
        font-size: 5px;
    }
    .tb_ship_cons{
        margin-top: 10px;
    }
    .title{
        margin-bottom: 10px;
        width: 90%;
        border-bottom: 1px solid #000;
    }
    .tb_ship_cons tr td{
        font-size: 10px;
    }
    .ciudad_destino{
        font-size: 20px;
        text-align: center;
        padding: 5px;
    }
    .peso{
        font-size: 35px;
        padding-left: 5px;
    }
    .paginacion{
        font-size: 35px;
        text-align: center;
    }
    .des{
        padding: 5px;
        margin: 0 auto;
    }
    #descripcion{
        font-size: 12px;
    }
    .iatacode{
        font-size: 45px;
    font-weight: bold;
    text-align: right;
    padding-right: 5px;
    }
    #barcode-name{
        text-align: center;
            font-size: 30px;
        }
        .title_consignee{
            padding-top: 10px;
            padding-bottom: 10px;
            font-size: 20px;
        }
</style>
<?php 
    $cont = 0;
    $toalRegistros = count($detalle);
    $contRegistros = 0;
?>
@foreach ($detalle as $value)
<?php $contRegistros++ ?>
<table border="0" cellpadding="0" cellspacing="0" id="reciboWarehouse" style="page-break-after:{{ ($contRegistros === $toalRegistros) ? 'avoid' : 'always' }}" width="100%">
    <tr>
        <td>
            <div class="fecha">
                {{ $value->created_at->toFormattedDateString() }}
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="agencia">
                {{ $documento->agencia }}
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="datos_agencia">
                {{ $documento->agencia_dir }}
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="datos_agencia">
                {{ $documento->agencia_tel }}
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="datos_agencia" style="margin-bottom: 10px;">
                {{ $documento->agencia_email }}
            </div>
        </td>
    </tr>
    <tr>
        <td class="space">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" class="tb_ship_cons" width="100%">
                <tr>
                    <th style="width: 50%;">
                        <div class="title">
                           @lang('general.shipper')
                        </div>
                    </th>
                    <th>
                        <div class="title">
                           @lang('general.consignee')
                        </div>
                    </th>
                </tr>
                <tr>
                    <td>
                        {{ $value->ship_nomfull }}
                    </td>
                    <td style="font-size: 20px;">
                        {{ $value->cons_ciudad }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $value->ship_dir }}
                    </td>
                    <td>
                        {{-- {{ $value->cons_dir }} --}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $value->ship_tel }}
                    </td>
                    <td>
                        {{-- {{ $value->cons_tel }} --}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $value->ship_email }}
                    </td>
                    <td>
                        {{-- {{ $value->cons_email }} --}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="title_consignee">{{ $value->cons_nomfull }}</td>
    </tr>
    <tr>
        <td>
            <table border="1" cellpadding="0" cellspacing="0" class="tb_datos_carga" width="100%">
                <tr>
                    {{-- <td style="width: 25%;" rowspan="2">
                        
                        
                    </td> --}}
                    <td style="height: 150px;">
                        <div class="des">
                            Desc:
                            <span id="descripcion">
                                {{ $value->contenido }}
                                <br>
                                    **- trackings ({{ str_replace(',', ', ', $value->tracking) }})
                                </br>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>
                        <table cellpadding="0" cellspacing="0" style="width: 100%;">
                            <tr>
                                <td style="width: 60%;border-right: 1px solid #000;">
                                    <div class="peso">
                                        {{ $value->peso }} Lbs
                                    </div>
                                </td>
                                <td>
                                    <div class="paginacion">
                                        {{ $cont + 1 . '-' . count($detalle) }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </th>
                </tr>
                <tr>
                    <th>
                        <div class="iatacode">
                            {{ $value->prefijo }} / {{ $value->cons_pais_code }}
                        </div>
                    </th>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <div class="codebar2" style="text-align: center;">
                <img id="barcode" style="height: 50px;padding-top: 25px;" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($value->codigo, "C128",2,40) }}" alt="barcode" />
                <div id="barcode-name">{{ $value->codigo }}</div>
            </div>
        </td>
    </tr>
    <?php $cont++ ?>
</table>
@endforeach
