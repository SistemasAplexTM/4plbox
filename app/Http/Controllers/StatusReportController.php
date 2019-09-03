<?php

namespace App\Http\Controllers;

use App\DocumentoDetalle;
use App\Documento;
use App\Http\Requests\StatusReportRequest;
use App\StatusReport;
use App\Status;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ChangeState;
use App\User;

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

    public function store(StatusReportRequest $request)
    {
        try {
          if ($request->codigo) {
              foreach ($request->codigo as $key => $value) {
                $data = new StatusReport;
                $data->status_id            = $request->status_id;
                $data->documento_detalle_id = $value['id'];
                $data->usuario_id           = Auth::user()->id;
                $data->codigo               = $value['name'];
                $data->observacion          = $request->observacion;
                $data->transportadora       = ($request->transportadora) ? $request->transportadora : NULL;
                $data->num_transportadora   = ($request->num_transportadora) ? $request->num_transportadora : NULL;
                $data->fecha_status         = date('Y-m-d H:i:s');
                $data->created_at           = date('Y-m-d H:i:s');
                $data->save();

                // $status = Status::select('id', 'json_data', 'email')->where('id', $request->status_id)->first();
                $status = Status::where('id', $request->status_id)->first();
                if ($status->view_client) {
                  $user = User::where('consignee_id', $request->consignee_id)->first();
                  // $user->notify(new ChangeState($status));
                }
                if($status->json_data !== null){
                  $datos = json_decode($status->json_data);
                  if(isset($datos->email_template_id) and $datos->email_template_id != '' and $status->email !== 0){
                    $detail = DocumentoDetalle::select('documento_id')->where('id', $value['id'])->first();
                    $document = Documento::select('consignee_id', 'agencia_id')->where('id', $detail->documento_id)->first();
                    $this->sendEmailStatus($document->agencia_id, $document->consignee_id, $datos->email_template_id);
                  }
                }

                $this->AddToLog('Status creado id(' . $data->id . ')');
              }
              $answer = array(
                  "datos"  => '',
                  "code"   => 200,
                  "status" => 200,
              );
          } else {
              $answer = array(
                  "error"  => 'No existen registro para insertar.',
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
                'b.color AS status_color',
                'b.icon AS status_icon',
                'c.num_warehouse',
                'c.num_guia',
                'c.num_consolidado',
                'c.liquidado',
                'd.id as usuario_id',
                'd.name',
                DB::raw('IF (
              	b.id = 5,
              	(
              		SELECT
              			x.consecutivo
              		FROM
              			consolidado_detalle AS z
              		INNER JOIN documento AS x ON z.consolidado_id = x.id
              		WHERE
              			z.documento_detalle_id = a.documento_detalle_id
              		AND z.deleted_at IS NULL
              	),
              	NULL
              ) AS consolidado')
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
