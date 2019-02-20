<?php

namespace App\Traits;

use Exception;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Consignee;
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
              $replacements = $this->replacements(0, $objAgencia, null, null, $condignee, null, $tracking);

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
                  ->send(new \App\Mail\WarehouseEmail($cuerpo_correo, false, $from_self, $asunto_correo));
          }
      }
    }
  }
}
