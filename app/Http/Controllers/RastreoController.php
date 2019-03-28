<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RastreoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('templates/rastreo');
    }

    public function getStatusReport($data, $idStatus = null)
    {
        /* ESTATUS DEL DOCUMENTO */
        $datos = DB::table('status_detalle as a')
            ->join('status as b', 'a.status_id', 'b.id')
            ->join('documento_detalle as c', 'a.documento_detalle_id', 'c.id')
            ->join('documento as d', 'c.documento_id', 'd.id')
            ->join('shipper as e', 'd.shipper_id', 'e.id')
            ->join('consignee as f', 'd.consignee_id', 'f.id')
            ->join('localizacion as g', 'f.localizacion_id', 'g.id')
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
              'b.view_client',
              'c.peso',
              'c.num_warehouse',
              'c.num_guia',
              'e.nombre_full AS procedencia',
              'f.nombre_full AS consignee',
              'a.fecha_status',
              'c.num_consolidado',
              'g.nombre AS ciudad',
              'h.descripcion AS depto',
              'i.descripcion AS pais',
              'u.nombre AS transportadora',
              'u.url_rastreo AS transportadora_url_rastreo',
              'a.num_transportadora AS transportadora_guia',
                DB::Raw('YEAR(a.fecha_status) as year_data, MONTH(a.fecha_status) as mont_data, DAY(a.fecha_status) as day_data'),
                DB::Raw("'img','descripcion'"),
                DB::raw("(SELECT GROUP_CONCAT(tracking.codigo) FROM tracking WHERE tracking.documento_detalle_id = c.id) as tracking")
            )
            ->where([
                ['c.deleted_at', null],
                ['b.view_client', 1],
            ])
            ->where(function ($query) use ($idStatus, $data) {
                if($idStatus != null && $idStatus != 'null'){
                  $query->where("a.status_id", $idStatus);
                }else{
                  // $query->whereRaw(" a.status_id IN (1,2, 5, 6, 7,12) AND (c.num_guia = '" . $data . "' OR c.num_warehouse = '" . $data . "' OR t.codigo = '" . $data . "')");
                  $query->whereRaw(" (c.num_guia = '" . $data . "' OR c.num_warehouse = '" . $data . "' OR t.codigo = '" . $data . "')");
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
              'f.nombre_full',
              'a.fecha_status',
              'c.num_consolidado',
              'g.nombre',
              'h.descripcion',
              'i.descripcion',
              'b.icon',
              'b.view_client',
              'a.status_id',
              'u.nombre',
              'u.url_rastreo',
              'a.num_transportadora'
            )->get();

        $answer = array(
            "data" => $datos,
            "code"  => 200,
        );
        return $answer;
    }
}
