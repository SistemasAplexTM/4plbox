<?php

namespace App\Http\Controllers;

use App\Consignee;
use App\Prealerta;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Auth;

class PrealertaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_agencia)
    {
        $id_age = $id_agencia;
        return view('templates/prealerta', compact('id_age'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function prealertaList()
    {
        $id_age = Auth::user()->agencia_id;
        return view('templates/prealertaList', compact('id_age'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_agencia)
    {
        try {
            $id_agencia = base64_decode($id_agencia);
            $dataUser = Consignee::select('id')->where('correo', $request->email)->first();
            if (!$dataUser) {
                /* ENVIAR EMAIL */
                $plantilla = $this->getDataEmailPlantillaById(4);
                if (isset($plantilla->mensaje) and $plantilla->mensaje != '') {
                    $this->sendEmailDocument($request->email, $id_agencia);
                }
            }

            // foreach ($request->campos as $key) {
            // for ($i = 0; $i < count($request->campos); $i++) {
            $data = new Prealerta;
            if ($dataUser) {
                $data->consignee_id = $dataUser->id;
            }
            $data->correo = $request->email;
            $data->tracking    = $request['tracking'];
            $data->contenido    = $request['contenido'];
            $data->agencia_id  = $id_agencia;
            $data->instruccion = $request['instruccion'];
            $data->despachar = ($request['despachar']) ? 1 : 0;
            // $data->telefono    = $request['telefono'];
            $data->created_at = date('Y-m-d H:i:s');
            $data->save();
            // }
            $answer = array(
                "datos"  => $request->all(),
                "code"   => 200,
                "status" => 200,
            );
            return $answer;
        } catch (Exception $e) {
            $error = '';
            // if (isset($e->errorInfo)) {
            //     foreach ($e->errorInfo as $key => $value) {
            //         $error .= $key . ' - ' . $value . ' <br> ';
            //     }
            // } else {
            //     $error = $e;
            // }
            $answer = array(
                "error"  => $e,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Prealerta::findOrFail($id);
        $data->delete();
    }

    /**
     * Actualiza el campo deleted_at del registro seleccionado.
     *
     * @param  int  $id
     * @param  boolean  $deleteLogical
     * @return \Illuminate\Http\Response
     */
    public function delete($id, $logical)
    {

        if (isset($logical) and $logical == 'true') {
            $data             = Prealerta::findOrFail($id);
            $now              = new \DateTime();
            $data->deleted_at = $now->format('Y-m-d H:i:s');
            if ($data->save()) {
                $answer = array(
                    "datos" => 'Eliminación exitosa.',
                    "code"  => 200,
                );
            } else {
                $answer = array(
                    "error" => 'Error al intentar Eliminar el registro.',
                    "code"  => 600,
                );
            }

            return $answer;
        } else {
            $this->destroy($id);
        }
    }

    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll($id_agencia)
    {
        $sql = Prealerta::leftJoin('consignee as b', 'prealerta.consignee_id', 'b.id')
            ->join('agencia as c', 'prealerta.agencia_id', 'c.id')
            ->select('prealerta.*', 'b.nombre_full as consignee', 'c.descripcion as agencia')
            ->where([['prealerta.deleted_at', NULL], ['prealerta.recibido', 0], ['prealerta.agencia_id', $id_agencia]])
            ->get();
        return \DataTables::of($sql)->make(true);
    }

    public function sendEmailDocument($email, $id_agencia)
    {
        try {
            $id_plantilla = 4;

            /* DATOS DE LA AGENCIA */
            $objAgencia = $this->getDataAgenciaById($id_agencia);
            $plantilla  = $this->getDataEmailPlantillaById($id_plantilla);
            if (isset($email) and $email != '') {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if (isset($plantilla->mensaje) and $plantilla->mensaje != '') {
                        /* ENVIO DE EMAIL REPLACEMENT($id_documento, $objAgencia, $objDocumento, $objShipper, $objConsignee, $datosEnvio)*/
                        $replacements  = $this->replacements(null, $objAgencia);
                        $cuerpo_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->mensaje);
                        $asunto_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->subject);

                        $from_self = array(
                            'address' => $objAgencia->email,
                            'name'    => $objAgencia->descripcion,
                        );

                        $moreUsers     = $email;
                        $evenMoreUsers = $email;

                        // return new \App\Mail\WarehouseEmail($cuerpo_correo);
                        return Mail::to($email)
                            ->cc($moreUsers)
                            ->bcc($evenMoreUsers)
                            ->send(new \App\Mail\WarehouseEmail($cuerpo_correo, $from_self, $asunto_correo));
                    } else {
                        return 'No existe una plantilla de Email para enviar el mensaje.';
                    }
                } else {
                    return 'No es una direccion de email valida';
                }
            } else {
                return 'No tiene direccion de email';
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function existEmailPost(Request $request)
    {
        try {
            $dataUser = Consignee::select('id')->where('correo', $request->email)->first();
            if (count($dataUser) > 0) {
                $answer = array(
                    "valid"   => true,
                    "message" => "",
                    "data"    => "",
                );
            } else {
                $answer = array(
                    "valid"   => false,
                    "message" => "",
                    "data"    => "",
                );
            }
            return $answer;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function validar_tracking(Request $request)
    {
        try {
            $data      = Prealerta::where('tracking', $request->element)->first();
            $dataTrack = DB::table('tracking')->select('codigo')->where('codigo', $request->element)->first();
            if ($data || $dataTrack) {
                $answer = array(
                    "valid"   => false,
                    "message" => "El registro ya se encuentra registrado en nuestra base de datos.",
                );
            } else {
                $answer = array(
                    "valid"   => true,
                    "message" => "",
                );
            }
            return $answer;
        } catch (Exception $e) {
            return $e;
        }
    }
}
