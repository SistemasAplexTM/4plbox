<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <title>
            Label AWB
        </title>
        <style type="text/css">
            body {
			  margin:0px;
			  font-family:Tahoma, Geneva, sans-serif;
			  font-size:12px;
			}
			.titulos {
			    font-size:9px;
			    font-weight:bold;
			}
			.titulo_grande {
			  font-family: "Arial Black", Gadget, sans-serif;
			  font-size:18px;
			  font-weight: bold;

			}
			.border_bottom {
			  border-bottom:solid 1px #000000;
			}
        </style>
    </head>
    <body>
    	<?php 
            $cont = 0;
            $toalRegistros = count($detalle);
            $toalRegistros = 0;
            $contRegistros = 0;
            $piezas = 0;
        ?>
        @foreach ($detalle as $value)
            <?php $piezas += $value->piezas ?>
        @endforeach
    	@foreach ($detalle as $value)
            @for($i = 1; $i <= $value->piezas; $i++)
            <?php $contRegistros++ ?>
	        <table border="0" cellpadding="0" cellspacing="0" height="288" width="216" style="page-break-after:{{ ($contRegistros === $piezas) ? 'avoid' : 'always' }}">
	            <tr>
	                <td colspan="4" style="text-align:center">
	                    <img src="{{ asset('storage/') }}/{{ ((isset($documento->agencia_logo) and $documento->agencia_logo != '') ? trim($documento->agencia_logo) : 'logo.png') }}"  style="height: 65px;"/>
	                </td>
	            </tr>
	            <tr>
	                <td class="titulo_grande border_bottom" colspan="3" width="50%">
	                    GUIA AWB:
	                </td>
	                <td class="titulo_grande border_bottom">
	                    {{ $value->codigo }}
	                </td>
	            </tr>
	            <tr>
	                <td class="border_bottom" colspan="4">
	                    <div class="titulos">
	                        Remitente
	                    </div>
	                    <div>
	                        <strong>{{ $value->ship_nomfull }}</strong>
	                    </div>
	                    <div>{{ $value->ship_dir }}</div>
	                    <div>{{ $value->ship_tel }}</div>
	                </td>
	            </tr>
	            <tr>
	                <td class="border_bottom" colspan="4">
	                    <div class="titulos">
	                        Consignatario
	                    </div>
	                    <div>
	                        <strong>{{ $value->cons_nomfull }}</strong>
	                    </div>
	                    <div>{{ $value->cons_dir }}</div>
	                    <div>{{ $value->cons_tel }}</div>
	                    <div>{{ $value->cons_ciudad }}</div>
	                </td>
	            </tr>
	            <tr>
	                <td class="border_bottom" colspan="4">
	                    <div class="titulos">
	                        Descripción - Contenido
	                    </div>
	                    <div>
	                        {{ $value->contenido }}
	                    </div>
	                </td>
	            </tr>
	            <tr>
	                <td class=" border_bottom" width="25%">
	                    WR
	                </td>
	                <td class="border_bottom" style="text-align:center;" width="25%">
	                    <strong>{{ $value->num_warehouse }}</strong>
	                </td>
	                <td class=" border_bottom" style="text-align:center;" width="25%">
	                    FECHA:
	                </td>
	                <td class="border_bottom" style="text-align:center;" width="25%">
	                    {{ $value->created_at->toFormattedDateString() }}
	                </td>
	            </tr>
	            <tr>
	                <td class="border_bottom" colspan="4">
	                    <div style="text-align:center">
	                       <strong> COLOMBIANA DE CARGA</strong>
	                    </div>
	                    <div style="text-align:center">
	                       <strong> Su mejor opción</strong>
	                    </div>
	                </td>
	            </tr>
	        </table>
        @endfor
        @endforeach
    </body>
</html>
