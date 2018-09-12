@extends('layouts.app')
@section('title', 'Ver Documento')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ $documento->tipo_nombre }}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">@lang('documents.home')</a>
            </li>
            <li >
                <a href="{{ route('documento.index') }}">{{ $documento->tipo_nombre }}</a>
            </li>
            <li class="active">
                <strong>@lang('documents.see'){{ $documento->tipo_nombre }}</strong>
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
                    <h5>@lang('documents.visualize_guide')</h5>
                    <div class="ibox-tools">
                    </div>
                </div>
                <div class="ibox-content">
                    <fieldset>
                        <legend>
                            <div style="padding:2px 0 2px 0">
                                <a href="" class="btn btn-default btn-sm" target="_blank"><i class="fa fa-print"></i>
                                @lang('documents.print_document')</a>
                                <a href="" class="btn btn-default btn-sm" target="_blank"><i class="fa fa-print"></i> 
                                @lang('documents.print_label')</a>
                                <a href="#" onclick="enviarMail()" class="btn btn-info btn-sm"><i class="fa fa-envelope"></i> @lang('documents.send_email')</a>
                                <a data-toggle="modal" data-target="#modalAddStatus" id="traerWarehouses" class="btn btn-success btn-sm"><i class="fa fa-commenting"></i> @lang('documents.add_status')</a>
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
                                                                    <span style="font-weight:bold;">@lang('documents.phone'):</span>{{ $documento->agencia_tel }}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td align="right" valign="top">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td align="right">
                                                                    <div id="apDiv6" v-if="mostrar.includes(22)">@lang('documents.warehouse') N° {{ $documento->num_warehouse }}</div>    
                                                                    <div style="width: 50%;" v-if="mostrar.includes(23)">
                                                                        <img id="barcode" style="padding-top: 5px; height: 40px; width: 100%;" src="data:image/png;base64, {{ DNS1D::getBarcodePNG($documento->num_guia, "C128",1,29,array(1,1,1)) }}" alt="barcode" />
                                                                        <div class="text-center" style="font-weight: 9;">{{ $documento->num_guia }}</div>
                                                                    </div>    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right">
                                                                    <span style="font-weight:bold;">@lang('documents.date'):</span> {{ $documento->created_at }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right">
                                                                    <span style="font-weight:bold;">@lang('documents.user')'': </span>{{ $documento->usuario }}
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
                                                                    <td width="20%"><b>@lang('documents.shipper'):</b></td>
                                                                    <td colspan="3"> {{ $documento->ship_nomfull }}</td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>@lang('documents.address'):</b></td>
                                                                    <td colspan="3"> {{ $documento->ship_dir }} </td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>@lang('documents.phone'):</b></td>
                                                                    <td width="33%"> {{ $documento->ship_tel }} </td>
                                                                    <td width="19%"><b>Ciudad:</b> </td>
                                                                    <td width="28%"> {{ $documento->ship_ciudad }} </td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>@lang('documents.state'):</b></td>
                                                                    <td> {{ $documento->ship_depto }} </td>
                                                                    <td><b>@lang('documents.zip'):</b></td>
                                                                    <td>{{ $documento->ship_zip }}</td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>@lang('documents.email'):</b></td>
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
                                                                    <td width="20%"><b>@lang('documents.consignee'):</b></td>
                                                                    <td colspan="3"> {{ $documento->cons_nomfull }} </td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>@lang('documents.address'):</b></td>
                                                                    <td colspan="3"> {{ $documento->cons_dir }} </td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>@lang('documents.phone'):</b></td>
                                                                    <td width="33%"> {{ $documento->cons_tel }} </td>
                                                                    <td width="19%"><b>@lang('documents.identification_card'):</b> </td>
                                                                    <td width="28%"> {{ $documento->cons_documento }} </td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>@lang('documents.city'):</b></td>
                                                                    <td width="33%"> {{ $documento->cons_ciudad }} </td>
                                                                    <td width="19%"><b>C.P:</b></td>
                                                                    <td width="28%"> {{ $documento->cons_zip }}</td>
                                                                </tr>
                                                                <tr height="20px">
                                                                    <td><b>@lang('documents.email'):</b></td>
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
                                                                                    <td  height="30"><b>@lang('documents.bill')</b> &nbsp;{{ ($documento->factura) ? 'Si' :'No' }}</td>
                                                                                    <td ><b>
                                                                                    @lang('documents.dangerous_load'):</b> &nbsp;{{ ($documento->carga_peligrosa) ? 'Si' :'No' }}</td>
                                                                                    <td ><b>@lang('documents.packed')</b> &nbsp;{{ ($documento->re_empacado) ? 'Si' :'No' }}</td>
                                                                                    <td ><b>
                                                                                    @lang('documents.badly_packed'):</b> &nbsp;{{ ($documento->mal_empacado) ? 'Si' :'No' }}</td>
                                                                                    <td><b>@lang('documents.broken'):</b> &nbsp;{{ ($documento->rota) ? 'Si' :'No' }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td valign="top"><b>@lang('documents.observations'):</b></td>
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
                                                                                <th width="20%" rowspan="2" bgcolor="lightgray" scope="col">@lang('documents.warehouse')</th>
                                                                                <th width="30%" rowspan="2" bgcolor="lightgray" scope="col">@lang('documents.content')</th>
                                                                                <th width="15%" rowspan="2" bgcolor="lightgray" scope="col">@lang('documents.tracking')</th>
                                                                                <th width="3%" rowspan="2" bgcolor="lightgray" scope="col">L</th>
                                                                                <th width="3%" rowspan="2" bgcolor="lightgray" scope="col">W</th>
                                                                                <th width="3%" rowspan="2" bgcolor="lightgray" scope="col">H</th>
                                                                                <th width="5%" rowspan="2" bgcolor="lightgray" scope="col">@lang('documents.weight') LBS</th>
                                                                                <th colspan="2" bgcolor="lightgray" scope="col">@lang('documents.weight') VOL</th>
                                                                                <th colspan="2" bgcolor="lightgray" scope="col">@lang('documents.volume')</th>
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
                                                                                <th width="" height="10" bgcolor="lightgray" scope="col" style="font-size: 10px;">@lang('documents.pieces')</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">@lang('documents.pounds')</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">Vol. LBS</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">Kls</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">Vol. KLS</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">Vol. CFT</th>
                                                                                <th width="" bgcolor="lightgray" scope="col" style="font-size: 10px;">Vol. CMT</th>
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
                                                                        <span class="importante">@lang('documents.important')</span>
                                                                   @lang('documents.message_receipt')
                                                                    </div>
                                                                    <div id="">&nbsp;</div>
                                                                    <div id="apDiv15">
                                                                        <span class="importante">
                                                                         @lang('documents.we_are_not_responsible')
                                                                        </span>
                                                                    </div>      
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2" style="text-align: center;">
                                                                    <div id="apDiv18">@lang('documents.check_the_ merchandise')</div>    
                                                                </th>

                                                            </tr>
                                                            <tr>
                                                                <th colspan="2" style="text-align: center;">
                                                                    <div id="apDiv19">@lang('documents.shipper_certifies')</div>    
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%"></td>
                                                                <td style="text-align: right;padding-top: 10px;">__________________________</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%"></td>
                                                                <td style="text-align: right; font-weight: bold;">@lang('documents.received'): </td>
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
                                                                                <td width="20%" colspan="2" style="background-color: lightgray;"><b style="font-weight: bold;font-size: 15px;"><i class="fa fa-money"></i> @lang('documents.settlement')</b></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>@lang('documents.value'): (Flete+Impuesto)</b></td>
                                                                                <td align="right">  echo number_format(($objGuia->declarado_total * 28 / 100) + $objGuia->flete, 2)  USD </td>
                                                                            </tr>                                   

                                                                            <tr>

                                                                                <td><b>@lang('documents.insurance'): </b></td>
                                                                                $seguro = $objGuia->seguro * $seg / 100;
                                                                                <td align="right"> echo number_format($seguro, 2)  USD</td>
                                                                            </tr>
                                                                            <tr>

                                                                                <td><b>@lang('documents.discount'):</b></td>
                                                                                <td align="right">number_format($objGuia->desto, 2) USD</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>@lang('documents.others'):</b></td>
                                                                                <td align="right">echo number_format($objGuia->cargos_add, 2) USD</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Sub @lang('documents.total')'':</b></td>
                                                                                <td align="right"> echo number_format(($objGuia->declarado_total * 28 / 100) + $objGuia->flete + $seguro - $objGuia->desto, 2)  USD</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div >
                                                                        <div id="apDiv11">
                                                                            <table width="100%" border="0" cellspacing="1" cellpadding="0">

                                                                                <tr>
                                                                                    <td><b>@lang('documents.total'):</b></td>
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
                                                                                <td width="20%" style="background-color: lightgray;"><b>@lang('documents.warehouse')</b></td>
                                                                                <td width="20%" style="background-color: lightgray;"><b>@lang('documents.content')</b></td>
                                                                                <td width="20%" style="background-color: lightgray;"><b>@lang('documents.weight')(lb)</b></td>
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
                                                                    <span style="font-size:18px; font-weight:bold">@lang('documents.states')</span>
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
                                                                        <td><b>@lang('documents.date'):</b></td>
                                                                        <td><b>@lang('documents.state'):</b></td>
                                                                        <td><b>@lang('documents.observations'):</b></td>
                                                                        <td><b>@lang('documents.user'):</b></td>
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
                        <a href="" class="btn btn-default btn-sm"><i class="fa fa-table fa-fw"></i> @lang('documents.return')</a>
                        <a href="" class="btn btn-primary btn-sm"><i class="fa fa-edit fa-fw"></i> @lang('documents.edit')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/documento/view.js') }}"></script>
@endsection