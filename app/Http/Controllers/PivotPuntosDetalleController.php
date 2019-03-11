<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\PivotPuntosDetalle;
use Illuminate\Support\Facades\DB;

class PivotPuntosDetalleController extends Controller
{
    public function store(Request $request)
    {
      try {
          $data = (new PivotPuntosDetalle)->fill($request->all());
          $data->save();
          $this->AddToLog('Puntos guardados para el detalle ' . $data->documento_detalle_id);
          $answer = array(
              "code"   => 200,
          );
          return $answer;
      } catch (\Exception $e) {
          $error = $e;
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
        try {
            $data = PivotPuntosDetalle::findOrFail($id);
            $data->delete();
            $this->AddToLog('Puntos eliminados' . json_encode($data));
            $answer = array(
                "datos" => 'Eliminación exitosa.',
                "code"  => 200,
            );
        } catch (\Exception $e) {
            $error = $e;
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
        }
        return $answer;
    }

    public function getByIdDetail($id)
    {
      $data = PivotPuntosDetalle::join('maestra_multiple AS b', 'pivot_puntos_detalle.puntos_id', 'b.id')
      ->select([
          'pivot_puntos_detalle.id',
          'pivot_puntos_detalle.puntos_id',
          'pivot_puntos_detalle.documento_detalle_id',
          'pivot_puntos_detalle.cantidad AS quantity',
          'pivot_puntos_detalle.total_puntos',
          'b.nombre AS category',
          'b.descripcion AS points_total'
        ])
      ->where([
              ['pivot_puntos_detalle.deleted_at', NULL],
              ['pivot_puntos_detalle.documento_detalle_id', $id]
          ]);

      return Datatables::of($data)->make(true);
    }

    public function getProductsClient($id)
    {
      // $ids_detalle = DB::table('documento_detalle AS a')
      // ->select(['a.id'])->where([['a.documento_id', $id]])->get();
      // echo "<pre>";
      // print_r($ids_detalle);
      // echo "</pre>";
      // exit();

      $data = DB::table('productos_detalle_cuba AS a')
      ->leftJoin('puntos_cuba_productos AS b', 'a.producto_cuba_id', 'b.id')
      ->leftJoin('maestra_multiple AS cat', 'b.categoria_id', 'cat.id')
      ->leftJoin('maestra_multiple AS um', 'b.unidad_medida_id', 'um.id')
      ->select([
          'a.id',
          'a.cantidad',
          'a.producto_cuba_id AS producto_id',
          DB::raw('IF(a.producto_cuba_id = 0, a.descripcion, b.articulo) AS articulo'),
          DB::raw('IF(a.producto_cuba_id = 0, 0, b.valor_aduan) AS puntos'),
          'b.limite_come AS limite',
          'cat.nombre AS categoria',
          'um.nombre AS unidad_medida',
          DB::raw('(a.cantidad * IF(a.producto_cuba_id = 0, 0, b.valor_aduan)) AS total_puntos')
        ])
      ->where([['a.documento_id', $id]])->get();

      return $data;
    }
}
