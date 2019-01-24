<?php

namespace App\Http\Controllers;

use App\Receipt;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
  public function index()
  {
      // $this->assignPermissionsJavascript('tracking');
      return view('templates/receipt');
  }

  public function store(Request $request)
  {
      try {
          $data             = (new Receipt)->fill($request->all());
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

  public function destroy($id)
  {
      $data = Receipt::findOrFail($id);
      $data->delete();
  }

  public function delete($id, $logical)
  {

      if (isset($logical) and $logical == 'true') {
          $data             = Receipt::findOrFail($id);
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

  public function getAll()
  {

      $data = Receipt::leftJoin('consignee AS b', 'tracking.consignee_id', 'b.id')
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
              'c.num_warehouse',
              DB::raw("(
                SELECT
                  b.descripcion
                FROM
                  status_detalle AS a
                INNER JOIN `status` AS b ON a.status_id = b.id
                WHERE
                  a.documento_detalle_id = c.id
                ORDER BY
                  a.id DESC
                LIMIT 1
              ) AS estatus"),
              DB::raw("(
                SELECT
                  b.color
                FROM
                  status_detalle AS a
                INNER JOIN `status` AS b ON a.status_id = b.id
                WHERE
                  a.documento_detalle_id = c.id
                ORDER BY
                  a.id DESC
                LIMIT 1
              ) AS estatus_color")
          )
          ->where($where)
          ->get();
      return \DataTables::of($data)->make(true);
  }
}
