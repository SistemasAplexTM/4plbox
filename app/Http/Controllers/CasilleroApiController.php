<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Prealerta;
use App\Consignee;
use App\AplexConfig;
use App\Agencia;

class CasilleroApiController extends Controller
{
    public function getAllWarehouse($user = null, $idStatus = null)
    {
      $columns = ['p.id',
      // 'p.codigo AS num_warehouse',
      'q.descripcion',
      'q.color',
      'q.icon',
      'p.documento_detalle_id',
      'p.fecha_status',
      'f.contenido',
      'f.num_warehouse',
      'f.peso',
      DB::raw("(SELECT GROUP_CONCAT(tracking.codigo) FROM tracking WHERE tracking.documento_detalle_id = f.id) as tracking")];

      $count = [DB::raw('COUNT(p.id) AS cant'), 'p.status_id'];
      $data = DB::table('status_detalle AS p')
      ->join('status AS q', 'q.id', 'p.status_id')
      ->join(DB::raw("(
        SELECT
          MAX(z.id) AS id_last_status,
          n.consignee_id,
          m.num_warehouse,
          m.contenido,
          m.peso,
          m.id
        FROM
          status_detalle AS z
        INNER JOIN status AS x ON z.status_id = x.id
        INNER JOIN documento_detalle AS m ON m.id = z.documento_detalle_id
        INNER JOIN documento AS n ON n.id = m.documento_id
        WHERE
          n.consignee_id = $user
        GROUP BY
          n.consignee_id,
          m.num_warehouse,
          m.contenido,
          m.peso,
          m.id
        ORDER BY
          m.id DESC
            ) AS f"), 'f.id_last_status', 'p.id'
        )
      ->select(
        ($idStatus != 'count') ? $columns : $count)
        ->when($idStatus, function ($query, $idStatus) {
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
     // DB::connection()->enableQueryLog();
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
          )->where([
              ['c.deleted_at', null],
              ['b.view_client', 1],
          ])->where(function ($query) use ($idStatus, $warehouse) {
              if($idStatus != null && $idStatus != 'null'){
                $query->where("a.status_id", $idStatus);
              }else{
                $query->whereRaw("(c.num_warehouse = '" . $warehouse . "')");
              }
          })->groupBy(
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
          // return DB::getQueryLog();

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
     // DB::connection()->enableQueryLog();
      $data = Prealerta::leftJoin('consignee as b', 'prealerta.consignee_id', 'b.id')
      ->join('agencia as c', 'prealerta.agencia_id', 'c.id')
      ->select('prealerta.*', 'b.nombre_full as consignee', 'c.descripcion as agencia')
      ->where([['prealerta.deleted_at', NULL],['prealerta.recibido', 0],['prealerta.agencia_id', $id_agencia],['prealerta.consignee_id', $consignee_id]])
      ->get();
      // return DB::getQueryLog();
      $answer = ['code' => 200, 'data' => $data];
      return $answer;
    }

    public function getCantPrealert($id_agencia,$consignee_id)
    {
      $data = Prealerta::leftJoin('consignee as b', 'prealerta.consignee_id', 'b.id')
      ->join('agencia as c', 'prealerta.agencia_id', 'c.id')
      ->select('prealerta.*', 'b.nombre_full as consignee', 'c.descripcion as agencia')
      ->where([['prealerta.deleted_at', NULL],['prealerta.recibido', 0],['prealerta.agencia_id', $id_agencia],['prealerta.consignee_id', $consignee_id]])
      ->count();

      $answer = ['code' => 200, 'data' => $data];
      return $answer;
    }

    public function setPrealert(Request $request)
    {
      $data = Prealerta::where('tracking', $request->tracking)->first();
      if ($data) {
        return ['message' => 'El número de tracking ya existe en nuestra base de datos.'];
      }
      Prealerta::insert($request->all());
      return $request->all();
    }

    public function updateUser(Request $request)
    {
      Consignee::where('id', $request->id)->update([
       'tipo_identificacion_id' => $request->tipo_identificacion_id,
       'agencia_id' => $request->agencia_id,
       'localizacion_id' => $request->localizacion_id,
       'documento' => $request->documento,
       'primer_nombre' => $request->primer_nombre,
       'segundo_nombre' => $request->segundo_nombre,
       'primer_apellido' => $request->primer_apellido,
       'segundo_apellido' => $request->segundo_apellido,
       'direccion' => $request->direccion,
       'telefono' => $request->telefono,
       'celular' => $request->celular,
       'correo' => $request->correo,
       'zip' => $request->zip,
       'tarifa' => $request->tarifa,
       'po_box' => $request->po_box,
       'estatus' => $request->estatus,
       'nombre_full' => $request->nombre_full,
       'casillero' => $request->casillero,
       'password_casillero' => $request->password_casillero,
       'direccion2' => $request->direccion2,
       'acepta_condiciones' => $request->acepta_condiciones,
       'recibir_info' => $request->recibir_info,
       'cliente_id' => $request->cliente_id,
      ]);
      return ['code' => 200];
    }

    public function findUser($id)
    {
     // DB::connection()->enableQueryLog();
      $data = Consignee::where('id',$id)->with('city')->get();
      // return DB::getQueryLog();
      return ['code' => 200, 'data' => $data];
    }

    public function getContacts($id)
    {
      $data = Consignee::find($id);
      return ['code' => 200, 'data' => $data];
    }

    public function setContacts(Request $request, $id)
    {
      $data = Consignee::where('id', $id)->update([
       'contactos_json' => json_encode(["campos" => [$request->all()]])
      ]);
      return ['code' => 200, 'data' => $data];
    }

    public function getUrlZopim($agency_id)
    {
      $data = AplexConfig::where('key', 'zopim_script_'.$agency_id)->first();
      if ($data) {
        return ['code' => 200, 'url' => $data->value];
      }
      return ['code' => 200];
    }

    public function getPaypal($agency_id)
    {
      $data = AplexConfig::where('key', 'agency_paypal_'.$agency_id)->first();
      return ['code' => 200, 'data' => $data];
    }

    public function getLogo($agency_id)
    {
      $data = Agencia::where('id', $agency_id)->first();
      return ['code' => 200, 'data' => $data->logo];
    }
}
