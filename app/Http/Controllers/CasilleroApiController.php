<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Prealerta;

class CasilleroApiController extends Controller
{
    public function getAllWarehouse($user = null, $idStatus = null)
    {
      $columns = ['p.id',
      'p.codigo AS num_warehouse',
      'q.descripcion',
      'q.color',
      'q.icon',
      'p.documento_detalle_id',
      'p.fecha_status',
      'f.contenido',
      'f.peso',
      DB::raw("(SELECT GROUP_CONCAT(tracking.codigo) FROM tracking WHERE tracking.documento_detalle_id = f.id) as tracking")];

      $count = [DB::raw('COUNT(p.id) AS cant'), 'p.status_id'];
      $data = DB::table('status_detalle AS p')
      ->join('status AS q', 'q.id', 'p.status_id')
      ->join(DB::raw("(
        SELECT
          MAX(z.id) AS id_last_status,
          n.consignee_id,
          m.contenido,
          m.id,
          m.peso
        FROM
          status_detalle AS z
        INNER JOIN status AS x ON z.status_id = x.id
        INNER JOIN documento_detalle AS m ON m.id = z.documento_detalle_id
        INNER JOIN documento AS n ON n.id = m.documento_id
        WHERE
          n.consignee_id = $user
        GROUP BY
          n.consignee_id,
          m.id
        ORDER BY
          m.id DESC
            ) AS f"), 'f.id_last_status', 'p.id'
        )
      ->select(
        ($idStatus != 'count') ? $columns : $count
        )->when($idStatus, function ($query, $idStatus) {
          if ($idStatus != 'count') {
            if ($idStatus == 'transito') {
              return $query->where([["p.status_id", "<>", 7],["p.status_id", "<>", 2]]);
            }
            return $query->where("p.status_id", $idStatus);
          }else{
            return $query->groupBy('p.status_id');
          }
        })
        ->get();

        $answer = array(
          "data" => $data,
          "code"  => 200,
        );
        return $answer;
    }

    public function getWarehouse($warehouse, $idStatus)
    {
      $data = DB::table('status_detalle as a')
          ->join('status as b', 'a.status_id', 'b.id')
          ->join('documento_detalle as c', 'a.documento_detalle_id', 'c.id')
          ->join('documento as d', 'c.documento_id', 'd.id')
          ->join('shipper as e', 'd.shipper_id', 'e.id')
          ->join('localizacion as g', 'e.localizacion_id', 'g.id')
          ->join('deptos as h', 'g.deptos_id', 'h.id')
          ->join('pais as i', 'h.pais_id', 'i.id')
          ->leftJoin('tracking as t', 'c.id', 't.documento_detalle_id')
          ->leftJoin('transportadoras_locales as u', 'a.transportadora', 'u.id')
          ->select(
            'a.id',
            'a.status_id',
            'b.descripcion as estado',
            'b.descripcion_general',
            'b.color',
            'b.icon',
            'c.peso',
            'c.num_warehouse',
            'c.num_guia',
            'e.nombre_full AS shipper',
            'a.fecha_status',
            'c.num_consolidado',
            'g.nombre AS ciudad',
            'h.descripcion AS depto',
            'i.descripcion AS pais',
            'u.nombre AS transportadora',
            'u.url_rastreo AS transportadora_url_rastreo',
            'a.num_transportadora AS transportadora_guia',
            DB::raw("(SELECT GROUP_CONCAT(tracking.codigo) FROM tracking WHERE tracking.documento_detalle_id = c.id) as tracking")
          )
          ->where([
              ['c.deleted_at', null],
              ['b.view_client', 1],
          ])
          ->where(function ($query) use ($idStatus, $warehouse) {
              if($idStatus != null && $idStatus != 'null'){
                $query->where("a.status_id", $idStatus);
              }else{
                $query->whereRaw("(c.num_warehouse = '" . $warehouse . "')");
              }
          })
          ->groupBy(
            'a.id',
            'c.id',
            'b.descripcion',
            'b.descripcion_general',
            'b.color',
            'c.peso',
            'c.num_warehouse',
            'c.num_guia',
            'e.nombre_full',
            'a.fecha_status',
            'c.num_consolidado',
            'g.nombre',
            'h.descripcion',
            'i.descripcion',
            'b.icon',
            'a.status_id',
            'u.nombre',
            'u.url_rastreo',
            'a.num_transportadora'
          )->get();

        $trackings = DB::table('tracking AS a')
        ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
        ->select('a.id', 'a.codigo', 'a.contenido', 'a.created_at')
        ->where('b.num_warehouse', $warehouse)
        ->get();
        $answer = ['code' => 200, 'data' => $data, 'trackings' => $trackings];

      return $answer;
    }

    public function getTrackings($value='')
    {
      // code...
    }

    public function getAllPrealert($id_agencia,$consignee_id)
    {
      $data = Prealerta::leftJoin('consignee as b', 'prealerta.consignee_id', 'b.id')
      ->join('agencia as c', 'prealerta.agencia_id', 'c.id')
      ->select('prealerta.*', 'b.nombre_full as consignee', 'c.descripcion as agencia')
      ->where([['prealerta.deleted_at', NULL],['prealerta.recibido', 0],['prealerta.agencia_id', $id_agencia],['prealerta.consignee_id', $consignee_id]])
      ->get();

      $answer = ['code' => 200, 'data' => $data];
      return $answer;
    }

    public function setPrealert(Request $request)
    {
      Prealerta::insert($request->all());
      return $request->all();
    }
}
