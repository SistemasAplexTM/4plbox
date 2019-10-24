<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipperRequest;
use App\Shipper;
use DataTables;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JavaScript;

class ShipperController extends Controller
{
    public function __construct(){
        $this->middleware('permission:shipper.index')->only('index');
        $this->middleware('permission:shipper.store')->only('store');
        $this->middleware('permission:shipper.update')->only('update');
        $this->middleware('permission:shipper.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('shipper');
        JavaScript::put([
            'data_agencia' => $this->getNameAgencia(),
        ]);
        return view('templates/shipper');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShipperRequest $request)
    {
        try {
            $email_cc = null;
            if ($request->emails_cc) {
              $email_cc = implode(",", $request->emails_cc);
            }
            $data              = (new Shipper)->fill($request->all());
            $data->nombre_full = $request->primer_nombre . ' ' . $request->segundo_nombre . ' ' . $request->primer_apellido . ' ' . $request->segundo_apellido;
            $data->created_at  = date('Y-m-d H:i:s');
            $data->agencia_id = Auth::user()->agencia_id;
            $data->email_cc = $email_cc;
            if ($data->save()) {
                $answer = array(
                    "datos"  => $this->getDataById($data->id)->original,
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShipperRequest $request, $id)
    {
        try {
          $email_cc = null;
          if ($request->emails_cc) {
            $email_cc = implode(",", $request->emails_cc);
          }
            $data = Shipper::findOrFail($id);
            $data->update($request->all());
            $data->nombre_full = $request->primer_nombre . ' ' . $request->segundo_nombre . ' ' . $request->primer_apellido . ' ' . $request->segundo_apellido;
            $data->email_cc = $email_cc;
            $data->save();
            $answer = array(
                "datos"  => $this->getDataById($id)->original,
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
        $data = Shipper::findOrFail($id);
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
            $data             = Shipper::findOrFail($id);
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
     * Restaura registro eliminado
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restaurar($id)
    {
        $data             = Shipper::findOrFail($id);
        $data->deleted_at = null;
        $data->save();
    }

    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll($data = null, $id_consignee = null, $id_agencia = null)
    {
        if($id_agencia == null){
            $id_agencia = Auth::user()->agencia_id;
        }
        $table = 'shipper';
        if ($id_consignee == null || $id_consignee == 'null') {
            $where = [[$table . '.deleted_at', '=', null]];
            if ($data != null and $data != 'null') {
                $where[] = array($table . '.nombre_full', 'like', '%' . $data . '%');
            }
            // if(!Auth::user()->isRole('admin')){
                $where[] = [$table . '.agencia_id', $id_agencia];
            // }
            $sql = DB::table($table)
                ->join('localizacion', $table . '.localizacion_id', '=', 'localizacion.id')
                ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
                ->join('pais', 'deptos.pais_id', '=', 'pais.id')
                ->join('agencia', $table . '.agencia_id', '=', 'agencia.id')
                ->select('shipper.id', 'shipper.agencia_id', 'shipper.localizacion_id', 'shipper.primer_nombre', 'shipper.segundo_nombre', 'shipper.primer_apellido', 'shipper.segundo_apellido', 'shipper.nombre_full', 'shipper.zip', 'shipper.correo', 'shipper.telefono', 'shipper.direccion', 'localizacion.nombre as ciudad', 'localizacion.id as ciudad_id', 'deptos.descripcion as estado', 'deptos.id as estado_id', 'pais.descripcion as pais', 'pais.id as pais_id', 'agencia.descripcion as agencia')
                ->where($where)
                ->orderBy($table . '.nombre_full');
        } else {

            $where = [['a.deleted_at', null], ['shipper.deleted_at', null]];
            if ($data != null and $data != 'null') {
                $where[] = array('shipper.nombre_full', 'like', '%' . $data . '%');
            }
            if ($id_consignee != null) {
                $where[] = array('a.consignee_id', $id_consignee);
            }
            if(!Auth::user()->isRole('admin')){
                $where[] = ['agencia.id', $id_agencia];
            }

            $sql = DB::table('shipper_consignee AS a')
                ->join('shipper', 'a.shipper_id', 'shipper.id')
                ->join('localizacion', 'shipper.localizacion_id', 'localizacion.id')
                ->join('agencia', 'shipper.agencia_id', 'agencia.id')
                ->select(
                    'shipper.id',
                    'shipper.telefono',
                    'shipper.nombre_full',
                    'shipper.agencia_id',
                    'localizacion.id AS localizacion_id',
                    'localizacion.nombre AS ciudad',
                    'agencia.descripcion AS agencia',
                    'shipper.zip',
                    'shipper.correo'
                )
                ->where($where)
                ->orderBy('shipper.nombre_full');
        }
        return \DataTables::of($sql)->make(true);
    }

    public function selectInput(Request $request, $tableName)
    {
        $term = $request->term ?: '';

        if ($tableName != 'localizacion') {
            if($tableName == 'agencia' && !Auth::user()->isRole('admin')){
                $tags = false;
            }else{
                $tags = DB::table($tableName)
                    ->select([$tableName . '.id', $tableName . '.descripcion as text'])
                    ->where([
                        [$tableName . '.descripcion', 'like', $term . '%'],
                        [$tableName . '.deleted_at', '=', null],
                    ])->get();
            }
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
        $table = 'shipper';
        $data  = DB::table($table)
            ->join('localizacion', $table . '.localizacion_id', '=', 'localizacion.id')
            ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
            ->join('pais', 'deptos.pais_id', '=', 'pais.id')
            ->join('agencia', $table . '.agencia_id', '=', 'agencia.id')
            ->select(DB::raw("CONCAT(" . $table . ".primer_nombre,' ', " . $table . ".segundo_nombre,' ', " . $table . ".primer_apellido,' ', " . $table . ".segundo_apellido) as full_name"), $table . '.*', 'localizacion.nombre as ciudad', 'localizacion.id as ciudad_id', 'deptos.descripcion as estado', 'deptos.id as estado_id', 'pais.descripcion as pais', 'pais.id as pais_id', 'agencia.descripcion as agencia')
            ->where([
                [$table . '.id', '=', $id],
                [$table . '.deleted_at', '=', null],
            ])->first();
        return \Response::json($data);
    }

    public function storeContacto(Request $request, $id, $table)
    {
        $datos = $request->all();
        $d_json = json_encode($datos);
        DB::table($table)
            ->where('id', $id)
            ->update(['contactos_json' => $d_json]);
        return array('code' => 200);
    }

    public function getContactos($id, $table)
    {
        $data = DB::table($table)
            ->select('contactos_json')
            ->where('id', $id)
            ->first();
        return $data->contactos_json;
    }

    public function existEmail(Request $request)
    {
        try {
            $dataUser = Shipper::select('id')->where([
                ['correo', $request->email],
                ['agencia_id', $request->agencia_id]
            ])->first();
            if (count($dataUser) > 0) {
                $answer = array(
                    "valid"   => false,
                    "message" => "Este email ya existe en la base de datos",
                    "data" => "",
                );
            } else {
                $answer = array(
                    "valid"   => true,
                    "message" => "",
                    "data" => "",
                );
            }
            return $answer;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function vueSelect($data)
    {
        $term = $data;

        $tags = Shipper::select(['id', 'nombre_full as name'])
        ->whereRaw("TRIM(REPLACE(nombre_full,'  ',' ')) like '%$term%'")
        ->where([
            ['deleted_at', null]
        ])->get();
        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return $answer;
    }

    public function getContacts($id)
    {
        try {
        return Shipper::where('parent_id', $id)->with('city')->get();
        } catch (Exception $e) {
        return $e;
        }
    }
}
