<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusRequest;
use App\Status;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DocumentoDetalle;
use Auth;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:status.index')->only('index');
        $this->middleware('permission:status.store')->only('store');
        $this->middleware('permission:status.update')->only('update');
        $this->middleware('permission:status.destroy')->only('destroy');
        $this->middleware('permission:status.delete')->only('delete');
    }

    public function index()
    {
        $this->assignPermissionsJavascript('status');
        return view('templates/status');
    }

    public function store(StatusRequest $request)
    {
        try {
            $data             = (new Status)->fill($request->all());
            $data->created_at = date('Y-m-d H:i:s');
            if($request->email_plantilla_id != null){
              $data->json_data = json_encode(['email_template_id' => $request->email_plantilla_id]);
            }
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
        } catch (Exception $e) {
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

    public function update(StatusRequest $request, $id)
    {
        try {
            $data = Status::findOrFail($id);
            if($request->email_plantilla_id != null){
              $data->json_data = json_encode(['email_template_id' => $request->email_plantilla_id]);
            }
            $data->update($request->all());
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
        try {
            $data = Status::findOrFail($id);
            $data->delete();
            $answer = array(
                "datos" => 'Eliminación exitosa.',
                "code"  => 200,
            );
        } catch (\Exception $e) {
            $error = '';
            foreach ($e->errorInfo as $key => $value) {
                // $error .= $key . ' - ' . $value . ' <br> ';
                if($value == '23000'){
                    $error .= 'No es posible eliminar el registro, esta asociado con otro registro <br> ';
                }
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
        }
        return $answer;
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
            $data             = Status::findOrFail($id);
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
        $data             = Status::findOrFail($id);
        $data->deleted_at = null;
        $data->save();
    }

    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        return \DataTables::of(Status::query()->where('deleted_at', '=', null))->make(true);
    }

    public function getDataSelect()
    {
        $data = Status::select('descripcion as name', 'id as value')
            ->where('deleted_at', null)->get();
        return array(
            "code" => 200,
            "data" => $data,
        );
    }

    public function getDataSelectModalTagGuia()
    {
        $data = Status::select('descripcion as name', 'id', 'transportadora')
            ->where('deleted_at', null)->get();
        return \DataTables::of($data)->make(true);
    }

    public function getDataSelectTransportadoras($id)
    {
      $pais_id = DB::table('documento AS a')
      ->join('consignee AS b', 'a.consignee_id', 'b.id')
      ->join('localizacion AS c', 'b.localizacion_id', 'c.id')
      ->join('deptos AS d', 'c.deptos_id', 'd.id')
      ->select('d.pais_id')
      ->where([['a.id', $id]])
      ->first();

        $data = DB::table('transportadoras_locales AS a')
        ->select('a.nombre as name', 'a.id', 'b.descripcion AS pais', 'b.iso3 AS prefijo')
        ->join('pais AS b', 'a.pais_id', 'b.id')
            ->where([['a.deleted_at', null], ['a.pais_id', $pais_id->pais_id]])->get();
        return array(
            "code" => 200,
            "data" => $data,
        );
    }

    public function cambiarStatusConsolidado(Request $request, $document_id)
    {
     $answer = ['code' => 600];
     $data = DocumentoDetalle::where('num_warehouse', $request->warehouse)->first();
     if ($data) {
       //VALIDAR SI EXISTE ESE WHR EN EL CONSOLIDADO
      // $consolidado_detalle = DB::table('consolidado_detalle')
      // ->select('id')->where([
      //  ['documento_detalle_id', $data->id],
      //  ['consolidado_id', $document_id]
      //  ])->first();
      // if ($consolidado_detalle) {
       DB::table('status_detalle')
       ->insert([
        'status_id' => $request->estatus_id,
        'usuario_id' => Auth::id(),
        'documento_detalle_id' => $data->id,
        'codigo' => $request->warehouse,
        'observacion' => $request->observacion
       ]);
       $answer = ['code' => 200];
      // }
     }
     return $answer;
    }
}
