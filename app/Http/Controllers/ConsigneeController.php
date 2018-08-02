<?php

namespace App\Http\Controllers;

use App\Consignee;
use App\Http\Requests\ConsigneeRequest;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use JavaScript;
use App\User;

class ConsigneeController extends Controller
{
    public function __construct(){
        $this->middleware('permission:consignee.index')->only('index');
        $this->middleware('permission:consignee.store')->only('store');
        $this->middleware('permission:consignee.update')->only('update');
        $this->middleware('permission:consignee.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('consignee');
        JavaScript::put([
            'data_agencia' => $this->getNameAgencia(),
        ]);
        return view('templates/consignee');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConsigneeRequest $request)
    {
        try {
            $data              = (new Consignee)->fill($request->all());
            $data->nombre_full = $request->primer_nombre . ' ' . $request->segundo_nombre . ' ' . $request->primer_apellido . ' ' . $request->segundo_apellido;
            $data->created_at  = date('Y-m-d H:i:s');
            if ($data->save()) {
                $this->AddToLog('Consignee creado id (' . $data->id . ')');
                $this->generarCasillero($data->id);
                if($request->emailsend){
                    $this->enviarEmailCasillero($data->id, $data->agencia_id, $data->nombre_full, $data->correo, $data->celular);
                }
                $answer = array(
                    "datos"  => $request->all(),
                    "code"   => 200,
                    "status" => 200,
                );
            } else {
                $answer = array(
                    "error"  => 'Error al intentar Eliminar el registro.',
                    "code"   => 600,
                    "status" => 500,
                );
            }
            return $answer;
        } catch (\Exception $e) {
            $error = $e;
            // foreach ($e->errorInfo as $key => $value) {
            //     $error .= $key . ' - ' . $value . ' <br> ';
            // }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ConsigneeRequest $request, $id)
    {
        try {
            $data = Consignee::findOrFail($id);
            $data->update($request->all());
            $data->nombre_full = $request->primer_nombre . ' ' . $request->segundo_nombre . ' ' . $request->primer_apellido . ' ' . $request->segundo_apellido;
            $data->save();
            $this->AddToLog('Consignee editado id (' . $data->id . ')');
            $answer = array(
                "datos"  => $request->all(),
                "code"   => 200,
                "status" => 500,
            );
            return $answer;

        } catch (\Exception $e) {
            $error = '';
            foreach ($e->errorInfo as $key => $value) {
                $error .= $key . ' - ' . $value . ' <br> ';
            }
            $answer = array(
                "error"  => $error,
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
        $data = Consignee::findOrFail($id);
        $data->delete();
        $this->AddToLog('Consignee Eliminado de base de datos id (' . $id . ')');
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
            $data             = Consignee::findOrFail($id);
            $now              = new \DateTime();
            $data->deleted_at = $now->format('Y-m-d H:i:s');
            if ($data->save()) {
                $this->AddToLog('Consignee Eliminado id (' . $id . ')');
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
     * Restaura registro eliminado
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restaurar($id)
    {
        $data             = Consignee::findOrFail($id);
        $data->deleted_at = null;
        $data->save();
    }

    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll($data = null, $id_shipper = null, $id_agencia = null)
    {
        if ($id_agencia == null) {
            $id_agencia = Auth::user()->agencia_id;
        }
        $table = 'consignee';
        if ($id_shipper == null || $id_shipper == 'null') {
            $where = [[$table . '.agencia_id', $id_agencia]];
            if ($data != null and $data != 'null') {
                $where[] = array($table . '.nombre_full', 'like', '%' . $data . '%');
            }
            $sql = DB::table($table)
                ->join('localizacion', $table . '.localizacion_id', 'localizacion.id')
                ->join('deptos', 'localizacion.deptos_id', 'deptos.id')
                ->join('pais', 'deptos.pais_id', 'pais.id')
                ->join('agencia', $table . '.agencia_id', 'agencia.id')
                ->leftjoin('tipo_identificacion', $table . '.tipo_identificacion_id', 'tipo_identificacion.id')
                ->select('consignee.id', 'consignee.po_box', 'consignee.documento', 'consignee.tarifa', 'consignee.primer_nombre', 'consignee.segundo_nombre', 'consignee.primer_apellido', 'consignee.segundo_apellido', 'consignee.nombre_full', 'consignee.zip', 'consignee.correo', 'consignee.telefono', 'consignee.direccion', 'consignee.localizacion_id', 'consignee.tipo_identificacion_id', 'consignee.agencia_id', 'localizacion.nombre as ciudad', 'localizacion.id as ciudad_id', 'deptos.descripcion as estado', 'deptos.id as estado_id', 'pais.descripcion as pais', 'pais.id as pais_id', 'agencia.descripcion as agencia', 'tipo_identificacion.descripcion as identificacion')
                ->where($where)
                ->orderBy($table . '.primer_nombre');
        } else {
            $where = [['a.deleted_at', null], ['b.deleted_at', null], ['d.id', $id_agencia]];
            if ($data != null and  $data != 'null') {
                $where[] = array('b.nombre_full', 'like', '%' . $data . '%');
            }
            if ($id_shipper != null) {
                $where[] = array('a.shipper_id', $id_shipper);
            }
            $sql = DB::table('shipper_consignee AS a')
                ->join('consignee AS b', 'a.consignee_id', 'b.id')
                ->join('localizacion AS c', 'b.localizacion_id', 'c.id')
                ->join('agencia AS d', 'b.agencia_id', 'd.id')
                ->select(
                    'b.id',
                    'b.telefono',
                    'b.celular',
                    'b.nombre_full',
                    'b.agencia_id',
                    'c.id AS localizacion_id',
                    'c.nombre AS ciudad',
                    'd.descripcion AS agencia',
                    'b.zip'
                )
                ->where($where)
                ->orderBy('b.nombre_full');
        }

        return \DataTables::of($sql)->make(true);
    }

    public function selectInput(Request $request, $tableName)
    {
        $term = $request->term ?: '';

        if ($tableName != 'localizacion') {
            $tags = DB::table($tableName)
                ->select([$tableName . '.id', $tableName . '.descripcion as text'])
                ->where([
                    [$tableName . '.descripcion', 'like', $term . '%'],
                    [$tableName . '.deleted_at', '=', null],
                ])->get();
        } else {
            $tags = DB::table($tableName)
                ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
                ->join('pais', 'deptos.pais_id', '=', 'pais.id')
                ->select(['localizacion.id', 'localizacion.nombre as text', 'deptos.descripcion as deptos', 'deptos.id as deptos_id', 'pais.descripcion as pais', 'pais.id as pais_id'])
                ->where([
                    ['localizacion.nombre', 'like', $term . '%'],
                    ['localizacion.deleted_at', '=', null],
                ])->get();
        }

        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return \Response::json($answer);
    }

    /**
     * Obtener registros mediante el id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDataById($id)
    {
        $table = 'consignee';
        $data  = DB::table($table)
            ->join('localizacion', $table . '.localizacion_id', '=', 'localizacion.id')
            ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
            ->join('pais', 'deptos.pais_id', '=', 'pais.id')
            ->join('agencia', $table . '.agencia_id', '=', 'agencia.id')
            ->leftjoin('tipo_identificacion', $table . '.tipo_identificacion_id', '=', 'tipo_identificacion.id')
            ->select(DB::raw("CONCAT(" . $table . ".primer_nombre,' ', " . $table . ".segundo_nombre,' ', " . $table . ".primer_apellido,' ', " . $table . ".segundo_apellido) as full_name"), $table . '.*', 'localizacion.nombre as ciudad', 'localizacion.id as ciudad_id', 'deptos.descripcion as estado', 'deptos.id as estado_id', 'pais.descripcion as pais', 'pais.id as pais_id', 'agencia.descripcion as agencia', 'tipo_identificacion.descripcion as identificacion')
            ->where([
                [$table . '.id', '=', $id],
                [$table . '.deleted_at', '=', null],
            ])->first();
        return \Response::json($data);
    }

    public function existEmail(Request $request)
    {
        try {
            $dataUser = Consignee::select('id')->where([
                ['correo', $request->email],
                ['agencia_id', $request->agencia_id]
            ])->first();
            
            if (count($dataUser) > 0) {
                $answer = array(
                    "valid"   => false,
                    "message" => "Este email ya existe en la base de datos",
                    "data"    => "",
                );
            } else {
                $answer = array(
                    "valid"   => true,
                    "message" => "",
                    "data"    => "",
                );
            }
            return $answer;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function generarCasillero($id)
    {
        try {
            $data    = Consignee::findOrFail($id);
            $prefijo = DB::table('consignee as a')
                ->join('localizacion as b', 'a.localizacion_id', 'b.id')
                ->join('deptos as c', 'b.deptos_id', 'c.id')
                ->join('pais as d', 'c.pais_id', 'd.id')
                ->select('b.prefijo', 'd.iso2')
                ->where([
                    ['a.deleted_at', null],
                    ['a.id', $id],
                ])
                ->first();
            $caracteres      = strlen($data->agencia_id);
            $sumarCaracteres = $caracteres - $caracteres;
            $caracter        = '';
            for ($i = 0; $i <= $sumarCaracteres; $i++) {
                $caracter = $caracter . '0';
            }
            $po_box = $caracter . $data->agencia_id . '-' . $id;
            $answer = Consignee::where('id', $id)->update(['po_box' => $prefijo->iso2 . '' . $po_box]);
            return $answer;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function enviarEmailCasillero($id_consignee, $id_agencia, $nombre_full, $correo, $celular)
    {
        DB::beginTransaction();
        try {
            // REGISTRAR USUARIO
            $user = User::where('email', $correo)->first();
            if(!$user){
                User::create([
                    'name'     => $nombre_full,
                    'email'    => $correo,
                    'password' => bcrypt($celular),
                ]);
            }
            $table = 'consignee';
            $user  = DB::table($table)
                ->join('localizacion', $table . '.localizacion_id', '=', 'localizacion.id')
                ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
                ->join('pais', 'deptos.pais_id', '=', 'pais.id')
                ->join('agencia', $table . '.agencia_id', '=', 'agencia.id')
                ->leftjoin('tipo_identificacion', $table . '.tipo_identificacion_id', '=', 'tipo_identificacion.id')
                ->select(DB::raw("CONCAT(" . $table . ".primer_nombre,' ', " . $table . ".segundo_nombre,' ', " . $table . ".primer_apellido,' ', " . $table . ".segundo_apellido) as full_name"), $table . '.*', 'localizacion.nombre as ciudad', 'localizacion.id as ciudad_id', 'deptos.descripcion as depto', 'deptos.id as estado_id', 'pais.descripcion as pais', 'pais.id as pais_id', 'agencia.descripcion as agencia', 'tipo_identificacion.descripcion as identificacion')
                ->where([
                    [$table . '.id', '=', $id_consignee],
                    [$table . '.deleted_at', '=', null],
                ])->first();
            $plantilla = DB::table('plantillas_correo AS a')
                ->select([
                    'a.mensaje',
                    'a.subject',
                ])->where([
                ['a.id', 3],
                ['a.deleted_at', '=', null],
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
                    'd.descripcion AS pais',
                ])->where([
                ['a.id', $id_agencia],
                ['a.deleted_at', '=', null],
            ])->first();

            $replacements = $this->replacements(null, $agencia, null, null, $user, null);

            $cuerpo_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->mensaje);
            $asunto_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->subject);

            $from_self = array(
                'address' => $agencia->email,
                'name'    => $agencia->descripcion,
            );
            Mail::to($correo)->send(new \App\Mail\CasilleroEmail($cuerpo_correo, $from_self, $asunto_correo));
            $this->AddToLog('Email casillero enviado id consignee (' . $id_consignee . ')');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $success   = false;
            $exception = $e;
            return $e;
        }
    }

}
