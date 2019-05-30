<?php

namespace App\Http\Controllers;

use App\Receipt;
use App\ReceiptDetail;
use App\StatusReport;
use App\Agencia;
use App\Consignee;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
  public function index()
  {
      // $this->assignPermissionsJavascript('tracking');
      $consignees = $this->getConsignee();
      return view('templates/receipt', compact('consignees'));
  }

  public function store(Request $request)
  {
    try {
      // $data = $request->data;
      // $data = Receipt::create([
      //   'agencia_id' => Auth::user()->agencia_id,
      //   'consignee_id' => $data['id_client'],
      //   'usuario_id' => Auth::id(),
      //   'numero_recibo' => 1,
      //   'cliente' => 'El cliente tales',
      //   'cliente_datos' => json_encode($data['data_client']),
      //   'transportador' => $data['transportador'],
      // ]);
      // Receipt::where('id', $data->id)->update([
      //   'numero_recibo' =>  $data->id,
      // ]);
      $answer = array(
          'id' => $data->id,
          "code"   => 200,
          "status" => 200,
      );
      return $answer;
    } catch (\Exception $e) {
      $answer = array(
          "error"  => $e,
          "code"   => 600,
          "status" => 500,
      );
      return $answer;
    }
  }

  public function storeDeail(Request $request)
  {
    // return $request->all();
    try {
      $data = Receipt::create([
        'agencia_id' => Auth::user()->agencia_id,
        'consignee_id' => $request->head['id_client'],
        'usuario_id' => Auth::id(),
        'numero_recibo' =>  1,
        'cliente' => $request->head['client'],
        'cliente_datos' => json_encode($request->head['data_client']),
        'transportador' => $request->head['transportador'],
      ]);

      foreach ($request->detalle as $value) {
        ReceiptDetail::create([
          'factura_id' => $data->id,
          'documento_detalle_id' => $value['id'],
          'entregado' => $request->head['entregado'],
          'cantidad' => 1,
          'trackings' => $value['trackings'],
          'observacion' => ''
        ]);
      }
      Receipt::where('id', $data->id)->update([
        'numero_recibo' =>  $data->id,
      ]);
      $answer = array(
          "code"   => 200,
          "status" => 200,
      );
      return $answer;
    } catch (\Exception $e) {
      $answer = array(
          "error"  => $e,
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
    $data = Receipt::leftJoin('consignee AS b', 'factura.consignee_id', 'b.id')
        ->leftJoin('localizacion AS c', 'b.localizacion_id', 'c.id')
        ->select(
            'factura.consignee_id',
            'factura.cliente',
            'factura.cliente_datos',
            'factura.id',
            'factura.numero_recibo',
            'b.nombre_full AS consignee',
            'b.direccion',
            'b.telefono',
            'b.correo',
            'c.nombre AS ciudad'
        )
        ->where([['factura.deleted_at', NULL]])
        ->get();
    return \DataTables::of($data)->make(true);
  }

  public function getConsignee($data = false)
  {
    $data = DB::table('consignee AS a')
        ->join('localizacion AS b', 'a.localizacion_id', 'b.id')
        ->select(
            'a.id',
            'a.nombre_full AS name',
            'a.direccion',
            'a.telefono',
            'a.correo',
            'b.nombre AS ciudad'
        )
        ->where([['a.deleted_at', NULL]])
        // ->whereRaw('a.nombre_full LIKE \'%' . $data . '%\'')
        ->get();
        return $data;
  }

  public function searchDocument($document)
  {
    $data = DB::table('documento_detalle AS a')
        ->select(
            'a.id',
            'a.num_warehouse AS warehouse',
            DB::raw("(
        			SELECT
        				GROUP_CONCAT(b.codigo)
        			FROM
        				tracking AS b
        			WHERE
        				b.deleted_at IS NULL
        			AND b.documento_detalle_id = a.id
        	) AS trackings")
        )
        ->where([['a.deleted_at', NULL], ['a.num_warehouse', $document]])
        ->first();
        if ($data) {
          $answer = array('code' => 200, 'msg' => 'Correcto', 'data' => $data);
          if ($this->intReceipt($data->id)) {
            $recibo = $this->intReceipt($data->id);
            $answer = array('code' => 300, 'msg' => "EL warehpuse ya está en el recibo $recibo->factura_id");
          }
        }else{
          $answer = array('code' => 404, 'msg' => 'No se encuentra el warehouse');
        }

      return $answer;
  }

  public function searchReceiptDetail($id_receipt)
  {
    $data = DB::table('factura_detalle AS a')
      ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
        ->select(
            'a.id',
            'a.factura_id',
            'a.documento_detalle_id',
            'b.num_warehouse AS warehouse',
            'a.entregado',
            'a.cantidad',
            'a.trackings',
            'a.observacion'
        )
        ->where([['a.deleted_at', NULL], ['a.factura_id', $id_receipt]])
        ->get();
      return array('data' => $data);
  }

  public function getDocument($id)
  {
    // $data = Receipt::leftjoin('consignee AS b', 'factura.consignee_id', 'b.id')
    // ->select('factura.agencia_id',
    // 'factura.consignee_id',
    // 'factura.usuario_id',
    // 'factura.numero_recibo',
    // 'factura.cliente',
    // 'factura.cliente_datos',
    // 'factura.transportador',
    // 'factura.created_at',
    // 'b.nombre_full AS consignee')
    // ->where('factura.id', $id)
    // ->get();
    // return $data;
    return Receipt::find($id);
  }

  public function checkReceipt(Request $request)
  {
    ReceiptDetail::where('id', $request->id)->update([
      'entregado' => 1
    ]);

    StatusReport::create([
      'status_id' => 9,
      'usuario_id' => Auth::id(),
      'documento_detalle_id' => $request->id_doc,
      'codigo' => $request->warehouse,
      // 'fecha_status' => date('Y-m-d:hh:mm:ss'),
      'observacion' => $request->status
    ]);
    $this->AddToLog('Revisando documento ' . $request->warehouse);

    return array('code' => 200);
  }

  public function printReceipt($id)
  {
    $agencia = Agencia::find(Auth::user()->agencia_id);
    $recibo = Receipt::leftjoin('consignee AS b', 'factura.consignee_id', 'b.id')
    ->join('users AS c', 'factura.usuario_id', 'c.id')
    ->select('factura.agencia_id',
    'factura.consignee_id',
    'factura.usuario_id',
    'factura.numero_recibo',
    'factura.cliente',
    'factura.cliente_datos',
    'factura.transportador',
    'factura.created_at',
    'b.nombre_full AS consignee',
    'c.name')
    ->where('factura.id', $id)
    ->first();
    $reciboD = ReceiptDetail::where([['factura_id', $id], ['factura_detalle.deleted_at', null]])
    ->join('documento_detalle AS b', 'documento_detalle_id', 'b.id')
    ->get();
    $cliente = json_decode($recibo->cliente_datos);
    $consignee = Consignee::find($recibo->consignee_id);
    return view('pdf.recibo', compact('agencia', 'recibo', 'reciboD', 'cliente', 'consignee'));
  }

  public function intReceipt($idWarehouse)
  {
    return ReceiptDetail::where('documento_detalle_id', $idWarehouse)->first();
  }

  public function changeStatus($id)
  {
    $data = ReceiptDetail::where('factura_id', $id)->get();
    if($data){
      foreach ($data as $value) {
        DB::table('status_detalle')->insert([
          [
            'status_id'            => 7,
            'usuario_id'           => Auth::user()->id,
            'documento_detalle_id' => $value->documento_detalle_id,
            'codigo'               => '',
            'fecha_status'         => date('Y-m-d H:i:s'),
            'observacion'          => 'Se agrego desde el recibo de entrega',
          ],
        ]);
      }
    }

  }
}
