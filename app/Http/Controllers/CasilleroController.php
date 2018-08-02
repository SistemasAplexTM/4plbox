<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ConsigneeRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Consignee;
use App\Ciudad;
use App\User;

class CasilleroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $img = DB::table('agencia')
        ->select('logo')
        ->where('id', $id)
        ->first();
        return view('templates/casillero', compact('img'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $fechas_error = null;
        DB::beginTransaction();
        try{
            $data = (new Consignee)->fill($request->all());
            $nombre_full = $request->primer_nombre .' '.$request->primer_apellido;
            $data->nombre_full = $nombre_full;
            $data->localizacion_id = $request->localizacion_id['id'];
            $data->created_at = date('Y-m-d H:i:s');
            $data->save();
            // PO_BOX
            $agencia = $request->agencia_id;
            $caracteres = strlen($agencia);
            $sumarCaracteres =  $caracteres - $caracteres;
            $caracter = '';
            for ($i=0; $i <= $sumarCaracteres ; $i++) { 
                $caracter = $caracter . '0';
            }
            $po_box = $caracter . $agencia . '-' . $data->id;
            Consignee::where('id', $data->id)->update(['po_box' =>  $request->localizacion_id['iso2'] . '' . $po_box]);
            // REGISTRAR USUARIO
            User::create([
                'name' => $nombre_full,
                'email' => $request->correo,
                'password' => bcrypt($request->celular),
                'agencia_id' => $agencia
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
            // return new \App\Mail\CasilleroEmail($variable);
            $plantilla = DB::table('plantillas_correo AS a')
            ->select([
                'a.mensaje',
                'a.subject',
            ])->where([
                ['a.id', 3],
                ['a.deleted_at', '=', NULL]
            ])->first();
            // La agencia es una consulta a la bd a partir del id que viene por url
            $agencia = DB::table('agencia AS a')
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
                'b.nombre AS ciudad',
                'c.descripcion AS depto',
                'd.descripcion AS pais'
            ])->where([
                ['a.id', $request->agencia_id],
                ['a.deleted_at', '=', NULL]
            ])->first();

            $replacements = $this->replacements(null, $agencia, null, null, $user, null);

            $cuerpo_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->mensaje);
            $asunto_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->subject);
            $url = DB::table('agencia')->select('url')->where('id', $agencia->id)->first();
            if ($request->recibir_info) {
                $listId = "c963896f86";
                if (!\Mailchimp::check($listId, $request->correo)) {
                    \Mailchimp::subscribe(
                        $listId,
                        $request->correo,
                        [], 
                        // ['FNAME' => $request->nombre, 'LNAME' => $request->primer_apellido], 
                        false
                    );
                }
            }
            // return new \App\Mail\CasilleroEmail($cuerpo_correo);
            // Mail::to($request->correo)->send(new \App\Mail\CasilleroEmail($cuerpo_correo));

            DB::commit();
        } catch (\Exception $e) {
          DB::rollback();
            $success = false;
            $exception = $e;
            return $e;
        }
        if($success){
            return array(
                'code' => 200,
                'url' => $url->url,
                'error' => false
            );
        }else{
            return array(
                'code' => 600,
                'error' => true,
                'exception' => $exception
            );
        }
    }

    public function buscar_ciudad($data){
        $term = $data;

        $tags = DB::table('localizacion AS a')
        ->join('deptos AS b', 'b.id', 'a.deptos_id')
        ->join('pais AS c', 'c.id', 'b.pais_id')
        ->select([
            'a.id',
            'a.nombre as name',
            'b.descripcion AS depto',
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

    public function validar_email(Request $request){
        try {
            $data = Consignee::where([
                ['correo', $request->element],
                ['agencia_id', $request->agencia_id],
            ])->first();
            $dataUser = DB::table('users')->select('email')->where('email', $request->element)->first(); 
            if (count($data) > 0 || count($dataUser) > 0) {
                $answer = array(
                    "valid"   => false,
                    "message" => "El registro ya existe en la base de datos.",
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
