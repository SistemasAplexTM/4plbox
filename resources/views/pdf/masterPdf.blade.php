<?php
    $left = '2.32';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <link href="style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        @for($i = 1; $i <= $cantidad; $i++)
	        @if($cantidad == 1)
	        <?php $i=8; ?>
	        @endif
	        <?php  $colorLetras = '';  ?>
	        @if($i == 1)
	        <?php $destino = 'ORIGINAL COPY 1 (AIRPORT OF DESTINATION)' ?>
	        <?php $img_master = 'ci_5.png'; ?>
	        @elseif($i == 2)
	        <?php $destino = 'ORIGINAL COPY 2 (FOR CONSIGNEE)' ?>
	        <?php $img_master = 'ci_2.png'; ?>
	        <?php $colorLetras = '#ff0000'; ?>
	        @elseif($i == 3)
	        <?php $destino = 'ORIGINAL COPY 3 (FOR ISSUING CARRIER)' ?>
	        <?php $img_master = 'ci_1.png'; ?>
	        <?php $colorLetras = '#00cc00'; ?>
	        @elseif($i == 4)
	        <?php $destino = 'ORIGINAL COPY 4 (FOR SHIPPER)' ?>
	        <?php $img_master = 'ci_3.png'; ?>
	        <?php $colorLetras = '#000099'; ?>
	        @elseif($i == 5)
	        <?php $destino = 'ORIGINAL COPY 5 (DELIVERY RECEIPT)' ?>
	        <?php $img_master = 'ci_4.png'; ?>
	        <?php $colorLetras = '#ff9900'; ?>
	        @elseif($i == 6)
	        <?php $destino = 'EXTRA COPY' ?>
	        <?php $img_master = 'ci_6.png'; ?>
	        <?php $left = '3.31' ?>
	        @elseif($i == 7 || $i == 8)
	        <?php $destino = 'EXTRA COPY' ?>
	        <?php $img_master = 'ci_6.png'; ?>
	        <?php $left = '3.31' ?>
	        @endif
        <table border="0" cellpadding="0" cellspacing="0" id="tableContainer" style="page-break-after:always;">
            <tr>
                <td>
                    <style type="text/css">
                        table {border-collapse: collapse;}
			table td {padding: 0px}
                    </style>
                    <img src="{{ asset('img/master/'.$img_master) }}" style="position:absolute;top:-0.30in;left:0.15in;width:7.65in;height:10.50in"/>
                    {{-- NUMERO MASTER --}}
                    <div style="position:absolute;top:-0.26in;left:0.14in;width:2.8in;font-size:15pt;">
                        <span style="font-style:normal;font-weight:bold;font-family:Helvetica;color:#000000">
                            {{ $data->codigo_aerolinea  }}
                        </span>
                    </div>
                    <div style="position:absolute;top:-0.26in;left:0.56in;width:2.8in;font-size:15pt;">
                        <span style="font-style:normal;font-weight:bold;font-family:Helvetica;color:#000000">
                            {{ $data->prefijo }}
                        </span>
                    </div>
                    <div style="position:absolute;top:-0.26in;left:1.1in;width:2.8in;font-size:15pt;">
                        <span style="font-style:normal;font-weight:bold;font-family:Helvetica;color:#000000">
                            {{ substr($data->num_master,3) }}
                        </span>
                    </div>
                    <div style="position:absolute;top:-0.26in;left:6.3in;width:2.8in;font-size:15pt;">
                        <span style="font-style:normal;font-weight:bold;font-family:Helvetica;color:#000000">
                            {{ $data->codigo_aerolinea .'-'. substr($data->num_master,3) }}
                        </span>
                    </div>
                    <div style="position:absolute;top:0in;left:0.19in;width:1.20in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Shipper&apos;s Name and Address
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:0in;left:2.50in;width:1.10in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Shipper&apos;s Account Number
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:0in;left:4in;width:0.61in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Not negotiable
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:0.15in;left:4in;width:0.99in;line-height:0.17in;">
                        <span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Air Waybill
                        </span>
                        <span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:0.45in;left:4in;width:0.41in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Issued by
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:1.07in;left:0.19in;width:1.33in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Consignee&apos;s Name and Address
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:1.07in;left:2.50in;width:1.22in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Consignee&apos;s Account Number
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:1.45in;left:4in;width:3.10in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Copies 1, 2 and 3 of this Air Waybill are Originals and have the same validity
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:1.65in;left:4in;width:3.84in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            It is agreed that the goods described herein are accepted in apparent good order and condition
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:1.75in;left:4in;width:3.68in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            (except as noted)  for carriage subject  to conditions of contract  on the reverse hereof. The
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:1.85in;left:4in;width:3.61in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            shipper&apos;s attention is drawn to the notice concerning carrier&apos;s limitation of liability. Shipper
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:1.95in;left:4in;width:3.83in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            may increase such limitation of liability by declaring a higher value for carriage and paying for it
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:2.14in;left:0.19in;width:1.57in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Issuing Carrier&apos;s Agent Name and City
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:2.14in;left:4in;width:0.95in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Accounting Information
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:2.84in;left:0.19in;width:0.80in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Agent&apos;s IATA Code
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:2.84in;left:2.25in;width:0.52in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Account No.
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.19in;left:0.19in;width:2.74in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Airport of Departure(Address of first Carrier) and requested Routing
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.55in;left:0.19in;width:0.10in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            to
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.55in;left:0.75in;width:0.61in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            By first Carrier
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.55in;left:2.25in;width:0.10in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            to
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.55in;left:2.8in;width:0.12in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            by
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.55in;left:3.15in;width:0.10in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            to
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.55in;left:3.7in;width:0.12in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            by
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.55in;left:4in;width:0.39in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Currency
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.55in;left:4.56in;width:0.29in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            CHGS
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.53in;left:4.95in;width:0.36in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            WT VAL
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.53in;left:5.55in;width:0.25in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Other
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.55in;left:5.99in;width:0.84in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Dec. Value Carriage
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.55in;left:6.9in;width:0.85in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Dec. Value Customs
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.9in;left:0.19in;width:0.87in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Airport of Destination
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.9in;left:2.25in;width:0.46in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Flight/Date
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.9in;left:3.16in;width:0.46in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Flight/Date
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.9in;left:4in;width:0.86in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Amount of Insurance
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:3.9in;left:5.55in;width:2.43in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            INSURANCE: If carrier offers insurance and such insurance
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:4in;left:5.55in;width:2.47in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            is requested inaccordance with conditions on reverse hereof,
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:4.1in;left:5.55in;width:2.38in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            indicate amount to be insured in box Amount of Insurance.
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:4.25in;left:0.19in;width:0.86in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Handling Information
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:4.95in;left:0.19in;width:0.27in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            No. of
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:5.02in;left:0.19in;width:0.30in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Pieces
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:5.11in;left:0.19in;width:0.22in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            RCP
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:4.95in;left:0.9in;width:0.27in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Gross
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:5.1in;left:0.9in;width:0.31in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Weight
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:4.95in;left:1.45in;width:0.12in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            kg
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:5.1in;left:1.45in;width:0.10in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            lb
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:4.95in;left:1.8in;width:0.47in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Rate Class
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:5.08in;left:1.8in;width:0.48in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Commodity
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:5.15in;left:1.8in;width:0.37in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Item No.
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:4.95in;left:2.7in;width:0.50in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Chargeable
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:5.1in;left:2.8in;width:0.31in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Weight
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:4.95in;left:3.5in;width:0.22in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Rate
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:5.15in;left:3.7in;width:0.32in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Charge
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:5.1in;left:4.6in;width:0.23in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Total
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:4.99in;left:6.1in;width:1.23in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Nature and Quantity of Goods
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:5.11in;left:6.12in;width:1.19in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            (Incl. Dimensions or Volume)
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:7.67in;left:0.27in;width:0.34in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Prepaid
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:7.67in;left:1in;width:0.63in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Weight Charge
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:7.67in;left:2.05in;width:0.30in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Collect
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:7.68in;left:2.45in;width:0.62in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Other Charges
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:7.95in;left:0.91in;width:0.73in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Valuation Charge
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:8.23in;left:1.2in;width:0.18in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Tax
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:8.51in;left:0.7in;width:1.30in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Total Other Charges Due Agent
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:8.8in;left:0.7in;width:1.34in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Total Other Charges Due Carrier
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:8.52in;left:2.45in;width:5.41in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Shipper certifies that the particulars on the face hereof are correct and that insofar as any part of the consignment contains dangerous
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:8.63in;left:2.45in;width:5.29in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            goods, such part is property described by name and is in proper condition for carriage by air according to the applicable Dangerous
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:8.74in;left:2.45in;width:0.82in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Goods Regulations.
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.15in;left:2.5in;width:5.59in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            ............................................................................................................................................................................................................................
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.25in;left:4.50in;width:1.36in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Signature of Shipper or his Agent
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.37in;left:0.5in;width:0.57in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Total Prepaid
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.37in;left:1.59in;width:0.53in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Total Collect
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.65in;left:0.37in;width:0.88in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Currency Conversion
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.65in;left:1.37in;width:1.10in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Charges in Dest. Currency
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.7in;left:2.5in;width:5.59in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            ............................................................................................................................................................................................................................
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.8in;left:2.45in;width:4.79in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Executed on               (Date)                 at             (Place)                                     Signature of Issuing Carrieror its Agent
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.93in;left:0.3in;width:0.93in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            For Carrier&apos;s Use Only
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:10in;left:0.42in;width:0.58in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            at Destination
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.93in;left:1.43in;width:0.95in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Charges at Destination
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:9.93in;left:2.85in;width:0.90in;line-height:0.08in;">
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            Total Collect Charges
                        </span>
                        <span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    <div style="position:absolute;top:10.23in;left:{{ $left }}in;width:4in;line-height:0.17in;">
                        <span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Helvetica;color:{{ $colorLetras }}">
                            {{ $destino }}
                        </span>
                        <span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Helvetica;color:{{ $colorLetras }}">
                        </span>
                        <br/>
                    </div>
                    {{-- SHIPPER INFORMATION --}}
                    <div style="position:absolute;top:0.13in;left:0.19in;width:3.7in;line-height:0.16in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->nombre_shipper }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->telefono_shipper }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->direccion_shipper }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->ciudad_shipper }}, {{ $data->estado_shipper }} - {{ $data->pais_shipper }} - {{ $data->zip_shipper }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            Contacto: {{ $data->nombre_shipper }}
                        </span>
                        <br/>
                    </div>
                    <br/>
                    <div style="position:absolute;top:0.45in;left:4.5in;width:1.87in;line-height:0.17in;">
                        <span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Helvetica;color:#000000">
                            {{ $data->nombre_aerolinea }}
                        </span>
                        <span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Helvetica;color:#000000">
                        </span>
                        <br/>
                    </div>
                    {{-- CONSIGNEE INFORMATION --}}
                    <div style="position:absolute;top:1.18in;left:0.19in;width:3.7in;line-height:0.16in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->nombre_consignee }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->telefono_consignee }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->direccion_consignee }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->ciudad_consignee }}, {{ $data->estado_consignee }} - {{ $data->pais_consignee }} - {{ $data->zip_consignee }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            Contacto: {{ $data->nombre_consignee }}
                        </span>
                        <br/>
                    </div>
                    <br/>
                    {{-- INSURE CARRIER INFORMATION --}}
                    <div style="position:absolute;top:2.23in;left:0.19in;width:3.7in;line-height:0.13in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->nombre_carrier }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->telefono_carrier }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->direccion_carrier }}
                        </span>
                        <br/>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->ciudad_carrier }}, {{ $data->zip_carrier }}
                        </span>
                        <br/>
                    </div>
                    <br/>
                    {{-- AGENT IATA CODE INFORMATION --}}
                    <div style="position:absolute;top:2.98in;left:0.19in;width:3.7in;line-height:0.13in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->agent_iata_code }}
                        </span>
                    </div>
                    <br/>
                    {{-- ACOUNT NUMBER INFORMATION --}}
                    <div style="position:absolute;top:2.98in;left:2.3in;width:3.7in;line-height:0.13in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->num_account }}
                        </span>
                    </div>
                    <br/>
                    {{-- AIR POSRT DEPARTURE INFORMATION --}}
                    <div style="position:absolute;top:3.35in;left:0.19in;width:3.7in;line-height:0.13in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->nombre_aeropuerto }}
                        </span>
                    </div>
                    <br/>
                    {{-- ACOUNTING INFORMATION INFORMATION --}}
                    <div style="position:absolute;top:2.23in;left:4in;width:3.7in;line-height:0.13in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->account_information }}
                        </span>
                    </div>
                    <br/>
                    {{-- TO INFORMATION --}}
                    <div style="position:absolute;top:3.7in;left:0.19in;width:0.48in;line-height:0.13in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->to1 }}
                        </span>
                    </div>
                    {{-- BY FIRST CARRIER INFORMATION --}}
                    <div style="position:absolute;top:3.7in;left:0.76in;width:1.37in;line-height:0.13in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->by_first_carrier }}
                        </span>
                    </div>
                    {{-- TO INFORMATION --}}
                    <div style="position:absolute;top:3.7in;left:2.24in;width:0.48in;line-height:0.13in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                        </span>
                    </div>
                    {{-- BY INFORMATION --}}
                    <div style="position:absolute;top:3.7in;left:2.81in;width:0.24in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->by1 }}
                        </span>
                    </div>
                    {{-- TO INFORMATION --}}
                    <div style="position:absolute;top:3.7in;left:3.14in;width:0.48in;line-height:0.13in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                        </span>
                    </div>
                    {{-- BY INFORMATION --}}
                    <div style="position:absolute;top:3.7in;left:3.7in;width:0.24in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                        </span>
                    </div>
                    {{-- CURRENCY INFORMATION --}}
                    <div style="position:absolute;top:3.7in;left:4in;width:0.5in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->currency }}
                        </span>
                    </div>
                    <div style="position:absolute;top:3.7in;left:4.56in;width:0.24in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->chgs_code }}
                        </span>
                    </div>
                    <div style="position:absolute;top:3.7in;left:4.88in;width:0.21in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {!! ($data->chgs_code == 'pp') ? 'X' : '' !!}
                        </span>
                    </div>
                    <div style="position:absolute;top:3.7in;left:5.16in;width:0.21in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {!! ($data->chgs_code == 'cll') ? 'X' : '' !!}
                        </span>
                    </div>
                    {{-- VALUE CARRIRE INFORMATION --}}
                    <div style="position:absolute;top:3.7in;left:6in;width:0.7in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            NDV
                        </span>
                    </div>
                    {{-- VALUE CUSTOM INFORMATION --}}
                    <div style="position:absolute;top:3.7in;left:6.95in;width:0.7in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            NDV
                        </span>
                    </div>
                    {{-- AIRPORT DESTINATION INFORMATION --}}
                    <div style="position:absolute;top:4.05in;left:0.19in;width:1.9in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->aeropuerto_destino }}
                        </span>
                    </div>
                    {{-- FLIGTH INFORMATION --}}
                    <div style="position:absolute;top:4.05in;left:2.26in;width:0.75in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->fecha_vuelo1 }}
                        </span>
                    </div>
                    <div style="position:absolute;top:4.05in;left:3.18in;width:0.75in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->fecha_vuelo2 }}
                        </span>
                    </div>
                    {{-- AMOUNTH OF INSURANCE INFORMATION --}}
                    <div style="position:absolute;top:4.05in;left:4in;width:1.5in;line-height:0.12in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->amount_insurance }}
                        </span>
                    </div>
                    {{-- HANDING INFORMATION --}}
                    <div style="position:absolute;top:4.10in;left:0.19in;width:7.57in;line-height:0.47in;font-size:13pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->handing_information }}
                        </span>
                    </div>
                    {{-- TOTALES INFORMATION --}}
                    <div style="position:absolute;top:5.33in;left:0.19in;width:0.5in;line-height:0.16in;font-size:13pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $detalle->piezas }}
                        </span>
                    </div>
                    <div style="position:absolute;top:5.33in;left:0.78in;width:0.57in;line-height:0.16in;font-size:13pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ round($detalle->peso,2) }}
                            <br>
                                {{ round($detalle->peso * 0.453592, 2) }}
                            </br>
                        </span>
                    </div>
                    <div style="position:absolute;top:5.33in;left:1.46in;width:0.1in;line-height:0.16in;font-size:9pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            k
                            <br>
                                l
                            </br>
                        </span>
                    </div>
                    <div style="position:absolute;top:5.33in;left:1.8in;width:0.57in;line-height:0.16in;font-size:13pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $detalle->rate_class }}
                        </span>
                    </div>
                    <div style="position:absolute;top:5.33in;left:2.65in;width:0.57in;line-height:0.16in;font-size:13pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $detalle->peso_cobrado }}
                        </span>
                    </div>
                    <div style="position:absolute;top:5.33in;left:3.5in;width:0.57in;line-height:0.16in;font-size:13pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $detalle->tarifa }}
                        </span>
                    </div>
                    <div style="position:absolute;top:5.33in;left:4.27in;width:0.96in;line-height:0.16in;font-size:13pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            $ {{ $detalle->total }}
                        </span>
                    </div>
                    <div style="position:absolute;top:5.33in;left:5.44in;width:2.3in;line-height:0.16in;font-size:9pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $detalle->descripcion }}
                        </span>
                    </div>
                    <div style="position:absolute;top:7.47in;left:0.19in;width:0.5in;line-height:0.16in;font-size:13pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $detalle->piezas }}
                        </span>
                    </div>
                    <div style="position:absolute;top:7.47in;left:0.78in;width:0.57in;line-height:0.16in;font-size:13pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ round($detalle->peso * 0.453592, 2) }}
                        </span>
                    </div>
                    <div style="position:absolute;top:7.47in;left:4.27in;width:0.96in;line-height:0.16in;font-size:13pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            $ {{ $detalle->total }}
                        </span>
                    </div>
                    {{-- PREPAID --}}
                    <div style="position:absolute;top:7.79in;left:0.19in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {!! ($data->chgs_code == 'pp') ?  '$ '.$detalle->total : '' !!}
                        </span>
                    </div>
                    {{-- COLLET --}}
                    <div style="position:absolute;top:7.79in;left:1.35in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {!! ($data->chgs_code == 'cll') ?  '$ '.$detalle->total : '' !!}
                        </span>
                    </div>
                    {{-- VALUATION CHARGE --}}
                    <div style="position:absolute;top:8.07in;left:0.19in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{-- {!! ($data->chgs_code == 'pp') ?  '$ ' : '' !!} --}}
                        </span>
                    </div>
                    <div style="position:absolute;top:8.07in;left:1.35in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{-- {!! ($data->chgs_code == 'cll') ?  '' : '$ ' !!} --}}
                        </span>
                    </div>
                    {{-- TAX--}}
                    <div style="position:absolute;top:8.36in;left:0.19in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{-- {!! ($data->chgs_code == 'pp') ?  '$ '.$data->tax : '' !!} --}}
                        </span>
                    </div>
                    <div style="position:absolute;top:8.36in;left:1.35in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{-- {!! ($data->chgs_code == 'cll') ?  '' : '$ '.$data->tax !!} --}}
                        </span>
                    </div>
                    {{-- Total Other Charges Due Agent --}}
                    <div style="position:absolute;top:8.63in;left:0.19in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {!! ($data->chgs_code == 'pp') ?  '$ '.$data->total_other_charge_due_agent : '' !!}
                        </span>
                    </div>
                    <div style="position:absolute;top:8.63in;left:1.35in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {!! ($data->chgs_code == 'cll') ?  '$ '.$data->total_other_charge_due_agent : '' !!}
                        </span>
                    </div>
                    {{-- Total Other Charges Due Carrier --}}
                    <div style="position:absolute;top:8.92in;left:0.19in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {!! ($data->chgs_code == 'pp') ?  '$ '.$data->total_other_charge_due_carrier : '' !!}
                        </span>
                    </div>
                    <div style="position:absolute;top:8.92in;left:1.35in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {!! ($data->chgs_code == 'cll') ?  '$ '.$data->total_other_charge_due_carrier : '' !!}
                        </span>
                    </div>
                    {{-- Total Prepaid  --}}
                    <div style="position:absolute;top:9.48in;left:0.19in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {!! ($data->chgs_code == 'pp') ?  '$ '.($data->total_other_charge_due_carrier + $data->total_other_charge_due_agent + $detalle->total) : '' !!}
                        </span>
                    </div>
                    {{-- Total Collet  --}}
                    <div style="position:absolute;top:9.48in;left:1.35in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {!! ($data->chgs_code == 'cll') ?  '$ '.($data->total_other_charge_due_carrier + $data->total_other_charge_due_agent + $detalle->total) : '' !!}
                        </span>
                    </div>
                    {{-- Currency Conversion  --}}
                    <div style="position:absolute;top:9.78in;left:0.19in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->currency }}
                        </span>
                    </div>
                    {{-- Charges in Dest. Currency  --}}
                    <div style="position:absolute;top:9.78in;left:1.35in;width:0.99in;line-height:0.13in;font-size:10pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">

                        </span>
                    </div>
                    {{-- Other Charges   --}}
                    <div style="position:absolute;top:7.79in;left:2.47in;width:5.27in;line-height:0.13in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            @foreach($other['data'] AS $ot)
                            {{ $ot->oc_description . ': $' . number_format($ot->oc_value, 2) .' ('. (($ot->oc_due == 1) ? 'C' : 'A') .')' }} <br>
                            @endforeach
                        </span>
                    </div>
					<div style="position:absolute;top:8.83in;left:2.47in;width:5.27in;line-height:0.13in;font-size:6pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            SHIPPER HEREBY CONSENTS TO A SEARCH OR INSPECTION OF THE CARGO.
                        </span>
                    </div>
                    <div style="position:absolute;top:9.6in;left:2.47in;width:2in;line-height:0.15in;font-size:14pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ (env('APP_CLIENT') != 'jexpress') ? date('m-d-y', strtotime($data->fecha_vuelo1)) : date('m-d-y', strtotime($data->fecha_vuelo1)) }}
                        </span>
                    </div>
                    <div style="position:absolute;top:9.37in;left:4.7in;width:2.8in;font-size:8pt;">
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->nombre_carrier }}
                        </span>
                        <br>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->telefono_carrier }} / {{ $data->direccion_carrier }}
                        </span>
                        <br>
                        <span style="font-style:normal;font-weight:normal;font-family:Helvetica;color:#000000">
                            {{ $data->ciudad_carrier }}, {{ $data->zip_carrier }}
                        </span>
                    </div>
                    {{-- NUMERO MASTER --}}
                    <div style="position:absolute;top:9.91in;left:5.1in;width:2.8in;font-size:15pt;">
                        <span style="font-style:normal;font-weight:bold;font-family:Helvetica;color:#000000">
                            {{ $data->codigo_aerolinea .'-'. substr($data->num_master,3) }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
        @endfor
    </body>
</html>
