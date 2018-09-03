<body>
    <?php
        $colorLetras = '#000';
        $colorMaster = '#000';
        $colorFondo = '#CCCCCC';
        $destino = 'AIRPORT OF DESTINATION';
    ?>
        <style>
            *{
                color: #000;
            }
        </style>
    <?php
        $destino1 = 'ORIGINAL COPY 1 (AIRPORT OF DESTINATION)';
        $destino2 = 'ORIGINAL COPY 2 (FOR CONSIGNEE)';
        $destino3 = 'ORIGINAL COPY 3 (FOR ISSUING CARRIER)';
        $destino4 = 'ORIGINAL COPY 4 (FOR SHIPPER)';
        $destino5 = 'ORIGINAL COPY 5 (DELIVERY RECEIPT)';
        $destino6 = 'EXTRA COPY';
        $sindestino = '';
    ?>

    <style>
        #mvcIcon, #mvcMain{
            display: none;
        }
        #tableContainerContrato{
            width: 100%;
            /*margin-left: -15px;*/
        }
        .content{
            background-color: #cfcfcf;
        }
        #iataLog{
            width: 60px;
            margin-bottom: 10px;        
        }
        #titulo{
            margin-bottom: 8px;  
        }
        #titulo2{
            margin-top: 12px;
            margin-bottom: 5px;  
        }
        #info1{
            text-align: justify;
            font-size: 9px;
        }
        #contenido{
            text-align: justify;
            margin-top: 10px;
            font-size: 9px;
        }
        *{
            font-size: 10px;
            font-family: sans-serif;
        }

        #tableContainer{
            font-size: 11px;
            width: 100%;
        }
        .cont1,.cont2{
            padding-right: 2px;
            padding-left: 2px;
            font-size: 20px;
            font-weight: bold;
            /*width: 50px;*/
        }
        .cont3{
            font-size: 20px;
            font-weight: bold; 
        }
        .cont4{
            font-size: 20px;
            text-align: right;
            font-weight: bold;
        }
        .fila1,.fila2,.fila3,.fila4,.fila5,.fila6,.fila7,.fila8,.fila9,.fila10,.fila11,.fila12{
            border:  1px solid #000;
        }
        .shipperNumber,.consigneeNumber{
            text-align: center;
        }
        .airWaibil{
            font-weight: bold;
            font-size: 20px;
            height: 20px;
        }
        .contAir{
            font-weight: bold;
            font-size: 20px;
            width: 99%;
            text-align: center;
            height: 20px;
        }
        #datosShipper, #datosConsignee, #datosIssuing{
            font-weight: bold;
        }
        #datosIssuing{
            font-size: 11px;
        }
        .info1{
            font-size: 9px;
        }
        .info2{
            font-size: 7px;
        }
        .titleIata,.titleNumA{
            font-weight: bold;
        }
        .contentAcI{
            width: 99%;
            font-size: 13px;
            height: 70px;
        }
        .titleRef,.titleopInf,.titleopInf2{
            text-align: center;
            font-size: 8px;
            height: 14px;
        }

        .titleTo1,.contentTo1,.titleTo2,.contentTo2,.titleTo3,.contentTo3,
        .titleBy1,.contentBy1,.titleBy2,.contentBy3{
            margin-left: 5px;
        }
        .contenttbl5{
            height: 16px;
            margin-left: 5px;
            font-weight: bold;
            padding-top: 2px;
        }
        .titletbl5{
            font-size: 7px;
            text-align: center;
            height: 10px;
        }
        .wtCont{
            height: 12px;
            margin-top: -10px;
        }
        .ppd{
            width: 50%;
            height: 10px;
        }
        .coll{
            margin-top: -10px;
            margin-left: 35px;
            width: 50%; 
            height: 10px;
        }
        .titleCu,.titleChgs,.contentCu,.contentChgs,#wtspanpp,#valpp,#wtspancol,#valcol{
            text-align: center;
        }
        #wtspancol,#wtspanpp{
            font-size: 8px;
        }
        #valpp{
            border-right: 1px solid #000;
            width: 100%;
        }
        #valcol{
            width: 100%;
        }
        .content5{
            font-weight: bold;
            text-align: center;
        }
        .space{
            background-color: #b4b6b8;
            width: 1%;
            height: 1px;
        }
        .title{
            font-size: 10px;
            text-align: center;
        }
        .valueDetalle{
            font-weight: bold;
            text-align: center;
        }
        .wCharge{
            text-align: center;
        }
        .valueTotal{
            font-weight: bold;
            font-size: 13px;
            text-align: center;
        }


        .line2OP {
            width: 20px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(98px)
                rotate(-50deg); 
            position: absolute;
        }
        .line1OP {
            width: 20px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(3px)
                rotate(50deg); 
            position: absolute;
        }

        .lineWe2 {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-48px)
                translateX(107px)
                rotate(-50deg); 
            position: absolute;
        }
        .lineWe {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-48px)
                translateX(10px)
                rotate(50deg); 
            position: absolute;
        }
        .linePre2 {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-48px)
                translateX(47px)
                rotate(-50deg); 
            position: absolute;
        }
        .linePre {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-48px)
                translateX(25px)
                rotate(50deg); 
            position: absolute;
        }
        .lineCol2 {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-48px)
                translateX(38px)
                rotate(-50deg); 
            position: absolute;
        }
        .lineCol {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(25px)
                rotate(50deg); 
            position: absolute;
        }
        .lineVal2 {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(163px)
                rotate(-50deg); 
            position: absolute;
        }
        .lineVal {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(66px)
                rotate(50deg); 
            position: absolute;
        }
        .lineTax2 {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(61px)
                rotate(-50deg); 
            position: absolute;
        }
        .lineTax {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(7px)
                rotate(50deg); 
            position: absolute;
        }

        .lineTo2 {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(188px)
                rotate(-50deg); 
            position: absolute;
        }
        .lineTo {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(43px)
                rotate(50deg); 
            position: absolute;
        }
        .lineTpre2 {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(70px)
                rotate(-50deg); 
            position: absolute;
        }
        .lineTpre {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(28px)
                rotate(50deg); 
            position: absolute;
        }
        .lineCurr2 {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(90px)
                rotate(-50deg); 
            position: absolute;
        }
        .lineCurr {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(18px)
                rotate(50deg); 
            position: absolute;
        }
        .lineChar2 {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(91px)
                rotate(-50deg); 
            position: absolute;
        }
        .lineChar {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(18px)
                rotate(50deg); 
            position: absolute;
        }
        .lineTChar2 {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(98px)
                rotate(-50deg); 
            position: absolute;
        }
        .lineTChar {
            width: 17px;
            height: 52px;
            -webkit-transform:
                translateY(-50px)
                translateX(18px)
                rotate(50deg); 
            position: absolute;
        }
        .titleShipper,#datosShipper,.margen{
            margin-left: 5px;
        }
    </style>
    @for($i = 1; $i <= 8; $i++)
        <table border="0" cellspacing="0" cellpadding="0" id="tableContainer" style='page-break-after:always;'>
            <tr>
                <td style="padding-bottom: 2px;width: 50%;">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <div class="cont1" style="border-right: 1px solid {{ $colorMaster }};color: {{  $colorLetras }};">
                                    {{ $data->codigo_aerolinea }}
                                </div>
                            </td>
                            <td>
                                <div class="cont2" style="margin-left: 5px;border-right: 1px solid {{  $colorMaster }};color: {{  $colorLetras }};">
                                    {{ $data->prefijo }}
                                </div>
                            </td>
                            <td>
                                <div class="cont3" style="margin-left: 5px;color: {{  $colorLetras }};">
                                    {{ $data->num_master }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <div class="cont4" style="color: {{  $colorLetras }};">
                        
                    </div>
                </td>
            </tr>
            <!--FILA 1 *******************************************************-->
            <tr>
                <td class="fila1" style="width: 50%;">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="">
                                <div class="titleShipper">
                                   @lang('master.shippers_name_and_address')
                                </div>
                            </td>
                            <td>
                                <div class="shipperNumber" style="width: 100%;border-bottom: 1px solid {{  $colorMaster }};border-left: 1px solid {{  $colorMaster }};">
                                 @lang('master.shippers_account_number')                                                 
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="" id="datosShipper" style="color: {{  $colorLetras }};">
                                    {{ $data->nombre_shipper }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="" id="datosShipper" style="color: {{  $colorLetras }};">
                                    {{ $data->direccion_shipper }}                                    
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="" id="datosShipper" style="color: {{  $colorLetras }};">
                                    {{ $data->telefono_shipper }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="" id="datosShipper" style="color: {{  $colorLetras }};">
                                    {{ $data->ciudad_shipper }}, {{ $data->estado_shipper }} - {{ $data->pais_shipper }} - {{ $data->zip_shipper }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="" id="datosShipper" style="color: {{  $colorLetras }};">
                                    
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="fila1">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="">
                                <div class="margen">@lang('master.not_negotiable')</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="margen airWaibil">@lang('master.air_Waybill')</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="margen issuedBy">@lang('master.issued_by')</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="contAir" style="color: {{  $colorLetras }};">
                                    {{ $data->nombre_aerolinea }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info1" style="border-top: 1px solid {{  $colorMaster }};">@lang('master.copies_123_of _this_air_waybill')</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--FILA 2 ***************************************************************-->
            <tr>
                <td class="fila2">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="">
                                <div class="margen titleCons">
                                    <a id="btnBuscarConsignee" data-value="Consignee">@lang('master.consignees_name_and_address')</a>
                                </div>
                            </td>
                            <td>
                                <div class="consigneeNumber" style="border-bottom: 1px solid {{  $colorMaster }};border-left: 1px solid {{  $colorMaster }};">
                                    Consignee's Account Number
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="margen" id="datosConsignee" style="color: {{  $colorLetras }};">
                                    {{ $data->nombre_consignee }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="margen" id="datosConsignee" style="color: {{  $colorLetras }};">
                                    {{ $data->direccion_consignee }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="margen" id="datosConsignee" style="color: {{  $colorLetras }};">
                                    {{ $data->telefono_consignee }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="margen" id="datosConsignee" style="color: {{  $colorLetras }};">
                                    {{ $data->ciudad_consignee }}, {{ $data->estado_consignee }} - {{ $data->pais_consignee }} - {{ $data->zip_consignee }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="margen" id="datosConsignee" style="color: {{  $colorLetras }};">
                                    
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="fila2">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="">
                                <div class="margen info2" style="text-align: justify;margin-right: 5px;">
                           @lang('master.message_acept')
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--FILA 3 ******************************************************************-->
            <tr>
                <td class="fila3">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td colspan="2" style="">
                                <div class="margen titleAgent">@lang('master.issuing_carriers_agent_name_and _city')</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="margen" id="datosIssuing" style="color: {{  $colorLetras }};">
                                    {{ $data->descripcion }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="margen" id="datosIssuing" style="color: {{  $colorLetras }};">
                                    {{ $data->direccion }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="margen" id="datosIssuing" style="color: {{  $colorLetras }};">
                                    {{ $data->telefono }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="margen" id="datosIssuing" style="color: {{  $colorLetras }};">
                                    {{ $data->nombre }}, {{ $data->zip }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;border-top: solid 1px #000; border-right:solid 1px #000; ">
                                <div class="margen titleIata">@lang('master.Agents_IATA_code')</div>
                            </td>
                            <td style="border-top: solid 1px #000;">
                                <div class="margen titleNumA">@lang('master.account_no')</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;border-right:solid 1px #000; ">
                                <div class="margen contentIata" style="color: {{  $colorLetras }};">
                                    {{ $data->agent_iata_code }}
                                </div>
                            </td>
                            <td >
                                <div class="margen contentNumA" style="color: {{  $colorLetras }};">
                                    {{ $data->num_account }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="fila3">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="">
                                <div class="margen titleAcI">@lang('master.accounting_information')</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="">
                                <div class="margen contentAcI" style="color: {{  $colorLetras }};">
                                    {{ $data->account_information }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--FILA 4 *****************************************************************-->
            <tr>
                <td class="fila4" style="width: 50%;">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="">
                                <div class="margen titleAirport">@lang('master.airport_of_departure')</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="margen form-group contentAirport" style="font-size: 13PX;color: {{  $colorLetras }};">
                                   {{ $data->nombre_aeropuerto }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="fila4" style="width: 50%;">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="width:34%;">
                                <div class="titleRef">@lang('master.reference_number')</div>
                            </td>
                            <td style="width:34%;">
                                <div class="titleopInf">@lang('master.optional_shipping_information')</div>
                                <!-- <div class="line1OP" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                <div class="line2OP" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                            </td>
                            <td style="width:32%;">
                                <div class="titleopInf2">&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="contentRef" style="color: {{  $colorLetras }};">
                                    {{ $data->reference_num }}
                                </div>
                            </td>
                            <td style="">
                                <div class="contentoptInf" style="color: {{  $colorLetras }};">
                                    {{ $data->optional_shipping_info }}
                                </div>
                            </td>
                            <td style="">
                                <div class="">&nbsp;</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--FILA 5 *****************************************************************-->
            <tr>
                <td class="fila5" style="border-right:solid 1px #000; ">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="width: 8%;border-right: solid 1px #000;">
                                <div class="titleTo1">to</div>
                            </td>
                            <td style="border-right: solid 1px #000;">
                                <div class="margen titleBFC" style="">@lang('master.by_first_carrier')</div>
                            </td>
                            <td style="border-right: solid 1px #000;">
                                <div class="routingDest" style="text-align: center;height: 11px;font-size: 8px;border-bottom: 1px solid {{  $colorMaster }};">Routing and Destination</div>
                            </td>
                            <td style="width: 8%;border-right: solid 1px #000;">
                                <div class="margen titleTo2">to</div>
                            </td>
                            <td style="width: 8%;border-right: solid 1px #000;">
                                <div class="margen titleBy1">by</div>
                            </td>
                            <td style="width: 8%;border-right: solid 1px #000;">
                                <div class="margen titleTo3">to</div>
                            </td>
                            <td style="width: 8%;">
                                <div class="margen titleBy2">by</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 8%;border-right: solid 1px #000;">
                                <div class="contenttbl5" style="color: {{  $colorLetras }};">
                                    {{ $data->to1 }}
                                </div>
                            </td>
                            <td colspan="2" style="border-right: solid 1px #000;">
                                <div class="contenttbl5" style="color: {{  $colorLetras }}; margin-left: 0px;margin-right: 0px;">
                                    {{ $data->by1 }}
                                </div>
                            </td>
                            <td style="width: 8%;border-right: solid 1px #000;">
                                <div class="contenttbl5" style="color: {{  $colorLetras }};">
                                    
                                </div>
                            </td>
                            <td style="width: 8%;border-right: solid 1px #000;">
                                <div class="contenttbl5" style="color: {{  $colorLetras }};">
                                    
                                </div>
                            </td>
                            <td style="width: 8%;border-right: solid 1px #000;">
                                <div class="contenttbl5" style="color: {{  $colorLetras }};">
                                    
                                </div>
                            </td>
                            <td style="width: 8%;">
                                <div class="contenttbl5" style="color: {{  $colorLetras }};">
                                    
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>

                <!--//Aquí-->
                <td class="fila5">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="border-right: solid 1px #000;">
                                <div class="titletbl5">@lang('master.currency')</div>
                            </td>
                            <td style="border-right: solid 1px #000;">
                                <div class="titletbl5">CHGS</div>
                            </td>
                            <td style="border-right: solid 1px #000;">
                                <div class="titletbl5" style="border-bottom: 1px solid {{  $colorMaster }};">WT/VAL</div>
                            </td>
                            <td style="width: 34%;border-right: solid 1px #000;">
                                <div class="titletbl5">@lang('master.dec_value_carriage')</div>
                            </td>
                            <td style="width: 32%;border-right: solid 1px #000;">
                                <div class="titletbl5">@lang('master.dec_value_customs')</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 20px;border-right: solid 1px #000;">
                                <div class="content5" style="color: {{  $colorLetras }};">
                                    {{ $data->currency }}
                                </div>
                            </td>
                            <td style="border-right: solid 1px #000;">
                                <div class="content5" style="color: {{  $colorLetras }};">
                                    {{ $data->chgs_code }}
                                </div>
                            </td>
                            <td style="border-right: solid 1px #000;">
                                <div class="wtCont">
                                    <div class="ppd" style="border-right: 1px solid {{  $colorMaster }};">
                                        <div id="wtspanpp">PPD</div>
                                        <div id="valpp" class="content5" style="color: {{  $colorLetras }};">
                                            {!! ($data->chgs_code == 'pp') ? 'X' : '' !!}
                                        </div>
                                    </div>
                                    <div class="coll">
                                        <div id="wtspancol">COLL</div>
                                        <div id="valcol" class="content5" style="color: {{  $colorLetras }};">
                                            {!! ($data->chgs_code == 'cll') ? 'X' : '' !!}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="border-right: solid 1px #000;">
                                <div class="content5" style="color: {{  $colorLetras }};">
                                    NDV
                                </div>
                            </td>
                            <td style="border-right: solid 1px #000;">
                                <div class="content5" style="color: {{  $colorLetras }};">
                                    NDV
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--FILA 6 *****************************************************************-->
            <tr>
                <td class="fila6" style="border-right:solid 1px #000; ">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="width: 55%;border-right: solid 1px #000;">
                                <div class="margen titleairport">@lang('master.airport_of_destination')</div>
                            </td>
                            <td style="width: 15%;border-right: solid 1px #000;">
                                <div class="titleflightdate1" style="font-size: 8px;">@lang('master.flight_dat')</div>
                            </td>
                            <td colspan="2" style="width: 15%;border-right: solid 1px #000;">
                                <div class="titleflightdate3" style="font-size: 6px;text-align: center;height: 11px;border-bottom: 1px solid {{  $colorMaster }};">For Carrier Use Only</div>
                            </td>
                            <td style="width: 15%;">
                                <div class="titleflightdate2" style="text-align: right;font-size: 8px;">@lang('master.flight_dat')</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #000;">
                                <div class="contenttbl5" style="color: {{  $colorLetras }};">
                                    <div class="form-group contentAirport" style="font-size: 9px;color: {{  $colorLetras }};">
                                        {{ $data->aeropuerto_destino }}
                                    </div>
                                </div>
                            </td>
                            <td colspan="2" style="border-right: solid 1px #000;">
                                <div class="contenttbl5" style="color: {{  $colorLetras }};">
                                    <div class="contentflightdate1" style="font-size: 10px;color: {{  $colorLetras }};">
                                        {{ $data->fecha_vuelo1 }}
                                    </div>
                                </div>
                            </td>
                            <td colspan="2" style="">
                                <div class="contenttbl5" style="color: {{  $colorLetras }};">
                                    <div class="contentflightdate2" style="font-size: 10px;color: {{  $colorLetras }};">
                                        {{ $data->fecha_vuelo2 }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="fila6">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td style="width: 25%;border-right: solid 1px #000;">
                                <div class="margen titleAirport" style="height: 18px;font-size: 8px;">@lang('master.amount_of_insurance')</div>
                            </td>
                            <td rowspan="2" style="border-right: solid 1px #000;">
                                <div class="contentifinsura" style="font-size: 8px;">
                                        @lang('master.insurance')
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #000;">
                                <div class=" form-group contentAirport" style="color: {{  $colorLetras }};"> 
                                    {{ $data->amount_insurance }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--FILA 7 *****************************************************************-->
            <tr>
                <td colspan="2" class="fila7" style="border-right:solid 1px #000; ">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td colspan="3" style="width: 100%;border-right: solid 1px #000;">
                                <div class="margen titlehandinginf">@lang('master.handling_information')</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="width: 100%;border-right: solid 1px #000;">
                                <div class="form-group margen contenthandinginf" style="height: 40px;font-size: 15px;color: {{  $colorLetras }};">
                                   {{ $data->handing_information }}
                                </div>
                            </td>                        
                        </tr>
                        <tr>
                            <td style="width: 410px;">
                                <div class="hendInf1" style="width: 90%;font-size: 8px;">
                                    @lang('master.message_technology')
                                </div>
                            </td>
                            <td style="width: 100px;">
                                <div class="hendInf2" style="font-size: 8px;">
                                   @lang('master.diversion_contrary')
                                </div>
                            </td>
                            <td style="width: 90px;border-left: solid 1px #000;border-right: solid 1px #000;border-top: solid 1px #000;height: 30px;">
                                <div class="" style="text-align: center;">SCI</div>
                                <div class="" style="font-weight: bold;font-size: 13;color: {{  $colorLetras }};">
                                    
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>            
            </tr>
            <!--FILA 8 *****************************************************************-->
            <tr>
                <td colspan="2" style="border:solid 1px #000; ">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;font-weight: bold;">
                        <!-- CABECERA DEL DETALLE -->
                        <tr>
                            <td style="text-align: center;width: 60px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">@lang('master.n°_of_pieces_rcp')</td>
                            <td id="space" style="background-color: #ccc; width: 5px;border-top: 1px solid #000;">&nbsp;</td>
                            <td style="text-align: center;width: 45px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">@lang('master.gross_weight')</td>
                            <td id="space" style="background-color: #ccc; width: 5px;border-top: 1px solid #000;">&nbsp;</td>
                            <td style="text-align: center;width: 15px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">kg lb</td>
                            <td id="space" style="background-color: #ccc; width: 5px;border-top: 1px solid #000;">&nbsp;</td>
                            <td style="text-align: center;width: 90px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">
                                Rate Class
                                <div><div style="border-top: 1px solid #000;border-left: 1px solid #000;width: 90%;margin-left: 9px;">@lang('master.commodity_item_n')</span></div>
                            </td>
                            <td id="space" style="background-color: #ccc; width: 5px;border-top: 1px solid #000;">&nbsp;</td>
                            <td style="text-align: center;width: 65px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">@lang('master.chargeable_weight')</td>
                            <td id="space" style="background-color: #ccc; width: 5px;border-top: 1px solid #000;">&nbsp;</td>
                            <td style="text-align: center;width: 40px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">@lang('master.rate_charge')</td>
                            <td id="space" style="background-color: #ccc; width: 5px;border-top: 1px solid #000;">&nbsp;</td>
                            <td style="text-align: center;width: 70px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">@lang('master.total')</td>
                            <td id="space" style="background-color: #ccc; width: 5px;border-top: 1px solid #000;">&nbsp;</td>
                            <td style="text-align: center;;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">@lang('master-nature_and_quantity')</td>
                        </tr>

                        <!-- CUERPO DEL DETALLE -->
                        <tr>
                            <td style="vertical-align: text-top;text-align: center;width: 60px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000;height: 110px;">{{ $detalle->piezas }}</td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="vertical-align: text-top;text-align: center;width: 45px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">{{ $detalle->peso }} <br> {{ $detalle->peso }}</td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="vertical-align: text-top;text-align: center;width: 15px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">K<br>l</td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="vertical-align: text-top;text-align: center;width: 90px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">
                                {{ $detalle->rate_class }}
                            </td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="vertical-align: text-top;text-align: center;width: 65px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">{{ $detalle->peso_cobrado }}</td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="vertical-align: text-top;text-align: center;width: 40px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">{{ $detalle->tarifa }}</td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="vertical-align: text-top;text-align: center;width: 70px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">$ {{ $detalle->total }}</td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="vertical-align: text-top;text-align: center;;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">{{ $detalle->descripcion }}</td>
                        </tr>

                        <!-- FOOTER DEL DETALLE -->
                        <tr>
                            <td style="text-align: center;width: 60px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000;height: 20px;">{{ $detalle->piezas }}</td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="text-align: center;width: 45px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">{{ $detalle->peso }}</td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="width: 15px;border-right: 1px solid #000;border-left: 1px solid #000"></td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="width: 90px;border-right: 1px solid #000;border-left: 1px solid #000">
                            </td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="width: 65px;border-right: 1px solid #000;border-left: 1px solid #000"></td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="width: 40px;border-right: 1px solid #000;border-left: 1px solid #000"></td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="text-align: center;width: 70px;border-top: 1px solid #000;border-right: 1px solid #000;border-left: 1px solid #000">$ {{ $detalle->total }}</td>
                            <td id="space" style="background-color: #ccc; width: 5px;">&nbsp;</td>
                            <td style="border-right: 1px solid #000;border-left: 1px solid #000"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="fila8" style="border-right:solid 1px #000; ">
                    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <!--FILA 9 *****************************************************************-->
                        <tr>
                            <td colspan="8" class="fila9" style="border-right:solid 1px #000; ">
                                <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="border-right: solid 1px #000;">
                                            <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 33.3%">
                                                        <div class="wCharge" style="border: 1px solid {{  $colorMaster }};width: 50%;margin-left: 19px;">@lang('master.prepaid')</div>
                                                        <!-- <div class="linePre" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="linePre2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                    <td colspan="2" style="width: 33.4%">
                                                        <div class="wCharge" style="border: 1px solid {{  $colorMaster }};width: 81%;margin-left: 4px;">@lang('master.weight_charge')</div>
                                                        <!-- <div class="lineWe" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineWe2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                    <td style="width: 33.3%">
                                                        <div class="wCharge" style="border: 1px solid {{  $colorMaster }};width: 50%;margin-left: 19px;">@lang('master.collect')</div>
                                                        <!-- <div class="lineCol" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineCol2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style=" height: 20px;border-right:1px solid {{  $colorMaster }}; border-bottom: 1px solid {{  $colorMaster }};">
                                                            {{ $data->total_prepaid }}
                                                        </div>
                                                    </td>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="height: 20px; border-bottom: 1px solid {{  $colorMaster }};">
                                                            
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="width: 33.4%">
                                                        <div class="wCharge" style="border: 1px solid {{  $colorMaster }};width: 50%;margin-left: 90px;">@lang('master.valuation_charge')</div>
                                                        <!-- <div class="lineVal" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineVal2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="wCharge" style="height: 20px;border-right:1px solid {{  $colorMaster }}; border-bottom: 1px solid {{  $colorMaster }};">&nbsp;</div>
                                                    </td>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="wCharge" style="height: 20px;border-bottom: 1px solid {{  $colorMaster }};">&nbsp;</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 33.3%">
                                                        <div class="wCharge" style="">
                                                            <!-- TAX -->
                                                        </div>
                                                    </td>
                                                    <td colspan="2" style="width: 33.4%">
                                                        <div class="wCharge" style="border: 1px solid {{  $colorMaster }};">Tax</div>
                                                        <!-- <div class="lineTax" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineTax2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                    <td style="width: 33.3%">
                                                        <div class="wCharge" style="">
                                                            <!-- TAX -->
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="wCharge" style="height: 20px;border-right:1px solid {{  $colorMaster }}; border-bottom: 1px solid {{  $colorMaster }};">
                                                            {!! ($data->chgs_code == 'pp') ? $data->tax : '' !!}
                                                        </div>
                                                    </td>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="wCharge" style="height: 20px;border-bottom: 1px solid {{  $colorMaster }};">
                                                            {!! ($data->chgs_code == 'cll') ? $data->tax : '' !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: solid 1px #000;">
                                            <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td colspan="4" style="width: 33.4%">
                                                        <div class="wCharge" style="border: 1px solid {{  $colorMaster }}; width: 70%;margin-left: 37px;">@lang('master.total_other_charges')</div>
                                                        <!-- <div class="lineTo" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineTo2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="height: 20px;border-right:1px solid {{  $colorMaster }}; border-bottom: 1px solid {{  $colorMaster }};">
                                                            {!! ($data->chgs_code == 'cll') ? $data->total_other_charge_due_agent : '' !!}
                                                        </div>
                                                    </td>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="height: 20px;border-bottom: 1px solid {{  $colorMaster }};">
                                                            {!! ($data->chgs_code == 'cll') ? $data->total_other_charge_due_agent : '' !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="width: 33.4%">
                                                        <div class="wCharge" style="border: 1px solid {{  $colorMaster }}; width: 70%;margin-left: 37px;">@lang('master.total_other_charges1')</div>
                                                        <!-- <div class="lineTo" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineTo2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="height: 20px;border-right:1px solid {{  $colorMaster }}; border-bottom: 1px solid {{  $colorMaster }};">
                                                            {!! ($data->chgs_code == 'cll') ? $data->total_other_charge_due_carrier : '' !!}
                                                        </div>
                                                    </td>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="height: 20px;border-bottom: 1px solid {{  $colorMaster }};">
                                                            {!! ($data->chgs_code == 'cll') ? $data->total_other_charge_due_carrier : '' !!}
                                                       </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="background-color: #b4b6b8;height: 30px;border-right:1px solid {{  $colorMaster }}; border-bottom: 1px solid {{  $colorMaster }};">&nbsp;</div>
                                                    </td>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="background-color: #b4b6b8;height: 30px;border-bottom: 1px solid {{  $colorMaster }};">&nbsp;</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%;border-right: 1px solid {{  $colorMaster }};">
                                                        <div class="wCharge" style="border: 1px solid {{  $colorMaster }};width: 70px;margin-left: 52px;">@lang('master.total_prepaid')</div>
                                                        <!-- <div class="lineTpre" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineTpre2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                    <td colspan="2" style="width: 50%;">
                                                        <div class="wCharge" style="border: 1px solid {{  $colorMaster }};width: 70px;margin-left: 22px;">@lang('master.total_collect')</div>
                                                        <!-- <div class="lineTpre" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineTpre2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%;">
                                                        <div class="valueTotal" style="height: 30px;border-right:1px solid {{  $colorMaster }}; border-bottom: 1px solid {{  $colorMaster }};">
                                                            {!! ($data->chgs_code == 'pp') ? $data->total_other_charge_due_carrier + $data->total_other_charge_due_agent + $data->tax : '' !!}
                                                        </div>
                                                    </td>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="height: 30px;border-bottom: 1px solid {{  $colorMaster }};">
                                                            {!! ($data->chgs_code == 'pp') ? $data->total_other_charge_due_carrier + $data->total_other_charge_due_agent + $data->tax : '' !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%;border-right: 1px solid {{  $colorMaster }};">
                                                        <div class="wCharge" style="width: 80%;margin-left: 12px;height: 12px;font-size: 8px;border: 1px solid {{  $colorMaster }};">@lang('master.currency_conversion')</div>
                                                        <!-- <div class="lineCurr" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineCurr2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="wCharge" style="width: 85%;margin-left: 10px;height: 12px;font-size: 8px;border: 1px solid {{  $colorMaster }};">@lang('master.charges_in_dest')</div>
                                                        <!-- <div class="lineChar" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineChar2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="height: 20px;border-right:1px solid {{  $colorMaster }}; border-bottom: 1px solid {{  $colorMaster }};">
                                                           {{ $data->currency }}
                                                        </div>
                                                    </td>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="height: 20px;border-bottom: 1px solid {{  $colorMaster }};">
                                                                
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" rowspan="2" style="width: 50%">
                                                        <div class="wCharge" style="height: 35px;border-right:1px solid {{  $colorMaster }};border-left:1px solid {{  $colorMaster }};border-bottom: 1px solid {{  $colorMaster }};">@lang('master.for_carriers')</div>
                                                    </td>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="wCharge" style="height: 12px;font-size: 9px;width: 83%;margin-left: 11px;margin-top: -5px;border: 1px solid {{  $colorMaster }};">@lang('master.charges_at_destination')</div>
                                                        <!-- <div class="lineChar" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                        <div class="lineChar2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 50%">
                                                        <div class="valueTotal" style="height: 22px;border-bottom: 1px solid {{  $colorMaster }};">
                                                            
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td colspan="7" class="fila9" style="border-right:solid 1px #000; ">
                                <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="height: 102px;border-bottom: solid 1px #000;">
                                            <div class="margen titleOtherCharge">@lang('master.other_charge')</div>
                                            <div class="margen contOtherCharge" style="font-size: 12px;height: 90px;font-weight: bold;color: {{  $colorLetras }};">
                                                {{ $data->other_charges }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 120px;border-bottom: solid 1px #000;">
                                            <div class="margen titleOtherCharge" style="text-align: justify;margin-right: 5px;">
                                              @lang('master.shipper_certifies')
                                            </div>
                                            <div class="margen contShiporAgent" style="font-size: 10px;height: 49px;color: {{  $colorLetras }};">
                                                
                                            </div>
                                            <div class="contShiporAgent2" style="border-top: 1px dashed {{  $colorMaster }};text-align: center;">@lang('master.signature_of_shippe')</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom: solid 1px #000;">
                                            <table border="0" cellspacing="0" cellpadding="0" style="height: 100px;width: 100%;">
                                                <tr>
                                                    <td style="height: 48px;border-bottom: dashed 1px #000;">
                                                        <div class="dateplacecont" style="padding-left: 5px;  vertical-align: bottom;font-weight: bold;font-size: 20px;color: {{  $colorLetras }};">
                                                            {{ $data->fecha_vuelo1 }}
                                                        </div>                                                        
                                                    </td>
                                                    <td style="border-bottom: dashed 1px #000;">
                                                        <div class="signatureAgentcont" style="color: {{  $colorLetras }};">
                                                            <div class="" style="font-weight: bold;font-size: 12px;color: {{  $colorLetras }};">
                                                                {{ $data->descripcion }}
                                                            </div>
                                                            <div class="" style="font-weight: bold;font-size: 12px;color: {{  $colorLetras }};">
                                                                {{ $data->direccion }}
                                                                
                                                            </div>
                                                            <div class="" style="font-weight: bold;font-size: 12px;color: {{  $colorLetras }};">
                                                                {{ $data->nombre }}, {{ $data->zip }}
                                                                
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 15px;border-bottom: solid 1px #000;">
                                                        <div class="dateplace">
                                                            @lang('master.executed_on') &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            (@lang('master.date')) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                                            at &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (@lang('master.place'))
                                                        </div>
                                                    </td>
                                                    <td style="border-bottom: solid 1px #000;">
                                                        <div class="signatureAgent">@lang('master.signature_of_issuing')</div>  
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="height: 30px;">
                                                        <table border="0" cellspacing="0" cellpadding="0" style="height: 30px;width: 100%;">
                                                            <tr>
                                                                <td style="width: 30%;border-right:1px solid {{  $colorMaster }};">
                                                                    <div class="wCharge" style="margin-left: 12px;width: 80%;font-size: 10px;">@lang('master.total_collect_charges')</div>
                                                                    <!-- <div class="lineTChar" style="border-bottom: 1px solid {{  $colorMaster }};"></div>
                                                                    <div class="lineTChar2" style="border-bottom: 1px solid {{  $colorMaster }};"></div> -->
                                                                </td>
                                                                <td rowspan="2" style="">
                                                                    <div class="cont4" style="height: 35px;text-align: center;color: {{  $colorLetras }};">
                                                                        999999
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="">
                                                                    <div class="contCols" style="height: 20px;border-right:1px solid {{  $colorMaster }};color: {{  $colorLetras }};">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td> 
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="margin-top: 5px;font-weight: bold;text-align: center;font-size: 15px;">
                        @if($i == 1)
                            {{ $destino1 }}
                        @elseif($i == 2)
                            {{ $destino2 }}
                        @elseif($i == 3)
                            {{ $destino3 }}
                        @elseif($i == 4)
                            {{ $destino4 }}
                        @elseif($i == 5)
                            {{ $destino5 }}
                        @elseif($i == 6)
                            {{ $destino6 }}
                        @elseif($i == 7 || $i == 8)
                            {{ $destino6 }}
                        @endif
                    </div>
                </td>
            </tr>     
        </table>
    @endfor
</body>