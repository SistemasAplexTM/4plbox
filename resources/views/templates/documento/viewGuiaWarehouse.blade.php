@extends('layouts.app')
@section('title', 'Ver Documento')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ $documento->tipo_nombre }}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li >
                <a href="{{ route('documento.index') }}">{{ $documento->tipo_nombre }}</a>
            </li>
            <li class="active">
                <strong>ver {{ $documento->tipo_nombre }}</strong>
            </li>
        </ol>
    </div>
</div>
<style type="text/css">
    #apDiv5, #apDiv4, #apDiv7, #apDiv10, #apDiv11{
        border: 1px solid;
        padding: 5px;
    }
    #reciboWarehouse{
        background-color: #ffffff;
    }
    *{
        font-size: 12px;
    }
    .importante{
        color: #F00;
        font-size: 15px;
    }
    #apDiv13{
        padding-left: 10px;
    }
    #apDiv15, #apDiv18{
        font-size: 15px;
    }
    #apDiv12{
        padding-right: 40px;
        opacity: 0.5;
    }
    #apDiv13{
        opacity: 0.5;
    }
    #apDiv6{
        font-size: 18px;
        font-weight: bold;
    }
    #mvcIcon, #mvcMain{
        display: none;
    }
    #imprimir{
        padding-top: 80px;
        color: #0e9aef;
        cursor: pointer;
    }
    #barcode{
        width: 40%;
        height: 60%;
    }
    #infGuia{
        text-align: justify;
    }
    .tituloStatus{
        padding-top: 10px;
    }
</style>
@endsection

@section('content')
{{ print_r($documento) }}
    <div class="row" id="viewdocumento">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Visualizar Guia</h5>
                    <div class="ibox-tools">
                    </div>
                </div>
                <div class="ibox-content">
                    <fieldset>
                        <legend>
                            <div style="padding:2px 0 2px 0">
                                <a href="" class="btn btn-default btn-sm" target="_blank"><i class="fa fa-print"></i> Imprimir documento</a>
                                <a href="" class="btn btn-default btn-sm" target="_blank"><i class="fa fa-print"></i> Imprimir Label</a>
                                <a href="#" onclick="enviarMail()" class="btn btn-info btn-sm"><i class="fa fa-envelope"></i> Enviar Email</a>
                                <a data-toggle="modal" data-target="#modalAddStatus" id="traerWarehouses" class="btn btn-success btn-sm"><i class="fa fa-commenting"></i> Add. Status</a>
                            </div>
                        </legend>
                        <div class="row col-lg-12">
                            <div class="col-lg-12">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="reciboWarehouse">
                                    <tr>
                                        <!-- *******************************   PARTE IZQUIERDA  *********************-->
                                        <td style="width: 60%;padding-right: 10px;border-right: 1px dashed #666">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="infWarehouse">
                                                <tr>
                                                    <td align="left" valign="top">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td align="left">
                                                                    <span style="font-size:18px; font-weight:bold">{{ $documento->agencia }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left">
                                                                    <span style="font-size:14px; font-weight:bold">{{ $documento->agencia_dir }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left">
                                                                    <span style="font-weight:bold;">Telefono:</span>{{ $documento->agencia_tel }}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td align="right" valign="top">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td align="right">
                                                                    <div id="apDiv6" v-if="mostrar.includes(22)">WAREHOUSE N° {{ $documento->num_warehouse }}</div>    
                                                                    <div style="width: 50%;" v-if="mostrar.includes(23)">
                                                                        <img id="barcode" style="padding-top: 5px; height: 40px; width: 100%;" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($documento->num_guia, "C128",1,29,array(1,1,1)) }}" alt="barcode" />
                                                                        <div class="text-center" style="font-weight: 9;">{{ $documento->num_guia }}</div>
                                                                    </div>    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right">
                                                                    <span style="font-weight:bold;">Fecha:</span> {{ $documento->created_at }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right">
                                                                    <span style="font-weight:bold;">Usuario: </span>{{ $documento->usuario }}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div id="apDiv4">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr height="20px">
                                                                    <td width="20%"><b>Shipper:</b></td>
                                                                    <td colspan="3"> {{ $documento->ship_nomfull }}</td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>Dirección:</b></td>
                                                                    <td colspan="3"> {{ $documento->ship_dir }} </td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>Teléfono:</b></td>
                                                                    <td width="33%"> {{ $documento->ship_tel }} </td>
                                                                    <td width="19%"><b>Ciudad:</b> </td>
                                                                    <td width="28%"> {{ $documento->ship_ciudad }} </td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>Estado:</b></td>
                                                                    <td> {{ $documento->ship_depto }} </td>
                                                                    <td><b>Zip:</b></td>
                                                                    <td>{{ $documento->ship_zip }}</td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>Email:</b></td>
                                                                    <td colspan="3"> {{ $documento->ship_email }} </td>
                                                                </tr>
                                                            </table>
                                                        </div>   
                                                    </td>
                                                    <td>
                                                        <div id="apDiv5">
                                                            <!--Información del Consignatario-->
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr height="20px">
                                                                    <td width="20%"><b>Consignee:</b></td>
                                                                    <td colspan="3"> {{ $documento->cons_nomfull }} </td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>Dirección:</b></td>
                                                                    <td colspan="3"> {{ $documento->cons_dir }} </td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>Teléfono:</b></td>
                                                                    <td width="33%"> {{ $documento->cons_tel }} </td>
                                                                    <td width="19%"><b>Cédula:</b> </td>
                                                                    <td width="28%"> {{ $documento->cons_documento }} </td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>Ciudad:</b></td>
                                                                    <td width="33%"> {{ $documento->cons_ciudad }} </td>
                                                                    <td width="19%"><b>C.P:</b></td>
                                                                    <td width="28%"> {{ $documento->cons_zip }}</td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>Email:</b></td>
                                                                    <td colspan="3"> {{ $documento->cons_email }} </td>
                                                                </tr>
                                                            </table>
                                                        </div>  
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2">
                                                        <table v-if="mostrar.includes(22)">
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div style="padding:2px 0 0 0">
                                                                        <div id="apDiv7">
                                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td  height="30"><b>Factura:</b> &nbsp;{{ ($documento->factura) ? 'Si' :'No' }}</td>
                                                                                    <td ><b>Carga Peligrosa:</b> &nbsp;{{ ($documento->carga_peligrosa) ? 'Si' :'No' }}</td>
                                                                                    <td ><b>Re-Empacado:</b> &nbsp;{{ ($documento->re_empacado) ? 'Si' :'No' }}</td>
                                                                                    <td ><b>Mal Empacado:</b> &nbsp;{{ ($documento->mal_empacado) ? 'Si' :'No' }}</td>
                                                                                    <td><b>Rota:</b> &nbsp;{{ ($documento->rota) ? 'Si' :'No' }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td valign="top"><b>Observaciones:</b></td>
                                                                                    <td height="50" colspan="5" valign="top"><span style="padding:4px 0 0 0"> {{ ($documento->observaciones) ? $documento->observaciones : 'Ninguna' }} </span></td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </div>  

                                                                </td>
                                                            </tr>
                                                            <!--LIQUIDACION-->
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div id="apDiv8">
                                                                        <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" style="border-collapse:collapse;">
                                                                            <tr>
                                                                                <th width="2%" rowspan="2" bgcolor="lightgray" scope="col">#</th>
                                                                                <th width="20%" rowspan="2" bgcolor="lightgray" scope="col">WAREHOUSE</th>
                                                                                <th width="30%" rowspan="2" bgcolor="lightgray" scope="col">CONTENIDO</th>
                                                                                <th width="15%" rowspan="2" bgcolor="lightgray" scope="col">TRACKING</th>
                                                                                <th width="3%" rowspan="2" bgcolor="lightgray" scope="col">L</th>
                                                                                <th width="3%" rowspan="2" bgcolor="lightgray" scope="col">W</th>
                                                                                <th width="3%" rowspan="2" bgcolor="lightgray" scope="col">H</th>
                                                                                <th width="5%" rowspan="2" bgcolor="lightgray" scope="col">PESO LBS</th>
                                                                                <th colspan="2" bgcolor="lightgray" scope="col">PESO VOL</th>
                                                                                <th colspan="2" bgcolor="lightgray" scope="col">VOLUMEN</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="6%" bgcolor="lightgray" scope="col">LBS</th>
                                                                                <th width="6%" bgcolor="lightgray" scope="col">KLS</th>
                                                                                <th width="5%" bgcolor="lightgray" scope="col">CFT</th>
                                                                                <th width="5%" bgcolor="lightgray" scope="col">CMT</th>
                                                                            </tr>
                                                                            <?php 
                                                                            $item = 1; 
                                                                            $sumPie = 0; 
                                                                            $sumMetro = 0; 
                                                                            ?>
                                                                            @if(count($detalle))
                                                                                @foreach($detalle as $val)
                                                                                    <tr>
                                                                                        <td align='center' style="height: 50px;">{{ $item++ }}</td>
                                                                                        <td align='center'>
                                                                                            <img id="barcode" style="padding-top: 5px;padding-bottom: 5px; height: 40px; width: 130px;" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($val->num_warehouse, "C128",1,29,array(1,1,1)) }}" alt="barcode" />
                                                                                            <div>{{ $val->num_warehouse }}</div>
                                                                                        </td>
                                                                                        <td><div style="height: 60px;text-align: center;">{{ $val->contenido }}</div></td>
                                                                                        <td>{{ substr($val->contenido, 0, 19) }} {{ substr($val->contenido, 19) }}</td>
                                                                                        <td align='center'>{{ $val->largo }}</td>
                                                                                        <td align='center'>{{ $val->ancho }}</td>
                                                                                        <td align='center'>{{ $val->alto }}</td>
                                                                                        <?php $arr = preg_split("/ /", $val->dimensiones); ?>
                                                                                        <td align='center'>{{ $arr[0] }}</td>
                                                                                        <td align='center'>{{ $val->volumen }}</td>
                                                                                        <td align='center'>{{ number_format(($val->volumen / 2.204622), 2) }}</td>
                                                                                        <td align='center'>{{ $pie = number_format(($val->largo * $val->alto) / 1728, 2) }}</td>
                                                                                        <td align='center'>{{ $metro = number_format($pie/ 31.315, 2) }}</td>
                                                                                    </tr>
                                                                                    <?php
                                                                                    $sumPie = $sumPie + $pie;
                                                                                    $sumMetro = $sumMetro + $metro;
                                                                                    ?>
                                                                                @endforeach
                                                                            @endif
                                                                        </table>
                                                                    </div>    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div id="apDiv9">
                                                                        <table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#000000" style="border-collapse:collapse;">
                                                                            <tr>
                                                                                <th width="300px" rowspan="2" scope="col"><span style="font-size:20px; color:#666;height: 30px;">Recibe</span></th>
                                                                                <th width="" height="10" bgcolor="lightgray" scope="col" style="font-size: 10px;">PIEZAS</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">LIBRAS</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">VOL. LBS</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">KLS</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">VOL. KLS</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">VOL. CFT</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">VOL.CMT</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="10" align="center"><span style="font-size:15px;">{{ $documento->piezas }} </span></td>
                                                                                <td align="center"><span style="font-size:15px;">{{ $documento->peso }} </span></td>
                                                                                <td align="center"><span style="font-size:15px;">{{ $documento->volumen }} </span></td>
                                                                                <td align="center"><span style="font-size:15px;">{{ number_format(($documento->peso / 2.204622), 2) }} </span></td>
                                                                                <td align="center"><span style="font-size:15px;">{{ number_format(($documento->volumen / 2.204622), 2) }} </span></td>
                                                                                <td align="center"><span style="font-size:15px;">{{ $sumPie }} </span></td>
                                                                                <td align="center"><span style="font-size:15px;">{{ $sumMetro }} </span></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2" style="text-align: center;">
                                                                    <div id="apDiv15">
                                                                        <span class="importante">¡IMPORTANTE!</span>
                                                                        EL RECIBO SE COBRARÁ POR EL VALOR MAYOR, (PESO O VOLUMEN) PARA LOS ENVÍOS AÉREOS.
                                                                    </div>
                                                                    <div id="">&nbsp;</div>
                                                                    <div id="apDiv15">
                                                                        <span class="importante">
                                                                            NO NOS HACEMOS RESPONSABLES DE DAÑOS EN TELEVISORES QUE NO VIAJEN EN  CAJA
                                                                            DE MADERA.
                                                                        </span>
                                                                    </div>      
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2" style="text-align: center;">
                                                                    <div id="apDiv18">REVISAR LA MERCANCÍA ANTES DE RETIRARLA DE LA OFICINA EN COLOMBIA, LUEGO DE ESTO, NO SE ACEPTARÁ NINGÚN RECLAMO. ALL CARGO </div>    
                                                                </th>

                                                            </tr>
                                                            <tr>
                                                                <th colspan="2" style="text-align: center;">
                                                                    <div id="apDiv19">SHIPPER CERTIFIES THAT THE PARTICULARS ON THE FACE HERE OF ARE CORRECT AND THAT INSOFAR AS ANY PART OF THE CONSIGNMENT
                                                                        CONTAINS DANGEOROUS GOODS, SUCH PART IS PROPERLY DESCRIBED BY NAME AND IS IN PROPER CONDITION FOR CARRIAGE BY AIR
                                                                        ACCORDING TO THE APPLICABLE DANGEROUS GOODS REGULATIONS. SHIPPER HEREBY CONSENTS TO A SEARCH OR INSPECTION OF THE
                                                                        CARGO</div>    
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%"></td>
                                                                <td style="text-align: right;padding-top: 10px;">__________________________</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%"></td>
                                                                <td style="text-align: right; font-weight: bold;">Received: </td>
                                                            </tr>
                                                        </table>

                                                       {{--  DATOS DE LA GUIA --}}
                                                       <table v-if="mostrar.includes(23)">
                                                           <tr>
                                                                <td colspan="2">
                                                                    <div id="apDiv4">
                                                                        <!--Información del Consignatario-->
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr height="20px">
                                                                                <td width="20%" colspan="2" style="background-color: lightgray;"><b style="font-weight: bold;font-size: 15px;"><i class="fa fa-money"></i> Liquidación</b></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Valor: (Flete+Impuesto)</b></td>
                                                                                <td align="right">  echo number_format(($objGuia->declarado_total * 28 / 100) + $objGuia->flete, 2)  USD </td>
                                                                            </tr>                                   

                                                                            <tr>
                                                                                <td><b>Seguro: </b></td>
                                                                                $seguro = $objGuia->seguro * $seg / 100;
                                                                                <td align="right"> echo number_format($seguro, 2)  USD</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Descuento:</b></td>
                                                                                <td align="right">number_format($objGuia->desto, 2) USD</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Otros:</b></td>
                                                                                <td align="right">echo number_format($objGuia->cargos_add, 2) USD</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Sub Total:</b></td>
                                                                                <td align="right"> echo number_format(($objGuia->declarado_total * 28 / 100) + $objGuia->flete + $seguro - $objGuia->desto, 2)  USD</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div >
                                                                        <div id="apDiv11">
                                                                            <table width="100%" border="0" cellspacing="1" cellpadding="0">

                                                                                <tr>
                                                                                    <td><b>Total:</b></td>
                                                                                    <td align="right"><b><span style="font-size:14px;color:#F00">echo number_format($objGuia->total, 2)  USD</span></b>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div id="apDiv4">
                                                                        <!--Información del Consignatario-->
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr height="20px">
                                                                                <td width="20%" style="background-color: lightgray;"><b>Warehouses</b></td>
                                                                                <td width="20%" style="background-color: lightgray;"><b>Contenido</b></td>
                                                                                <td width="20%" style="background-color: lightgray;"><b>Peso (lb)</b></td>
                                                                            </tr>
                                                                            {{-- if ($warehouses != '') --}}
                                                                                {{-- foreach ($warehouses as $wrh): --}}
                                                                                    <tr>
                                                                                        <td style="width: 15%;border-top: 1px solid #666;">
                                                                                            echo $wrh->num_warehouse
                                                                                        </td>
                                                                                        <td style="width: 70%;border-top: 1px solid #666;">
                                                                                            echo $wrh->contenido
                                                                                        </td>
                                                                                        <td style="width: 15%;border-top: 1px solid #666;">
                                                                                            echo $wrh->peso
                                                                                        </td>
                                                                                    </tr>
                                                                                {{-- endforeach --}}
                                                                            {{-- endif --}}
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                       </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <!-- *******************************   PARTE DERECHA  *********************-->
                                        <td valign="top" style="padding-left: 10px;">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="infWarehouse">
                                                <tr>
                                                    <td align="left" valign="top" colspan="2">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td align="left">
                                                                    <span style="font-size:18px; font-weight:bold">Status</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td align="right" valign="top" colspan="2">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td align="right" >
                                                                    {{-- <strong style="font-size:18px;">Consolidado </strong>  --}} 
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <div id="apDiv4">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalleGuias">
                                                                <thead>
                                                                    <tr height="20px">
                                                                        <td><b>Fecha:</b></td>
                                                                        <td><b>Estado:</b></td>
                                                                        <td><b>Observación:</b></td>
                                                                        <td><b>Usuario:</b></td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if(isset($objStatus) and $objStatus)
                                                                        @foreach($objStatus as $sta)
                                                                            <tr>
                                                                                <td style="width: 15%;border-top: 1px solid #666;">{{ $sta->fecha_status }}</td>
                                                                                <td style="width: 15%;border-top: 1px solid #666;">{{ $sta->status }}</td>
                                                                                <td style="width: 45%;border-top: 1px solid #666;">{{ $sta->observacion }}</td>
                                                                                <td style="width: 15%;border-top: 1px solid #666;">{{ $sta->usuario }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>   
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </fieldset>
                    <div class="text-right">
                        <a href="" class="btn btn-default btn-sm"><i class="fa fa-table fa-fw"></i> Volver</a>
                        <a href="" class="btn btn-primary btn-sm"><i class="fa fa-edit fa-fw"></i> Editar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/documento/view.js') }}"></script>
@endsection