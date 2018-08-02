<?php

namespace App\Http\Controllers;

use App\DocumentoDetalle;
use App\Http\Requests\StatusReportRequest;
use App\StatusReport;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('templates/statusReport');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatusReportRequest $request)
    {
        try {
            $obj = DocumentoDetalle::select('id')
                ->whereRaw("documento_detalle.num_warehouse = '" . trim($request->codigo) . "' OR documento_detalle.num_guia = '" . trim($request->codigo) . "'")
                ->where('deleted_at', null)
                ->first();
            if ($obj) {
                $data                       = (new StatusReport)->fill($request->all());
                $data->documento_detalle_id = $obj->id;
                $data->usuario_id           = Auth::user()->id;
                $data->fecha_status         = date('Y-m-d H:i:s');
                $data->created_at           = date('Y-m-d H:i:s');
                if ($data->save()) {
                    $this->AddToLog('Status creado id(' . $data->id . ')');
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
            } else {
                $answer = array(
                    "error"  => 'No existe registro con ese numero de Guia o Warehouse que ingresó.',
                    "code"   => 600,
                    "status" => 500,
                );
            }
            return $answer;
        } catch (\Exception $e) {
            $error = '';
            if (isset($e->errorInfo) and count($e->errorInfo) > 0) {
                foreach ($e->errorInfo as $key => $value) {
                    $error .= $key . ' - ' . $value . ' <br> ';
                }
            } else {
                $error = $e;
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $e;
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
        $data = StatusReport::findOrFail($id);
        $data->delete();
        $this->AddToLog('Status Eliminado fisicamente id(' . $id . ')');
    }

    /**
     * Actualiza el campo deleted_at del registro seleccionado.
     *
     * @param  int  $id
     * @param  boolean  $deleteLogical
     * @return \Illuminate\Http\Response
     */
    public function delete($id, $logical = false)
    {
        if (isset($logical) and $logical == 'true') {
            $data             = StatusReport::findOrFail($id);
            $now              = new \DateTime();
            $data->deleted_at = $now->format('Y-m-d H:i:s');
            if ($data->save()) {
                $this->AddToLog('Status Eliminado id(' . $id . ')');
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
    public function getAll()
    {
        $sql = DB::table('status_detalle AS a')
            ->join('status AS b', 'a.status_id', 'b.id')
            ->join('documento_detalle AS c', 'a.documento_detalle_id', 'c.id')
            ->join('users AS d', 'd.id', '.usuario_id')
            ->select(
                'a.id',
                'a.documento_detalle_id',
                'a.codigo',
                'a.fecha_status',
                'a.observacion',
                'a.transportadora',
                'a.num_transportadora',
                'b.id AS status_id',
                'b.descripcion AS status_name',
                'c.num_warehouse',
                'c.num_guia',
                'c.num_consolidado',
                'c.liquidado',
                'd.id as usuario_id',
                'd.name'
            )
            ->where('a.deleted_at', null)
            ->orderBy('a.fecha_status', 'DESC');
        return \DataTables::of($sql)->make(true);
    }

    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllGrid($id_documento)
    {
        $sql = DB::table('status_detalle AS a')
            ->join('status AS b', 'a.status_id', 'b.id')
            ->join('documento_detalle AS c', 'a.documento_detalle_id', 'c.id')
            ->join('users AS d', 'd.id', 'a.usuario_id')
            ->join('documento AS e', 'e.id', 'c.documento_id')
            ->select(
                'a.id',
                'a.documento_detalle_id',
                'a.codigo',
                'a.fecha_status',
                'a.observacion',
                'a.transportadora',
                'a.num_transportadora',
                'b.id AS status_id',
                'b.descripcion AS status_name',
                'c.num_warehouse',
                'c.num_guia',
                'c.num_consolidado',
                'c.liquidado',
                'd.id as usuario_id',
                'd.name'
            )
            ->where([
                ['a.deleted_at', null],
                ['e.id', $id_documento],
            ])
            ->orderBy('a.fecha_status', 'DESC');
        return \DataTables::of($sql)->make(true);
    }

    public function getStatusByIdDetalle($id)
    {
        /* ESTATUS DEL DOCUMENTO */
        $status = DB::table('status_detalle AS a')
            ->join('status AS b', 'a.status_id', 'b.id')
            ->join('documento_detalle AS c', 'a.documento_detalle_id', 'c.id')
            ->join('users AS d', 'd.id', '.usuario_id')
            ->select(
                'a.id',
                'a.documento_detalle_id',
                'a.codigo',
                'a.fecha_status',
                'a.observacion',
                'a.transportadora',
                'a.num_transportadora',
                'b.id AS status_id',
                'b.descripcion AS status_name',
                'b.color AS status_color',
                'c.num_warehouse',
                'c.num_guia',
                'c.num_consolidado',
                'c.liquidado',
                'd.id as usuario_id',
                'd.name'
            )
            ->where([['a.deleted_at', null], ['a.documento_detalle_id', $id]])
            ->orderBy('a.fecha_status', 'DESC')
            ->get();
        $answer = array(
            "data" => $status,
            "code" => 200,
        );
        return $answer;
    }
}
