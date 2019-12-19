<?php

namespace App\Http\Controllers;

include_once(app_path() . '\WebClientPrint\WebClientPrint.php');
use Auth;
use JavaScript;
use App\Agencia;
use App\Shipper;
use App\Consignee;
use App\AplexConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Helpers\LogActivity as Logs;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function assignPermissionsJavascript($table = null)
    {
        /* PERMISOS PARA JAVASCRIPT */
        JavaScript::put([
            'permission_store'                    => ((Auth::user()->can($table . '.store')) ? true : false),
            'permission_create'                   => ((Auth::user()->can($table . '.create')) ? true : false),
            'permission_edit'                     => ((Auth::user()->can($table . '.edit')) ? true : false),
            'permission_update'                   => ((Auth::user()->can($table . '.update')) ? true : false),
            'permission_destroy'                  => ((Auth::user()->can($table . '.destroy')) ? true : false),
            'permission_delete'                   => ((Auth::user()->can($table . '.delete')) ? true : false),
            'permission_ajaxCreate'               => ((Auth::user()->can($table . '.ajaxCreate')) ? true : false),
            'permission_deleteDetailConsolidado'  => ((Auth::user()->can($table . '.deleteDetailConsolidado')) ? true : false),
            'permission_insertDetail'             => ((Auth::user()->can($table . '.insertDetail')) ? true : false),
            'permission_editDetail'               => ((Auth::user()->can($table . '.editDetail')) ? true : false),
            'permission_additionalCharguesDelete' => ((Auth::user()->can($table . '.additionalCharguesDelete')) ? true : false),
            'permission_updateDetailConsolidado'  => ((Auth::user()->can($table . '.updateDetailConsolidado')) ? true : false),
            'permission_ajaxCreateNota'           => ((Auth::user()->can($table . '.ajaxCreateNota')) ? true : false),
            'permission_deleteNota'               => ((Auth::user()->can($table . '.deleteNota')) ? true : false),
            'permission_removerGuiaAgrupada'      => ((Auth::user()->can($table . '.removerGuiaAgrupada')) ? true : false),
            'permission_pdfContrato'              => ((Auth::user()->can($table . '.pdfContrato')) ? true : false),
            'permission_pdfTsa'                   => ((Auth::user()->can($table . '.pdfTsa')) ? true : false),
            'permission_pdf'                      => ((Auth::user()->can($table . '.pdf')) ? true : false),
            'permission_pdfLabel'                 => ((Auth::user()->can($table . '.pdfLabel')) ? true : false),
            'agency_id'                           => Auth::user()->agencia_id
        ]);
    }

    public function AddToLog($activity = null)
    {
        // \LogActivity::addToLog($activity);
        Logs::addToLog($activity);
    }

    public function logActivity()
    {
        // $logs = \LogActivity::logActivityLists();
        $logs = Logs::logActivityLists();
        return $logs;
    }

    public function getDataConsigneeOrShipperById($id, $nameTable)
    {
        /* DATOS DEL CONSIGNNEE O SHIPPER  */
        $table       = $nameTable;
        return $user = DB::table($table)
            ->join('localizacion', $table . '.localizacion_id', '=', 'localizacion.id')
            ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
            ->join('pais', 'deptos.pais_id', '=', 'pais.id')
            ->join('agencia', $table . '.agencia_id', '=', 'agencia.id')
            ->leftjoin('tipo_identificacion', $table . '.tipo_identificacion_id', '=', 'tipo_identificacion.id')
            ->select(DB::raw("CONCAT(" . $table . ".primer_nombre,' ', " . $table . ".segundo_nombre,' ', " . $table . ".primer_apellido,' ', " . $table . ".segundo_apellido) as full_name"), $table . '.*', 'localizacion.nombre as ciudad', 'localizacion.id as ciudad_id', 'deptos.descripcion as depto', 'deptos.id as estado_id', 'pais.descripcion as pais', 'pais.id as pais_id', 'agencia.descripcion as agencia', 'tipo_identificacion.descripcion as identificacion')
            ->where([
                [$table . '.id', '=', $id],
                [$table . '.deleted_at', '=', null],
            ])->first();
    }

    public function getDataEmailPlantillaById($id)
    {
        /* PLANTILLA */
        return $plantilla = DB::table('plantillas_correo AS a')
            ->select([
                'a.mensaje',
                'a.subject',
                'a.nombre',
                'a.descripcion_plantilla',
                'a.otros_destinatarios',
                'a.enviar_archivo'
            ])->where([
                ['a.id', $id],
                ['a.deleted_at', '=', null],
            ])->first();
    }

    public function getDataAgenciaById($id)
    {
        /* AGENCIA */
        return $objAgencia = DB::table('agencia AS a')
            ->join('localizacion AS b', 'b.id', 'a.localizacion_id')
            ->join('deptos AS c', 'c.id', 'b.deptos_id')
            ->join('pais AS d', 'd.id', 'c.pais_id')
            ->select([
                'a.*',
                'b.nombre AS ciudad',
                'c.descripcion AS depto',
                'd.descripcion AS pais',
            ])->where([
                ['a.id', $id],
                ['a.deleted_at', '=', null],
            ])->first();
    }

    public function getNameAgencia()
    {
        if (Auth::check()) {
            $agencia_data = DB::table('users as a')
                ->join('agencia as b', 'a.agencia_id', 'b.id')
                ->select(['b.id', 'b.descripcion', 'b.logo'])
                ->where('a.id', Auth::user()->id)
                ->first();
            session(['agencia_name_global' => $agencia_data->descripcion]);
            return $agencia_data;
        } else {
            return false;
        }
    }

    public function validateRelationShipperConsignee($shipper_id, $consig_id)
    {
        /*VALIDO SI EXISTE EN LA TABLA PIBOT 'shipper_consignee' LA RELACION, SI NO EXISTE LA CREAMOS*/
        $idPibot = DB::table('shipper_consignee')->select('id')
            ->where([['shipper_id', $shipper_id], ['consignee_id', $consig_id], ['deleted_at', null]])
            ->first();

        if ($idPibot === false || $idPibot == '') {
            DB::table('shipper_consignee')->insert([
                ['shipper_id' => $shipper_id, 'consignee_id' => $consig_id, 'created_at' => date('Y-m-d H:i:s')],
            ]);
        }
        return true;
    }

    
    public function getNamesAndFullNames($name_complet)
    {
        $nomFull = array();
        /*separo los nombres y apellidos que estan separados con un guion(-) en espacios*/
        $arrayDatosShip = explode('-', $name_complet);
        /*    luego separo los nombres que vienen separados por un espacio en blanco ' ' y los apellidos igualmente
        y los almaceno en arreglos con posiciones de: $nombresShip[0]=primer nombre y $nombresShip[1]=segundo nombre igual para los apellidos*/

        /* arreglo donde se guardan las "palabras" de nombres y apellidos */
        $names = array();
        $ape   = array();
        if (count($arrayDatosShip) > 1) {
            $nombresShip   = explode(' ', trim($arrayDatosShip[0]));
            $apellidosShip = (isset($arrayDatosShip[1])) ? explode(' ', trim($arrayDatosShip[1])) : false;
            /* palabras de apellidos (y nombres) compuetos */
            $special_tokens = array('da', 'de', 'del', 'la', 'las', 'los', 'mac', 'mc', 'van', 'von', 'y', 'i', 'san', 'santa');
            //SEPARAR NOMBRES
            $prevn = "";
            foreach ($nombresShip as $token) {
                $_token = strtolower($token);
                if (in_array($_token, $special_tokens)) {
                    $prevn .= "$token ";
                } else {
                    $names[] = $prevn . $token;
                    $prevn   = "";
                }
            }
            $nomFull[0] = $names;
            //SEPARAR APELLIDOS
            $prev = "";
            if ($apellidosShip) {
                foreach ($apellidosShip as $token) {
                    $_token = strtolower($token);
                    if (in_array($_token, $special_tokens)) {
                        $prev .= "$token ";
                    } else {
                        $ape[] = $prev . $token;
                        $prev  = "";
                    }
                }
            }
            $nomFull[1] = $ape;
            return $nomFull;
        } else {
            /*NOMBRES*/
            $names[0]   = $name_complet;
            $nomFull[0] = $names;
            /*APELLIDOS*/
            $nomFull[1] = $ape;
            return $nomFull;
        }
    }

    public function replacements($id, $objAgencia, $objWarehouse = null, $objShipper = null, $objConsignee = null, $datosEnvio = null, $tracking = null)
    {
        $replacements = array(
            //URL del sistema
            '({url_principal})'   => url('/'),
            // datos del documento
            '({id})'              => $id,
            // '({num_guia})'        => ($objWarehouse) ? $objWarehouse->num_guia : '',
            '({num_warehouse})'   => ($objWarehouse) ? $objWarehouse->num_warehouse : '',
            //Datos Shipper
            '({nom_shipper})'     => ($objShipper) ? $objShipper->nombre_full : '',
            //Datos consignee
            '({agencia})'         => ($objConsignee) ? $objConsignee->nombre_full : '',
            '({nom_consignee})'   => ($objConsignee) ? $objConsignee->nombre_full : '',
            '({dir_consignee})'   => ($objConsignee) ? $objConsignee->direccion : '',
            '({dir2_consignee})'  => ($objConsignee) ? $objConsignee->direccion2 : '',
            '({ciu_consignee})'   => ($objConsignee) ? $objConsignee->ciudad : '',
            '({depto_consignee})' => ($objConsignee) ? $objConsignee->depto : '',
            '({zip_consignee})'   => ($objConsignee) ? $objConsignee->zip : '',
            '({pais_consignee})'  => ($objConsignee) ? $objConsignee->pais : '',
            '({pass_consignee})'  => ($objConsignee) ? $objConsignee->celular : '',
            '({email_consignee})' => ($objConsignee) ? $objConsignee->correo : '',
            '({tel_consignee})'   => ($objConsignee) ? $objConsignee->telefono : '----------',
            '({cel_consignee})'   => ($objConsignee) ? $objConsignee->celular : '',
            '({pobox_consignee})' => ($objConsignee) ? $objConsignee->po_box : '',
            '({user_consignee})'  => ($objConsignee) ? $objConsignee->correo : '',
            //Datos Guias
            '({flete_impuesto})'  => ($objWarehouse) ? (($objWarehouse->valor_declarado * $objWarehouse->impuesto / 100) + $objWarehouse->flete) : '',
            '({seguro})'          => ($objWarehouse) ? $objWarehouse->seguro : '',
            '({descuento})'       => ($objWarehouse) ? $objWarehouse->descuento : '',
            '({piezas})'          => ($objWarehouse) ? $objWarehouse->piezas : '',
            '({cargos_add})'      => ($objWarehouse) ? $objWarehouse->cargos_add : '',
            '({total})'           => ($objWarehouse) ? (($objWarehouse->valor_declarado * $objWarehouse->impuesto / 100) + $objWarehouse->cargos_add + $objWarehouse->flete + $objWarehouse->seguro - $objWarehouse->descuento) : '',
            //Datos Detalle mensaje
            '({datos_detalle})'   => $datosEnvio,
            '({tracking})'        => $tracking,
            //Datos firma - Agencia
            '({id_agencia})'      => ($objAgencia) ? $objAgencia->id : '',
            '({nom_agencia})'     => ($objAgencia) ? $objAgencia->descripcion : '',
            '({tel_agencia})'     => ($objAgencia) ? $objAgencia->telefono : '',
            '({email_agencia})'   => ($objAgencia) ? $objAgencia->email : '',
            '({dir_agencia})'     => ($objAgencia) ? $objAgencia->direccion : '',
            '({zip_agencia})'     => ($objAgencia) ? $objAgencia->zip : '',
            '({ciudad_agencia})'  => ($objAgencia) ? $objAgencia->ciudad : '',
            '({estado_agencia})'  => ($objAgencia) ? $objAgencia->depto : '',
            '({pais_agencia})'    => ($objAgencia) ? $objAgencia->pais : '',
            '({url_casillero})'   => ($objAgencia) ? $objAgencia->url_casillero : '',
            '({url_terms})'       => ($objAgencia) ? $objAgencia->url_terms : '',
            // '({url_registro})'    => ($objAgencia) ? $objAgencia->url_registro : '',
            // '({url_rastreo})'     => ($objAgencia) ? $objAgencia->url_rastreo : '',
            '({url_prealerta})'   => ($objAgencia) ? $objAgencia->url_prealerta : '',
            '({url_registro_casillero})'     => ($objAgencia) ? $objAgencia->url_registro_casillero : '',
            '({url})'             => ($objAgencia) ? $objAgencia->url : '',
            '({logo_agencia})'    => ($objAgencia) ? '<img src="' . url('storage') . '/' . $objAgencia->logo . '" alt="logo" height="80" />' : '',
        );
        return $replacements;
    }

    public function getConfig($key)
    {
        $data = AplexConfig::where([['key', $key], ['deleted_at', null]])->first();
        return $data;
    }

    public function sendEmailStatus($id_agencia, $id_consignee, $id_plantila)
    {
        DB::beginTransaction();
        try {

            $user = $this->getDataConsigneeOrShipperById($id_consignee, 'consignee');
            $plantilla = DB::table('plantillas_correo AS a')
                ->select([
                    'a.mensaje',
                    'a.subject',
                ])->where([
                    ['a.id', $id_plantila],
                    ['a.deleted_at', '=', null],
                ])->first();
            // La agencia es una consulta a la bd a partir del id que viene por url
            $agencia = $this->getDataAgenciaById($id_agencia);

            $replacements = $this->replacements(null, $agencia, null, null, $user, null);

            $cuerpo_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->mensaje);
            $asunto_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->subject);

            $from_self = array(
                'address' => $agencia->email,
                'name'    => $agencia->descripcion,
            );
            Mail::to($user->correo)->send(new \App\Mail\StatusEmail($cuerpo_correo, $from_self, $asunto_correo));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $success   = false;
            $exception = $e;
            return $e;
        }
    }

    
}
