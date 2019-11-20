<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ConsigneeRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Consignee;
use App\Ciudad;
use App\User;
use App\Agencia;
use App\AplexConfig;

class CasilleroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $id = base64_decode($id);
        $img = DB::table('agencia')
            ->select('*')
            ->where('id', $id)
            ->first();
        $terms = AplexConfig::where('key', 'agency_mc_' . $id)->first();
        if ($terms) {
            $terms = $terms->value;
            $terms = json_decode($terms, true);
            $terms = $terms['actived'];
        } else {
            $terms = false;
        }
        if ($img == null) {
            return redirect('/');
        }
        return view('templates/casillero', compact('img', 'terms'));
    }

    public function store(Request $request)
    {
        $success = true;
        $fechas_error = null;
        DB::beginTransaction();
        try {
            $request->agencia_id = base64_decode($request->agencia_id);
            $data = (new Consignee)->fill($request->all());
            $nombre_full = $request->primer_nombre . ' ' . $request->primer_apellido;
            $data->nombre_full = $nombre_full;
            $data->casillero = 1;
            $data->agencia_id = $request->agencia_id;
            $data->localizacion_id = $request->localizacion_id;
            $data->created_at = date('Y-m-d H:i:s');
            $data->save();
            // PO_BOX
            $agencia =  $request->agencia_id;
            $pref = '';
            $prefijo_pobox = Agencia::select('prefijo_pobox')->where('id', $agencia)->first();
            if ($prefijo_pobox->prefijo_pobox == null) {
                $pref = $agencia;
            } else {
                $pref = $prefijo_pobox->prefijo_pobox;
            }
            $caracteres      = strlen($pref);

            // $caracteres = strlen($agencia);
            $sumarCaracteres =  $caracteres - $caracteres;
            $caracter = '';
            if ($prefijo_pobox->prefijo_pobox == null) {
                for ($i = 0; $i <= $sumarCaracteres; $i++) {
                    $caracter = $caracter . '0';
                }
            }

            $po_box = $caracter . $pref . '-' . $data->id;
            Consignee::where('id', $data->id)->update(['po_box' =>  $po_box]);
            // REGISTRAR USUARIO
            $user = User::create([
                'name' => $nombre_full,
                'email' => $request->correo,
                'password' => bcrypt($request->celular),
                'agencia_id' => $agencia,
                'consignee_id' => $data->id
            ]);
            $table = 'consignee';
            $user = DB::table($table)
                ->join('localizacion', $table . '.localizacion_id', '=', 'localizacion.id')
                ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
                ->join('pais', 'deptos.pais_id', '=', 'pais.id')
                ->join('agencia', $table . '.agencia_id', '=', 'agencia.id')
                ->leftjoin('tipo_identificacion', $table . '.tipo_identificacion_id', '=', 'tipo_identificacion.id')
                ->select(DB::raw("CONCAT(" . $table . ".primer_nombre,' ', " . $table . ".segundo_nombre,' ', " . $table . ".primer_apellido,' ', " . $table . ".segundo_apellido) as full_name"), $table . '.*', 'localizacion.nombre as ciudad', 'localizacion.id as ciudad_id', 'deptos.descripcion as depto', 'deptos.id as estado_id', 'pais.descripcion as pais', 'pais.id as pais_id', 'agencia.descripcion as agencia', 'tipo_identificacion.descripcion as identificacion')
                ->where([
                    [$table . '.id', '=', $data->id],
                    [$table . '.deleted_at', '=', null],
                ])->first();

            $plantilla = DB::table('plantillas_correo AS a')
                ->select([
                    'a.mensaje',
                    'a.subject',
                ])->where([
                    ['a.id', 3],
                    ['a.deleted_at', '=', NULL]
                ])->first();

            $agencia = $this->getDataAgenciaById($request->agencia_id);
            
            $replacements = $this->replacements(null, $agencia, null, null, $user, null);
            $cuerpo_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->mensaje);
            $asunto_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->subject);
            $from_self = array(
                'address' => $agencia->email,
                'name'    => $agencia->descripcion,
            );
            if ($request->recibir_info) {
                $list_id = AplexConfig::where('key', 'agency_mc_' . $request->agencia_id)->first();
                if ($list_id) {
                    $list_id = $list_id->value;
                    $list_id = json_decode($list_id, true);

                    $mc = new \NZTim\Mailchimp\Mailchimp($list_id['mc_key']);
                    $list_id = $list_id['id_list'];
                    $listId = $request->listId;
                    if (!$mc->check($list_id, $request->correo)) {
                        $mc->subscribe(
                            $list_id,
                            $request->correo,
                            ['FNAME' => $request->primer_nombre, 'LNAME' => $request->primer_apellido, 'POBOX' => $po_box],
                            false
                        );
                    }
                }
            }
            //Mail::to($request->correo)->send(new \App\Mail\CasilleroEmail($cuerpo_correo, $from_self, $asunto_correo));

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $success = false;
            $exception = $e;
            return array(
                'code' => 600,
                'error' => true,
                'exception' => $exception
            );
        }
        if ($success) {
            return array(
                'code' => 200,
                'url' => url()->current() . '/' . base64_encode($request->agencia_id),
                'error' => false
            );
        } else {
            return array(
                'code' => 600,
                'error' => true,
                'exception' => $exception
            );
        }
    }

    public function buscar_ciudad($data)
    {
        $term = $data;

        $tags = DB::table('localizacion AS a')
            ->join('deptos AS b', 'b.id', 'a.deptos_id')
            ->join('pais AS c', 'c.id', 'b.pais_id')
            ->select([
                'a.id',
                'a.nombre as name',
                'b.descripcion AS depto',
                'c.id AS pais_id',
                'c.descripcion AS pais',
                'c.phone_code',
                'c.iso2'
            ])->where([
                ['a.nombre', 'like', '%' . $term . '%'],
                ['a.deleted_at', '=', NULL]
            ])->get();
        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return $answer;
    }

    public function validar_email(Request $request)
    {
        $request->agencia_id = base64_decode($request->agencia_id);
        try {
            $data = Consignee::where([
                ['correo', $request->element],
                ['agencia_id', $request->agencia_id],
            ])->first();
            $dataUser = DB::table('users')->select('email')->where([['email', $request->element], ['agencia_id', $request->agencia_id]])->first();
            if ($data || $dataUser) {
                $answer = array(
                    "valid"   => false,
                    "message" => "El registro ya existe en la base de datos.",
                    "data" => array('consignee: ' => $data, 'User: ' => $dataUser)
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
