<?php

namespace App\Traits;

use Exception;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Consignee;
use App\Tracking;
use App\Cliente;
use App\DocumentoDetalle;

trait sendEmailAlerts
{
  public function verifySendEmail($alert = false, $id_plantilla = null, $consignee_id = null, $tracking = null)
  {
    try {
      if($alert){
        /* DATOS DE LA AGENCIA */
        $objAgencia = $this->getDataAgenciaById(Auth::user()->agencia_id);
        /* DATOS DE LA PLANTILLA */
        $plantilla = $this->getDataEmailPlantillaById($id_plantilla);
        // DATOS DEL Consignee
        $consignee = Consignee::findOrFail($consignee_id);

        if (isset($consignee->correo) and $consignee->correo != '') {
            if (filter_var(trim($consignee->correo), FILTER_VALIDATE_EMAIL)) {
                /* ENVIO DE EMAIL REPLACEMENT($id_documento, $objAgencia, $objDocumento, $objShipper, $consignee, $datosEnvio, $trakcings)*/
                $t = explode(',', $tracking);
                $datosEnvio = '';
                $thead = '<table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="0" style="border-collapse:collapse"><thead><tr><th style="width:40%;"><div style="font-size: 15px;">TRACKING</div></th><th><div style="font-size: 15px;">CONTENIDO</div></th></tr></thead>';
                $tbody = '<tbody>';
                $datosEnvio .= $thead . ' ' . $tbody;
                foreach ($t as $key => $value) {
                  $tr = Tracking::where('codigo', $value)->first();
                  $datosEnvio .= '<tr><td style="text-align: left;font-size: 14px;"><div style="margin-left: 3px;">' . $value . '<br><small>Recibido: ' . $tr->created_at . '</small></div></td><td style="text-align: left;font-size: 14px;"><div style="margin-left: 3px;">' . $tr->contenido . '</div></td></tr>';
                }
                $datosEnvio .= '</tbody></table>';
                $replacements = $this->replacements(0, $objAgencia, null, null, $consignee, $datosEnvio, $tracking);

                $cuerpo_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->mensaje);
                $asunto_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->subject);

                $from_self = array(
                    'address' => $objAgencia->email,
                    'name'    => $objAgencia->descripcion,
                );
                $moreUsers = [];
                if($consignee->email_cc != ''){
                  $moreUsers = explode(',', $consignee->email_cc);
                }
                // VERIFICAR SI SE ENVIA CORREO AL CLIENTE ASOCIADO AL CONSIGNEE
                $evenMoreUsers = [];
                $cliente = null;
                if ($consignee->cliente_id !== null) {
                  $cliente = Cliente::findOrFail($consignee->cliente_id);
                }
                if ($consignee->cliente_id !== null and $cliente !== null) {
                  if ($consignee->notify_client == 1 || $cliente->email_bcc == 1) {
                    if($cliente->email != ''){
                      $evenMoreUsers = trim($cliente->email);
                    }
                  }
                }

                $this->AddToLog('Email enviado verifySendEmail()');

                return Mail::to(trim($consignee->correo))
                ->cc($moreUsers)
                ->bcc($evenMoreUsers)
                ->send(new \App\Mail\BodegaRecibido($cuerpo_correo, $from_self, $asunto_correo));
            }else{
              return array(
                "error"  => 'El email "'. $consignee->correo .'" no es valido',
                "code"   => 600
              );
            }
        }else{
          return array(
            "error"  => 'El consignee no tiene email registrado',
            "code"   => 600
          );
        }
      }
    } catch (Exception $e) {
      return array(
          "error"  => $e,
          "code"   => 600
      );
    }
  }
}
