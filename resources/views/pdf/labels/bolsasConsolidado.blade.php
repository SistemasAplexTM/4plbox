<html>
    <style>
        #tableContainer{
            font-family: sans-serif;
            /*margin-bottom: 27px;*/
        }
        #mvcIcon, #mvcMain{
            display: none;
        }
        #tipoCarga{
            font-size: 60px;
            font-weight: bold;
        }
        #imgLogo{
            width:100%;
            height: 350px;
        }
        #master{
            font-size: 80px;
            font-weight: bold;
        }
        #bolsas{
            font-size: 70px;
            font-weight: bold;
        }
        #ciudadDestino{
            font-size: 30px;
            width: 100%;
            font-weight: bold;
        }
        #agencia{
            font-size: 60px;
            font-weight: bold;
        }
        #divImg{
            padding: 2px;
            /*height: 350px;*/
            /*background-color: #000;*/
        }
        #pieza{
            padding: 10px;
            font-size: 60px;
            font-weight: bold;
        }
    </style>
    <?php
//     echo count($data);
// echo '<pre>';
// print_r($data[0]);
// echo '</pre>';
// exit();
    $contRegistros = 0;
    $toalRegistros = count($data);
    ?>
    <body>
        <?php if (count($data) > 0): ?>
            <?php for ($i = 0; $i <= (count($data) -1); $i++): ?>
                <?php $contRegistros++; ?>
                <table width="100%;" border="1" cellspacing="0" cellpadding="0" id="tableContainer" style="page-break-after:<?php if ($contRegistros === (1 * count($data))): ?>avoid;<?php else: ?>always<?php endif; ?>">
                    <tr>
                        <td style="width: 70%;">
                            <img class="" id="" style="height: 300px; width: 550px;" src="{{ asset('storage/') }}/{{ ((isset($data[$i]->logo) and $data[$i]->logo != '') ? $data[$i]->logo : 'logo.png') }}" />
                        </td>
                        <td align="CENTER">
                            <span id="tipoCarga">Pieza <div>{{ $i + 1 . ' / ' . count($data) }}</div></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="CENTER" colspan="2">
                            <span id="master">{{ ($data[$i]->num_master != '') ? $data[$i]->num_master : '&nbsp;'  }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span id="bolsas"><span id="pieza">COURIER</span></span>
                        </td>
                        <td align="CENTER">
                            <div id="ciudadDestino">{{ $data[$i]->ciudad . ' ' . $data[$i]->pais }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td align="CENTER" colspan="2">
                            <span id="agencia">{{ $data[$i]->agencia }}</span>
                        </td>
                    </tr>
                </table>
            <?php endfor; ?>
        <?php else: ?>
          <h3>No hay datos</h3>
        <?php endif; ?>
    </body>
</html>
