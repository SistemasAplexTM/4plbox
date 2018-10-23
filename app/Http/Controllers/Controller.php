<?php

namespace App\Http\Controllers;

use App\Consignee;
use App\Agencia;
use App\Helpers\LogActivity as Logs;
use App\Shipper;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use JavaScript;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function assignPermissionsJavascript($table)
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
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddToLog($activity = null)
    {
        // \LogActivity::addToLog($activity);
        Logs::addToLog($activity);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
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
            ->select($table . '.*', 'localizacion.nombre as ciudad', 'localizacion.id as ciudad_id', 'deptos.descripcion as depto', 'deptos.id as estado_id', 'pais.descripcion as pais', 'pais.id as pais_id', 'agencia.descripcion as agencia', 'tipo_identificacion.descripcion as identificacion')
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
                'a.id',
                'a.descripcion as descripcion',
                'a.telefono',
                'a.email',
                'a.direccion',
                'a.zip',
                'a.logo',
                'b.nombre AS ciudad',
                'c.descripcion AS depto',
                'd.descripcion AS pais',
            ])->where([
            ['a.id', Auth::user()->agencia_id],
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
        }else{
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

    public function createOrUpdateShipperConsignee($data)
    {
        /* arreglos donde se guardan los datos de shipper y consignee para registrar */
        $dataShip = array();
        $dataCons = array();
        /*ids de shipper y consignee*/
        $shipper_id = $data['shipper_id'];
        $consig_id  = $data['consignee_id'];

        if ($data['shipper_id'] == '') {
            //DATOS SHIPPER
            try {
                $firstNamesFull = '';
                $lastNamesFull  = '';
                $arrayNamesFull = $this->getNamesAndFullNames($data['nombreR']);
                $firstNames     = $arrayNamesFull[0];
                $lastName       = $arrayNamesFull[1];
                $dataShip       = array(
                    'agencia_id'      => $data['agencia_id'],
                    'localizacion_id' => $data['localizacion_id'],
                    'direccion'       => $data['direccionR'],
                    'telefono'        => $data['telR'],
                    'correo'          => $data['emailR'],
                    'zip'             => $data['zipR'],
                );
                /*verifico si existen dos nombres o solo uno para registrar*/
                if (count($firstNames) != 0) {
                    if (count($firstNames) != 1) {
                        if ($firstNames[1] != '') {
                            $dataShip['primer_nombre']  = strtoupper($firstNames[0]);
                            $dataShip['segundo_nombre'] = strtoupper($firstNames[1]);
                            $firstNamesFull .= $firstNames[0] . ' ' . $firstNames[1];
                        } else {
                            $dataShip['primer_nombre']  = strtoupper($firstNames[0]);
                            $dataShip['segundo_nombre'] = '';
                            $firstNamesFull .= $firstNames[0];
                        }
                    } else {
                        $dataShip['primer_nombre']  = $firstNames[0];
                        $dataShip['segundo_nombre'] = '';
                        $firstNamesFull .= $firstNames[0];
                    }
                }
                /*verifico si existen dos apellidos o solo uno para registrar*/
                if (count($lastName) != 0) {
                    if (count($lastName) != 1) {
                        if ($lastName[0] != '') {
                            $dataShip['primer_apellido']  = strtoupper($lastName[0]);
                            $dataShip['segundo_apellido'] = strtoupper($lastName[1]);
                            $lastNamesFull .= $lastName[0] . ' ' . $lastName[1];
                        } else {
                            $dataShip['primer_apellido']  = strtoupper($lastName[1]);
                            $dataShip['segundo_apellido'] = '';
                            $lastNamesFull .= $lastName[0];
                        }
                    } else {
                        $dataShip['primer_apellido']  = strtoupper($lastName[0]);
                        $dataShip['segundo_apellido'] = '';
                        $lastNamesFull .= $lastName[0];
                    }
                }

                $dataShip['nombre_full'] = strtoupper($firstNamesFull . ' ' . $lastNamesFull);
                $dataShip['nombre_old']  = strtoupper($firstNamesFull . ' ' . $lastNamesFull);

                $dataS             = (new Shipper)->fill($dataShip);
                $dataS->created_at = date('Y-m-d H:i:s');
                $dataS->save();
                $shipper_id = $dataS->id;
            } catch (Exception $e) {
                echo 'Error: <pre>';
                print_r($e);
                echo '</pre>';
            }
        } else {

            if ($shipper_id != '' and isset($data['opEditarShip'])) {
                $dataU                  = Shipper::findOrFail($shipper_id);
                $dataU->agencia_id      = $data['agencia_id'];
                $dataU->localizacion_id = $data['localizacion_id'];
                $dataU->direccion       = $data['direccionR'];
                $dataU->telefono        = $data['telR'];
                $dataU->correo          = $data['emailR'];
                $dataU->zip             = $data['zipR'];

                $firstNamesFull = '';
                $lastNamesFull  = '';
                $arrayNamesFull = $this->getNamesAndFullNames($data['nombreR']);
                $firstNames     = $arrayNamesFull[0];
                $lastName       = $arrayNamesFull[1];

                /*verifico si existen dos nombres o solo uno para registrar*/
                if (count($firstNames) != 0) {
                    if (count($firstNames) != 1) {
                        if ($firstNames[1] != '') {
                            $dataU->primer_nombre  = strtoupper($firstNames[0]);
                            $dataU->segundo_nombre = strtoupper($firstNames[1]);
                            $firstNamesFull .= $firstNames[0] . ' ' . $firstNames[1];
                        } else {
                            $dataU->primer_nombre  = strtoupper($firstNames[0]);
                            $dataU->segundo_nombre = '';
                            $firstNamesFull .= $firstNames[0];
                        }
                    } else {
                        $dataU->primer_nombre  = strtoupper($firstNames[0]);
                        $dataU->segundo_nombre = '';
                        $firstNamesFull .= $firstNames[0];
                    }
                }
                /*verifico si existen dos apellidos o solo uno para registrar*/
                if (count($lastName) != 0) {
                    if (count($lastName) != 1) {
                        if ($lastName[0] != '') {
                            $dataU->primer_apellido  = strtoupper($lastName[0]);
                            $dataU->segundo_apellido = strtoupper($lastName[1]);
                            $lastNamesFull .= $lastName[0] . ' ' . $lastName[1];
                        } else {
                            $dataU->primer_apellido  = strtoupper($lastName[1]);
                            $dataU->segundo_apellido = '';
                            $lastNamesFull .= $lastName[0];
                        }
                    } else {
                        $dataU->primer_apellido  = strtoupper($lastName[0]);
                        $dataU->segundo_apellido = '';
                        $lastNamesFull .= $lastName[0];
                    }
                }

                $dataU->nombre_full = strtoupper($firstNamesFull . ' ' . $lastNamesFull);
                $dataU->nombre_old  = strtoupper($firstNamesFull . ' ' . $lastNamesFull);

                $dataU->save();
            }
        }

        if ($data['consignee_id'] == '') {
            //DATOS CONSIGNEE
            try {
                $firstNamesFull = '';
                $lastNamesFull  = '';
                $arrayNamesFull = $this->getNamesAndFullNames($data['nombreD']);
                $firstNames     = $arrayNamesFull[0];
                $lastName       = $arrayNamesFull[1];
                $dataCons       = array(
                    'agencia_id'      => $data['agencia_id'],
                    'localizacion_id' => $data['localizacion_id_c'],
                    'direccion'       => $data['direccionD'],
                    'telefono'        => $data['telD'],
                    'correo'          => $data['emailD'],
                    'zip'             => $data['zipD'],
                    'po_box'          => $data['poBoxD'],
                );
                /*verifico si existen dos nombres o solo uno para registrar*/
                if (count($firstNames) != 0) {
                    if (count($firstNames) != 1) {
                        if ($firstNames[1] != '') {
                            $dataCons['primer_nombre']  = strtoupper($firstNames[0]);
                            $dataCons['segundo_nombre'] = strtoupper($firstNames[1]);
                            $firstNamesFull .= $firstNames[0] . ' ' . $firstNames[1];
                        } else {
                            $dataCons['primer_nombre']  = strtoupper($firstNames[0]);
                            $dataCons['segundo_nombre'] = '';
                            $firstNamesFull .= $firstNames[0];
                        }
                    } else {
                        $dataCons['primer_nombre']  = strtoupper($firstNames[0]);
                        $dataCons['segundo_nombre'] = '';
                        $firstNamesFull .= $firstNames[0];
                    }
                }
                /*verifico si existen dos apellidos o solo uno para registrar*/
                if (count($lastName) != 0) {
                    if (count($lastName) != 1) {
                        if ($lastName[0] != '') {
                            $dataCons['primer_apellido']  = strtoupper($lastName[0]);
                            $dataCons['segundo_apellido'] = strtoupper($lastName[1]);
                            $lastNamesFull .= $lastName[0] . ' ' . $lastName[1];
                        } else {
                            $dataCons['primer_apellido']  = strtoupper($lastName[1]);
                            $dataCons['segundo_apellido'] = '';
                            $lastNamesFull .= $lastName[0];
                        }
                    } else {
                        $dataCons['primer_apellido']  = strtoupper($lastName[0]);
                        $dataCons['segundo_apellido'] = '';
                        $lastNamesFull .= $lastName[0];
                    }
                }

                $dataCons['nombre_full'] = strtoupper($firstNamesFull . ' ' . $lastNamesFull);
                $dataCons['nombre_old']  = strtoupper($firstNamesFull . ' ' . $lastNamesFull);


                $dataC             = (new Consignee)->fill($dataCons);
                $dataC->created_at = date('Y-m-d H:i:s');
                $dataC->save();
                $consig_id = $dataC->id;

                /* CREACION DEL PO_BOX */
                $prefijo = DB::table('consignee as a')
                ->join('localizacion as b', 'a.localizacion_id', 'b.id')
                ->join('deptos as c', 'b.deptos_id', 'c.id')
                ->join('pais as d', 'c.pais_id', 'd.id')
                ->select('b.prefijo', 'd.iso2')
                ->where([
                    ['a.deleted_at', null],
                    ['a.id', $consig_id],
                ])
                ->first();
                $pref = '';
                $prefijo_pobox = Agencia::select('prefijo_pobox')->where('id', Auth::user()->agencia_id)->first();
                if($prefijo_pobox->prefijo_pobox == null){
                   $pref = Auth::user()->agencia_id;
                }else{
                    $pref = $prefijo_pobox->prefijo_pobox;
                }
                $caracteres      = strlen($pref);
                $sumarCaracteres = $caracteres - $caracteres;
                $caracter        = '';
                for ($i = 0; $i <= $sumarCaracteres; $i++) {
                    $caracter = $caracter . '0';
                }
                $po_box = $caracter . $pref . '-' . $consig_id;
                // Consignee::where('id', $consig_id)->update(['po_box' => $prefijo->iso2 . '' . $po_box]);
                Consignee::where('id', $consig_id)->update(['po_box' => $po_box]);

            } catch (Exception $e) {
                echo 'Error: <pre>';
                print_r($e);
                echo '<pre>';
            }
        } else {
            if ($consig_id != '' and isset($data['opEditarCons'])) {
                $dataU                  = Consignee::findOrFail($consig_id);
                $dataU->agencia_id      = $data['agencia_id'];
                $dataU->localizacion_id = $data['localizacion_id_c'];
                $dataU->direccion       = $data['direccionD'];
                $dataU->telefono        = $data['telD'];
                $dataU->correo          = $data['emailD'];
                $dataU->zip             = $data['zipD'];
                $dataU->po_box          = $data['poBoxD'];

                $firstNamesFull = '';
                $lastNamesFull  = '';
                $arrayNamesFull = $this->getNamesAndFullNames($data['nombreD']);
                $firstNames     = $arrayNamesFull[0];
                $lastName       = $arrayNamesFull[1];

                /*verifico si existen dos nombres o solo uno para registrar*/
                if (count($firstNames) != 0) {
                    if (count($firstNames) != 1) {
                        if ($firstNames[1] != '') {
                            $dataU->primer_nombre  = strtoupper($firstNames[0]);
                            $dataU->segundo_nombre = strtoupper($firstNames[1]);
                            $firstNamesFull .= $firstNames[0] . ' ' . $firstNames[1];
                        } else {
                            $dataU->primer_nombre  = strtoupper($firstNames[0]);
                            $dataU->segundo_nombre = '';
                            $firstNamesFull .= $firstNames[0];
                        }
                    } else {
                        $dataU->primer_nombre  = strtoupper($firstNames[0]);
                        $dataU->segundo_nombre = '';
                        $firstNamesFull .= $firstNames[0];
                    }
                }
                /*verifico si existen dos apellidos o solo uno para registrar*/
                if (count($lastName) != 0) {
                    if (count($lastName) != 1) {
                        if ($lastName[0] != '') {
                            $dataU->primer_apellido  = strtoupper($lastName[0]);
                            $dataU->segundo_apellido = strtoupper($lastName[1]);
                            $lastNamesFull .= $lastName[0] . ' ' . $lastName[1];
                        } else {
                            $dataU->primer_apellido  = strtoupper($lastName[1]);
                            $dataU->segundo_apellido = '';
                            $lastNamesFull .= $lastName[0];
                        }
                    } else {
                        $dataU->primer_apellido  = strtoupper($lastName[0]);
                        $dataU->segundo_apellido = '';
                        $lastNamesFull .= $lastName[0];
                    }
                }

                $dataU->nombre_full = strtoupper($firstNamesFull . ' ' . $lastNamesFull);

                $dataU->save();
            }
        }
        /*VALIDO SI EXISTE EN LA TABLA PIBOT 'shipper_consignee' LA RELACION, SI NO EXISTE LA CREAMOS*/
        $this->validateRelationShipperConsignee($shipper_id, $consig_id);

        /*fin del registro del shipper y consignee*/
        $ids = array(
            'shipper_id' => $shipper_id,
            'consig_id'  => $consig_id,
        );
        return $ids;
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
        );
        return $replacements;
    }
}
