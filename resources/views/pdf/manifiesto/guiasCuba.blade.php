<style>
  *{
    font-family: Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif"
  }
  .bill{
    width: 100%;
  }
  .content{
    width: 100%;
  }
  .title{
    font-size: 8px;
    padding-bottom: 5px;
    padding-top: 2px;
    padding-left: 2px
  }
  .b-top{
    border-top: 1px solid #000000;
  }
  .b-bottom{
    border-bottom: 1px solid #000000;
  }
  .b-left{
    border-left: 1px solid #000000;
  }
  .b-right{
    border-right: 1px solid #000000;
  }
  .p-left{
    padding-left: 10px;
  }
  .var{
    font-size: 14px;
  font-weight: bold;
  }
  .detail .title{
    padding: 5px;
  }
  pre {
    margin: 0;
  }
  .det{
    font-size: 17px;
    margin-left: 5px;
    margin-right: 5px;
    font-weight: bold;
  }
</style>
<?php
$cont = 0;
$contRegistros = 0;
$toalRegistros = count($detalleConsolidado);
// echo '<pre>';
// print_r($documento);
// print_r($detalleConsolidado);
// echo '<pre>';
// exit();
?>

@if($detalleConsolidado != '')
    @foreach ($detalleConsolidado as $value)
        <?php
        $shipper_json = '';
        $consignee_json = '';
        $cont++;
        $contRegistros++;
        if($value->shipper_json != ''){
            $shipper_json = json_decode($value->shipper_json);
        }
        if($value->consignee_json != ''){
            $consignee_json = json_decode($value->consignee_json);
        }
        ?>
        @if ($cont != 1)
            {{-- <div id="spaceTable">&nbsp;</div> --}}
        @endif
        <table border="0" id="" width="100%" cellspacing="0" cellpadding="0"  <?php if ($cont === 1): ?>
        style="page-break-after:<?php if ($contRegistros === $toalRegistros): ?>avoid;margin-bottom: 0px;<?php else: ?>always<?php endif; ?>"
        <?php
          $cont = 0;
        endif;
        ?>>
          <thead>
            <tr>
              <td style="padding-left: 5px;font-size: 25px"><img src="{{ '/storage/' . $documento->agencia_logo }}" alt="" height="50" style="margin: 0 auto"></td>
              <td style="text-align: right;padding-right: 5px;font-size: 25px">BILL OF LADING</td>
            </tr>
          </thead>
          <tbody style="border: 1px solid #030303;">
            <tr>
              <td colspan="2">
                <table class="content" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="top" style="width: 55%" class="b-top b-right">
                      <table width="100%" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td colspan="2" class="title">2. EXPORTER (Principal or seller -licensee and address including ZIP Code )</td>
                        </tr>
                        <tr>
                          <td class="p-left">
                            <div class="var">{{ $value->ship_nomfull }}</div>
                          </td>
                        </tr>
                        <tr>
                          <td class="p-left">
                            <div class="var">{{ $value->ship_dir }}</div>
                          </td>
                        </tr>
                        <tr>
                          <td class="p-left" rowspan="2" style="width: 70%;">
                            <div class="var">{{ $value->ship_ciudad . '-'. $value->ship_depto_ab }}</div>
                            <div class="var">{{ $value->ship_tel }}</div>
                          </td>
                        </tr>
                        <tr>
                          <td style="height: 30px"></td>
                          <td class="b-top b-left" valign="top">
                            <div style="font-size: 10px;margin-left: 2px">ZIP CO'DE</div>
                            <div class="var">{{ $value->ship_zip }}</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td valign="top" class="b-top">
                      <table width="100%" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td valign="top" class="title b-right" style="width: 50%;padding-left: 2px;height: 35px">5. DOCUMENT NUMBER
                            <div class="var"></div>
                          </td>
                          <td valign="top" class="title" style="padding-left: 2px">5a. B/L NUMBER
                            <div class="" style="font-size:17px;font-weight:bold">{{ $value->num_guia }}</div>
                          </td>
                        </tr>
                        <tr>
                          <td valign="top" colspan="2" class="title b-top">6. EXPORT REFERENCES</td>
                        </tr>
                        <tr>
                          <td valign="top" colspan="2" class="p-left" style="font-size:17px;font-weight:bold">{{ $value->num_guia }}</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td valign="top" style="width: 55%" class="b-top b-right">
                      <table width="100%" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td colspan="2" class="title">3. CONSIGNED TO</td>
                        </tr>
                        <tr>
                          <td colspan="" class="p-left">
                            <div class="var">{{ $value->cons_nomfull }}</div>
                            <div class="var">{{ $value->cons_dir }}</div>
                            <div class="var">{{ $value->cons_ciudad . '-'. $value->cons_depto_ab }}</div>
                            <div class="var">{{ $value->cons_tel }}</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td valign="top" class="b-top">
                      <table width="100%" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td valign="top" class="title" style="padding-left: 2px;height: 55px">
                            7. FORWARDING AGENT (Name and address - references )
                            <div class="var">
                              <div class="var">{{ $documento->agencia }}</div>
                              <div class="var">{{ $documento->agencia_dir }}</div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td valign="top" colspan="2" class="title b-top" style="padding-left: 2px;">
                            8. POINT (STATE) OF ORIGIN OR FTZ NUMBER
                            <div class="var">&nbsp;</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td valign="top" style="width: 55%" class="b-top b-right">
                      <table width="100%" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td colspan="2" class="title">4. NOTIFY PARTY /INTERMEDIATE CONSIGNEE (Name and address )</td>
                        </tr>
                        <tr>
                          <td valign="top" colspan="2" class="p-left" style="height: 70px">
                            <div class="var">{{ $documento->trans_nom }}</div>
                            <div class="var">{{ $documento->trans_dir }}</div>
                            <div class="var">{{ $documento->trans_tel }}</div>
                          </td>
                        </tr>
                        <tr>
                          <td class="title b-top b-right" style="width: 60%;">12. PRE-CARRIAGE BY</td>
                          <td class="title b-top">13. PLACE OF RECEIPT BY PRE -CARRIER</td>
                        </tr>
                        <tr>
                          <td valign="top" class="p-left b-right">
                            <div class="var">carriage</div>
                          </td>
                          <td valign="top" class="p-left">
                            <div class="var">place</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td valign="top" class="b-top">
                      <table width="100%" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td valign="top" class="title" style="padding-left: 2px;height: 55px">
                            9. DOMESTIC ROUTING/EXPORT INSTRUCTIONS
                            <div class="var"><pre>domestic</pre></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td valign="top" style="width: 55%" class="b-right">
                      <table width="100%" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td class="title b-top b-right" style="width: 60%;">14. EXPORTING CARRIER</td>
                          <td class="title b-top">15. PORT OF LOADING /EXPORT</td>
                        </tr>
                        <tr>
                          <td valign="top" class="p-left b-right">
                            <div class="var">expor carrier</div>
                          </td>
                          <td valign="top" class="p-left">
                            <div class="var">port loading</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td valign="top" class="b-top">
                      <table width="100%" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td valign="top" class="title" style="padding-left: 2px;">10. LOADING PIER /TERMINAL</td>
                        </tr>
                        <tr>
                          <td valign="top" class="p-left">
                            <div class="var">load pier</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td valign="top" style="width: 55%" class="b-right">
                      <table width="100%" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td class="title b-top b-right" style="width: 60%;">16. FOREIGN PORT OF UNLOADING (Vessel and air only )</td>
                          <td class="title b-top">17. PLACE OF DELIVERY BY ON -CARRIER</td>
                        </tr>
                        <tr>
                          <td valign="top" class="p-left b-right">
                            <div class="var">foreign_port</div>
                          </td>
                          <td valign="top" class="p-left">
                            <div class="var">placce_delivery</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td valign="top" class="b-top">
                      <table width="100%" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td class="title b-right" style="width: 50%;">11. TYPE OF MOVE</td>
                          <td class="title">11a. CONTAINERIZED (Vessel only )</td>
                        </tr>
                        <tr>
                          <td valign="top" class="p-left b-right">
                            <div class="var">{{ '$data->type_move' }}</div>
                          </td>
                          <td valign="top" class="p-left">
                            <div class="var" style="width: 50%;float: left">Yes &nbsp;X</div>
                            <div class="var" style="width: 50%;float: left">No  &nbsp;</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="b-top b-bottom">
                      <table width="100%" cellspacing="0" cellpadding="0" class="detail">
                        <tr style="text-align: center;">
                          <td class="title b-right" style="width: 100px;">MARKS AND NUMBERS <br> (18)</td>
                          <td class="title b-right" style="width: 70px;">NUMBER OF PACKAGES <br> (19)</td>
                          <td class="title b-right" style="">DESCRIPTION OF COMMODITIES <br> (20)</td>
                          <td class="title b-right" style="width: 90px;">GROSS WEIGHT <br> (kilos) (21)</td>
                          <td class="title" style="width: 90px;">MEASUREMENT <br> (22)</td>
                        </tr>
                        {{-- @if(count($detalle) > 0)
                          @foreach($detalle as $dt) --}}
                            <tr>
                              <td valign="top" class="b-top b-right" style="height: 200px">
                                <div class="var"><pre></pre></div>
                              </td>
                              <td valign="top" class="b-top b-right">
                                <div class="det">{{ $value->piezas }} PCS</div>
                              </td>
                              <td valign="top" class="b-top b-right">
                                <div class="det">{{ $value->contenido2 }}</div>
                              </td>
                              <td valign="top" class="b-top b-right">
                                <div class="det" style="text-align: left;">{{ number_format($value->peso2 * 0.453592, 2) }} KLS<br>{{ $value->peso2 }} LBS</div>
                              </td>
                              <td valign="top" class="b-top">
                                <div class="det" style="text-align: left;">{{ ceil(number_format($value->volumen * 166 / 1728)) }} FT<br>{{ ceil(number_format(($value->volumen * 166 / 1728) / 35.315)) }} Mt3</div>
                              </td>
                            </tr>
                          {{-- @endforeach
                        @endif --}}
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="title">Carrier has a policy against payment , solicitation, or receipt of any rebate , directly or indirectly , which would be unlawful under the United States Shipping Act , 1984 as amended .</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="title">DECLARED VALUE __________________________ READ CLAUSE 29 HEREOF CONCERNING EXTRA FREIGHT AND CARRIER 'S LIMITATION DECLARED VALUE OF LIABILITY .</td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <table cellspacing="0" cellpadding="0" style="width: 100%">
                        <tr>
                          <td width="50%" class="b-top b-right" style="padding: 6px 6px 0px 0px" valign="top">
                            <table cellspacing="0" cellpadding="0" style="width: 100%">
                              <tr>
                                <td colspan="3" style="font-size: 11px;font-weight: bold;text-align: center;">FREIGHT RATES, CHARGES, WEIGHTS AND/OR MEASUREMENTS</td>
                              </tr>
                              <tr>
                                <td style="font-size: 11px;text-align: center;" class="b-right">SUBJECT TO CORRECTION</td>
                                <td style="font-size: 11px;text-align: center;" class="b-top b-right">PREPAID</td>
                                <td style="font-size: 11px;text-align: center;" class="b-top b-right">COLLECT</td>
                              </tr>
                              <?php $total_pp = 0; ?>
                              <?php $total_cll = 0; ?>
                              {{-- @if(count($other) > 0)
                                @foreach($other as $ot) --}}
                                  <tr>
                                    <td valign="top"  class="b-top b-right" style="height: 190px"></td>
                                    <td valign="top"  class="b-top b-right"></td>
                                    <td valign="top"  class="b-top b-right"></td>
                                  </tr>
                                  <?php $total_pp += 0 ?>
                                  <?php $total_cll += 0; ?>
                                {{-- @endforeach
                              @endif --}}
                              <tr>
                                <td valign="top"  class="b-top b-right" style="padding: 10px;text-align: right">GRAND TOTAL :</td>
                                <td valign="top"  class="b-top b-right"></td>
                                <td valign="top"  class="b-top b-right"></td>
                              </tr>
                            </table>
                          </td>
                          <td valign="top" width="50%" class="b-top" style="padding: 10px 0px 0px 6px">
                            <table cellspacing="0" cellpadding="0" style="width: 100%">
                              <tr>
                                <td valign="top" colspan="2" style="font-size: 8px;font-weight: bold;text-align: center;text-align: justify">Received by the Carrier for shipment by ocean vessel between port of loading and port of
                                discharge , and for arrangement or procurement of pre -carriage from place of receipt and on -
                                carriage to place of delivery , where stated above , the goods as specified above in apparent
                                good order and condition unless otherwise stated . The goods to be delivered at the above
                                mentioned port of discharge or place of delivery , whichever is applicable , subject always to the
                                exceptions , limitations, conditions and liberties set out on the reverse side hereof , to which the
                                Shipper and /or Consignee agree to accepting this Bill of Lading .
                                IN WITNESS WHEREOF three (3) original Bills of Lading have been signed , not otherwise
                                stated above , one of which being accomplished the others shall be void .</td>
                              </tr>
                              <tr>
                                <td colspan="2" style="padding-top: 10px;">
                                  <div style="width: 20%;float: left;font-size:12px;">DATED AT</div>
                                  <div style="width: 80%;float: left;border-bottom: 1px solid #000000;">&nbsp;</div>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2" style="padding-top: 5px;">
                                  <div style="width: 5%;float: left;font-size:12px;">BY</div>
                                  <div style="width: 95%;float: left;border-bottom: 1px solid #000000;">
                                    <div style="width: 100%;text-align: center;font-size: 20px;font-weight:bold;">{{ $documento->agencia_ciudad }}</div>
                                  </div>
                                  <div style="font-size: 12px;text-align: center;">AGENT FOR THE CARRIER</div>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2" style="padding-top: 5px;">
                                  <div style="width: 35%;float: left;font-weight:bold;">{{ date("m", strtotime($documento->created_at)) }}</div>
                                  <div style="width: 30%;float: left;text-align: center;font-weight:bold;">{{ date("d", strtotime($documento->created_at)) }}</div>
                                  <div style="width: 35%;float: left;text-align: right;font-weight:bold;">{{ date("Y", strtotime($documento->created_at)) }}</div>
                                </td>
                              </tr>
                              <tr><td colspan="2"><div style="border-bottom: 1px solid #000000;"></div></td></tr>
                              <tr>
                                <td colspan="2" style="padding-top: 5px;">
                                  <div style="width: 35%;float: left;font-size:12px;">MO.</div>
                                  <div style="width: 30%;float: left;text-align: center;font-size:12px;">DAY</div>
                                  <div style="width: 35%;float: left;text-align: right;font-size:12px;">YEAR</div>
                                </td>
                              </tr>
                              <tr>
                                <td style="width: 50%;">&nbsp;</td>
                                <td class="title b-top b-left">B/L No.</td>
                              </tr>
                              <tr>
                                <td style="width: 50%;">&nbsp;</td>
                                <td class="b-left" style="text-align: right;padding-right: 5px;font-weight: bold;font-size:20px;">{{ $value->num_guia }}</td>
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
          </tbody>
        </table>
    @endforeach
@else
    <div id="noDatos">@lang('pdfs.there_is_no_data')</div>
@endif

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
