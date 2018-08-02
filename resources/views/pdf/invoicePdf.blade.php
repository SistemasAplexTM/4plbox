<html>
    <style>
        *{
            font-weight: bold;
            font-size: 13px;
            font-family: sans-serif;
        }
        #mvcIcon, #mvcMain{
            display: none;
        }
        .content{
            background-color: #cfcfcf;
        }
        #head, #invoice{
            font-size: 20px;
            background-color: #cfcfcf;
        }
        #invoice{
            text-align: right;
            width: 100%;
        }
        #cont1{
            padding-top: 5px;
            padding-bottom: 0px;
        }
        #numInvoice, #fechaInvoice{
            text-align: right;
        }
        #ship, #cons{
            font-size: 15px;
            font-weight: bold;
            padding-bottom: 5px;
        }
        #titulo{
            font-weight: bold;
        }
        #total{
            text-align: right;
            margin-top: 10px;
        }
        #detalle{
            margin-top: 10px;
            margin-bottom: 10px;
            height: 55px;;
        }
        #totalv{
            font-size: 20px;
        }
        #declaro{
            font-size: 8px;
            padding-top: 5px;
        }
        #declaro1{
            font-size: 8px;
            padding-top: 5px;
            padding-bottom: 5px;
        }
        #declaro2{
            font-size: 8px;
            padding-bottom: 5px;
        }
        #firma{
            padding-top: 10px;
        }
        #space{
            margin-bottom: 3px;
            margin-top: 12px;
        }
        #space2{
            margin-top: 10px;
            margin-bottom: 7px;
            border: 1px dashed #000;
            font-size: 2px;
        }
    </style>
    <?php
    $toalRegistros = count($detalle);
    $contRegistros = 0;
    $cont=0;
    ?>
    <body style="margin-top: -25px;">
    	@foreach($detalle as $val)

    		<?php $contRegistros++ ?>
            <?php for ($i = 0; $i <= 1; $i++): ?>
                <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="tableContainer" <?php if ($i === 1): ?> style="page-break-after:<?php if ($contRegistros === $toalRegistros): ?>avoid;<?php else: ?>always<?php endif; ?>" <?php endif; ?>>
                    <tr>
                        <td colspan="4" align="CENTER"><div id="space">(Copy {{ $i + 1 }})</div></td>
                    </tr>
                    <tr>
                        <td>
                            <div id="head">{{ (isset($documento->agencia)) ? $documento->agencia : '' }}</div>
                        </td>
                        <td>
                            <div id="invoice">COMERCIAL INVOICE</div>
                        </td>
                    </tr>
                    <tr>
                        <td align="CENTER">
                            <div id="cont1">
                                <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="table1">
                                    <tr>
                                        <td>Direccion: {{ (isset($documento->agencia_dir)) ? $documento->agencia_dir : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Telefono: {{ (isset($documento->agencia_tel)) ? $documento->agencia_tel : '' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td align="CENTER">
                            <div id="cont1">
                                <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="table1">
                                    <tr>
                                        <td><div id="numInvoice">Invoice N°: {{ (isset($detalle[$cont]->num_guia)) ? $detalle[$cont]->num_guia : '' }}</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="fechaInvoice">Fecha: {{ (isset($documento->created_at)) ? $documento->created_at : '' }}</div></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="CENTER">
                            <div id="cont1">
                                <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="table1">
                                    <tr>
                                        <td><div id="ship">Shipper - (Remitente)</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="nomS"><span id="titulo">Nombre:</span> {{ (isset($detalle[$cont]->ship_nomfull)) ? $detalle[$cont]->ship_nomfull : '' }}</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="dirS"><span id="titulo">Direccion:</span> {{ (isset($detalle[$cont]->ship_dir)) ? $detalle[$cont]->ship_dir : '' }}</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="ciuS"><span id="titulo">Pais/Ciudad:</span> {{ (isset($detalle[$cont]->ship_ciudad)) ? $detalle[$cont]->ship_ciudad: '' }}</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="telS"><span id="titulo">Telefono:</span> {{ (isset($detalle[$cont]->ship_tel)) ? $detalle[$cont]->ship_tel : '' }}</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="telS">{{ (isset($detalle[$cont]->ship_email)) ? $detalle[$cont]->ship_email : '' }}</div></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td align="CENTER">
                            <div id="cont1">
                                <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="table1">
                                    <tr>
                                        <td><div id="cons">Consignee - (Destinatario)</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="nomC"><span id="titulo">Nombre:</span> {{ (isset($detalle[$cont]->cons_nomfull)) ? $detalle[$cont]->cons_nomfull : '' }}</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="dirC"><span id="titulo">Direccion:</span> {{ (isset($detalle[$cont]->cons_dir)) ? $detalle[$cont]->cons_dir : '' }}</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="ciuC"><span id="titulo">Pais/Ciudad:</span> {{ (isset($detalle[$cont]->cons_ciudad)) ? $detalle[$cont]->cons_ciudad : '' }}</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="telC"><span id="titulo">Telefono:</span> {{ (isset($detalle[$cont]->cons_tel)) ? $detalle[$cont]->cons_tel : '' }}</div></td>
                                    </tr>
                                    <tr>
                                        <td><div id="telC"><spam style="font-size: 12px;">{{ (isset($detalle[$cont]->cons_email)) ? $detalle[$cont]->cons_email : '' }}</spam></div></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="CENTER" colspan="2">
                            <div>
                                <table width="100%;" border="1" cellspacing="0" cellpadding="0" id="table1">
                                    <tr>
                                        <th style="width: 5%;">CANT.</th>
                                        <th style="width: 68%;">DESCRIPCION</th>
                                        <th style="width: 12%;">VALOR (USD)</th>
                                        <th style="width: 15%;">SUBTOTAL (USD)</th>
                                    </tr>
                                    <?php $total = 0; ?>
									<tr>
                                        <td align="CENTER"><div id="detalle">{{ 1 }}</div></td>
                                        <?php $leng = strlen($detalle[$cont]->contenido2); ?>
                                        <td align="CENTER">
                                        	<div id="detalle" style="font-size: 12px; height: 45px;">
                                        		{{ ($leng > 215) ? str_replace(',', ', ', substr($detalle[$cont]->contenido2, 0, 215)) : str_replace(',', ', ', $detalle[$cont]->contenido2) }}
                                        	</div>
                                        </td>
                                        <td align="CENTER"><div id="detalle">{{ '$' . number_format($detalle[$cont]->declarado2, 2) }}</div></td>
                                        <td align="CENTER"><div id="detalle">{{ '$' . number_format($detalle[$cont]->declarado2, 2) }}</div></td>
                                    </tr>
                                    <?php $total = $total +$detalle[$cont]->declarado2; ?>
                                    

                                    <tr>
                                        <th colspan="3"><div id="total">TOTAL</div></th>
                                        <td align="CENTER"><div id="totalv"  style="{{ ($total == 0) ? 'background-color: black;color: #fff' : ''}}">{{ '$' . number_format($total, 2) }}</div></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <div id="declaro">NOTE: THIS DECLARATION OF COMMERCIAL VALUE REPLACES THE ORIGINAL INVOICE.</div>
                                            <div id="declaro1">
                                                DECLARO QUE LO EXPUESTO EN ESTE DOCUMENTO ES VERDADERO Y QUE NO ESTOY ENVIANDO DINERO, EXPLOSIVOS, DROGAS, ARMAS, QUIMICOS PELIGROSOS, JOYAS NI MERCANCIA CON FINES COMERCIALES. DE IGUAL FORMA DECLARO QUE NO ENVIO MAS DE 6 ARTICULOS DE LA MISMA CLASE Y QUE EL CONTENIDO DEL ENVIO ESTA DECLARADO EN SU TOTALIDAD, SEGUN EL ART. 2 Y 3 DE LA RESOLUCION 994 DEL 04/02/2011 EN CONCORDANCIA CON EL ART. 123 Y SIGUIENTES DEL DECRETO 2685/99.
                                            </div>
                                            <div id="declaro2">
                                                THIS INVOICE IS ONLY FOR CUSTOMS USE BETWEEN UNITED STATES AND COUNTRY OF DESTINATION. THIS INVOICE IS NOT FOR TRADE. THE SHIPPER DECLARES ALL THE INFORMATION IS TRUE AND CORRECT. THESE COMMODITIES, TECHNOLOGY OR SOFTWARE WERE EXPORTED FROM THE UNITED STATES IN ACCORDANCE WITH THE EXPORT ADMINISTRATION REGULATIONS. DIVERSION CONTRARY TO U.S. LAW IS PROHIBITED. 
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div id="firma">
                                    <table width="100%;" border="0" cellspacing="0" cellpadding="0" id="table1">
                                        <tr>
                                            <th>__________________________</th>
                                            <th>__________________________</th>
                                            <th>__________________________</th>
                                        </tr>
                                        <tr>
                                            <th>SIGNATURE / FIRMA</th>
                                            <th>No. ID/No. IDENTIFICACION</th>
                                            <th>DATE/FECHA</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php if ($i === 0): ?>  
                        <tr>
                            <td colspan="4" align="CENTER"><div id="space2">&nbsp;</div></td>
                        </tr>
                    <?php endif; ?> 
                </table>
            <?php endfor; ?>
            <?php $cont++; ?>
        @endforeach
    </body>
   {{--  <script  type="text/javascript">
        function printHTML() {
            if (window.print) {
                window.print();
            }
        }

        document.addEventListener("DOMContentLoaded", function (event) {
            printHTML();
        });
    </script> --}}
</html>