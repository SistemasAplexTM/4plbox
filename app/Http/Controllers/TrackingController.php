<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackingRequest;
use App\Tracking;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tracking.index')->only('index');
        $this->middleware('permission:tracking.store')->only('store');
        $this->middleware('permission:tracking.update')->only('update');
        $this->middleware('permission:tracking.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('tracking');
        return view('templates/tracking');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrackingRequest $request)
    {
        try {
            $data             = (new Tracking)->fill($request->all());
            $data->agencia_id = Auth::user()->agencia_id;
            if ($request->confirmed_send) {
                $data->confirmed_send = 1;
            }
            $data->created_at = date('Y-m-d H:i:s');
            if ($data->save()) {
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
        $data = Tracking::findOrFail($id);
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
            $data             = Tracking::findOrFail($id);
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
    public function getAll($grid = false, $add = null, $id = false, $req_consignee = false)
    {
        $where = [['tracking.deleted_at', null], ['tracking.agencia_id', Auth::user()->agencia_id]];

        if ($grid == false || $grid == 'false') {
            if ($id != '') {
                $where[] = array('tracking.documento_detalle_id', $id);
            } else {
                // $where[] = array('tracking.documento_detalle_id', null);
            }

            if ($add != null and $add != 'null') {
                $where[] = array('tracking.consignee_id', $add);
            } else {
                // if ($req_consignee == false) {
                //     $where[] = array('tracking.consignee_id', null);
                // }
            }
        }
        $data = Tracking::leftJoin('consignee AS b', 'tracking.consignee_id', 'b.id')
            ->leftJoin('documento_detalle AS c', 'tracking.documento_detalle_id', 'c.id')
            ->select(
                'tracking.id',
                'tracking.consignee_id',
                'tracking.documento_detalle_id',
                'tracking.codigo',
                'tracking.contenido',
                'tracking.confirmed_send',
                'tracking.created_at as fecha',
                'b.nombre_full as cliente',
                'c.num_warehouse'
            )
            ->where($where)
            ->get();
        return \DataTables::of($data)->make(true);
    }

    public function getAllShipperConsignee($table)
    {
        $data = DB::table($table . ' as a')
            ->select('a.id', 'a.nombre_full as name')
            ->where([
                ['a.deleted_at', null],
                ['a.agencia_id', Auth::user()->agencia_id],
            ])
            ->get();
        return \DataTables::of($data)->make(true);
    }

    public function searchTracking($tracking)
    {
        $data = DB::table('prealerta as a')
            ->leftjoin('consignee as b', 'a.consignee_id', 'b.id')
            ->select('a.id', 'a.consignee_id', 'a.tracking', 'a.contenido', 'a.instruccion', 'a.correo', 'a.despachar', 'b.nombre_full')
            ->where([
                ['a.deleted_at', null],
                ['a.tracking', $tracking],
                ['a.agencia_id', Auth::user()->agencia_id],
            ])
            ->first();
        $answer = array(
            'code' => 200,
            'data' => $data,
        );
        return $answer;
    }

    public function addOrDeleteDocument(Request $request)
    {
        $data = DB::table('tracking AS a')
            ->select('a.id', 'a.codigo', 'b.num_warehouse')
            ->leftjoin('documento_detalle as b', 'a.documento_detalle_id', 'b.id')
            ->where([
                ['a.deleted_at', null],
                ['a.codigo', $request->tracking],
                ['a.agencia_id', Auth::user()->agencia_id],
            ])
            ->first();
        if ($data != null) {
            if ($request->option === 'create') {
                if ($data->num_warehouse == null) {
                    DB::table('tracking')
                        ->where('id', $data->id)
                        ->update(['documento_detalle_id' => $request->id_detail]);
                    $answer = array(
                        'code' => 200,
                        'data' => $data,
                        'message' => 'Tracking agregado a este documento.'
                    );

                } else {
                    $answer = array(
                        'code'  => 600,
                        'error' => 'El numero de warehouse ingresado, ya esta asociado al documento (<strong>' . $data->num_warehouse . '</strong>).',
                    );
                }
            } else {
                DB::table('tracking')
                    ->where('id', $data->id)
                    ->update(['documento_detalle_id' => null]);
                $answer = array(
                    'code' => 200,
                    'data' => $data,
                    'message' => 'Tracking retirado de este documento.'
                );
            }
        } else {
            $answer = array(
                'code'  => 700,
                'error' => 'El numero de warehouse ingresado, no esta en la base de datos.',
            );
        }

        // if ($request->option == 'delete') {
        //     DB::table('tracking')
        //         ->where('id', $request->id_tracking)
        //         ->update(['documento_detalle_id' => null]);
        // } else {
        //     DB::table('tracking')
        //         ->where('id', $request->id_tracking)
        //         ->update(['documento_detalle_id' => $request->id_document]);
        // }

        return $answer;
    }

    public function validar_tracking(Request $request)
    {
        try {
            $dataTrack = DB::table('tracking')->select('codigo')->where('codigo', $request->element)->first();
            if (count($dataTrack) > 0) {
                $answer = array(
                    "valid"   => false,
                    "message" => "El registro ya se encuentra en nuestra base de datos.",
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
