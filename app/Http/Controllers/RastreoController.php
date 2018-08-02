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

    public function getStatusReport($data)
    {
        /* ESTATUS DEL DOCUMENTO */
        $data = DB::table('status_detalle as a')
            ->join('status as b', 'a.status_id', 'b.id')
            ->join('documento_detalle as c', 'a.documento_detalle_id', 'c.id')
            ->join('documento as d', 'c.documento_id', 'd.id')
            ->join('shipper as e', 'd.shipper_id', 'e.id')
            ->join('consignee as f', 'd.consignee_id', 'f.id')
            ->join('localizacion as g', 'f.localizacion_id', 'g.id')
            ->join('deptos as h', 'g.deptos_id', 'h.id')
            ->join('pais as i', 'h.pais_id', 'i.id')
            ->leftJoin('tracking as t', 'c.id', 't.documento_detalle_id')
            ->select(
                'a.id',
                'b.descripcion as estado',
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
                DB::Raw('YEAR(a.fecha_status) as year_data, MONTH(a.fecha_status) as mont_data, DAY(a.fecha_status) as day_data'),
                DB::Raw("'img','descripcion'"),
                DB::raw("(SELECT GROUP_CONCAT(tracking.codigo) FROM tracking WHERE tracking.documento_detalle_id = c.id) as tracking")
            )
            ->where([
                ['c.deleted_at', null]
            ])
            ->whereRaw(
                " a.status_id IN (1,2, 5, 6, 7,12) AND (c.num_guia = '" . $data . "' OR c.num_warehouse = '" . $data . "' OR t.codigo = '" . $data . "')"
            )
            ->groupBy(
                'a.id',
                'c.id',
                'b.descripcion',
                'c.peso',
                'c.num_warehouse',
                'c.num_guia',
                'e.nombre_full',
                'f.nombre_full',
                'a.fecha_status',
                'c.num_consolidado',
                'g.nombre',
                'h.descripcion',
                'i.descripcion'
            )
            ->get();
        $answer = array(
            "data" => $data,
            "code"  => 200,
        );
        return $answer;
    }
}
