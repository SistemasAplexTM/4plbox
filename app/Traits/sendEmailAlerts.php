<?php

namespace App\Traits;

use Exception;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Consignee;
use App\Tracking;
use App\DocumentoDetalle;

trait sendEmailAlerts
{
  public function verifySendEmail($alert = false, $id_plantilla = null, $consignee_id = null, $tracking = null)
  {
    if($alert){
      /* DATOS DE LA AGENCIA */
      $objAgencia = $this->getDataAgenciaById(Auth::user()->agencia_id);
      /* DATOS DE LA PLANTILLA */
      $plantilla = $this->getDataEmailPlantillaById($id_plantilla);
      // DATOS DEL Consignee
      $condignee = Consignee::findOrFail($consignee_id);

      if (isset($condignee->correo) and $condignee->correo != '') {
          if (filter_var(trim($condignee->correo), FILTER_VALIDATE_EMAIL)) {
              /* ENVIO DE EMAIL REPLACEMENT($id_documento, $objAgencia, $objDocumento, $objShipper, $condignee, $datosEnvio, $trakcings)*/
              $t = explode(',', $tracking);
              $datosEnvio = '';
              $thead = '<table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="0" style="border-collapse:collapse"><thead><tr><th style="width:40%;"><div style="font-size: 15px;">TRACKING</div></th><th><div style="font-size: 15px;">CONTENIDO</div></th></tr></thead>';
              $tbody = '<tbody>';
              $datosEnvio .= $thead . ' ' . $tbody;
              foreach ($t as $key => $value) {
                $tr = Tracking::where('codigo', $value)->first();
                $datosEnvio .= '<tr><td style="text-align: left;font-size: 14px;"><div style="margin-left: 3px;">' . $value . '</div></td><td style="text-align: left;font-size: 14px;"><div style="margin-left: 3px;">' . $tr->contenido . '</div></td></tr>';
              }
              $datosEnvio .= '</tbody></table>';
              $replacements = $this->replacements(0, $objAgencia, null, null, $condignee, $datosEnvio, $tracking);

              $cuerpo_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->mensaje);
              $asunto_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->subject);

              $from_self = array(
                  'address' => $objAgencia->email,
                  'name'    => $objAgencia->descripcion,
              );

              // $moreUsers     = $condignee->correo;
              // $evenMoreUsers = $condignee->correo;


              $this->AddToLog('Email enviado verifySendEmail()');
              return Mail::to(trim($condignee->correo))
              // ->cc($moreUsers)
              // ->bcc($evenMoreUsers)
                  ->send(new \App\Mail\BodegaRecibido($cuerpo_correo, $from_self, $asunto_correo));
          }
      }
    }
  }
}
