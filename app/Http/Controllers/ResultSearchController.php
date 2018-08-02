<?php

namespace App\Http\Controllers;

use App\DocumentoDetalle;
use Illuminate\Support\Facades\DB;

class ResultSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $where = [['documento_detalle.deleted_at', null], ['documento_detalle.id', $id]];

        $detalle = DocumentoDetalle::join('documento', 'documento_detalle.documento_id', '=', 'documento.id')
            ->leftJoin('shipper', 'documento_detalle.shipper_id', '=', 'shipper.id')
            ->leftJoin('consignee', 'documento_detalle.consignee_id', '=', 'consignee.id')
            ->leftJoin('posicion_arancelaria', 'documento_detalle.posicion_arancelaria_id', '=', 'posicion_arancelaria.id')
            ->leftJoin('localizacion AS ciudad_consignee', 'consignee.localizacion_id', '=', 'ciudad_consignee.id')
            ->leftJoin('localizacion AS ciudad_shipper', 'shipper.localizacion_id', '=', 'ciudad_shipper.id')
            ->leftJoin('deptos AS deptos_consignee', 'ciudad_consignee.deptos_id', '=', 'deptos_consignee.id')
            ->leftJoin('deptos AS deptos_shipper', 'ciudad_shipper.deptos_id', '=', 'deptos_shipper.id')
            ->join('maestra_multiple', 'documento_detalle.tipo_empaque_id', '=', 'maestra_multiple.id')
            ->select(
                'documento_detalle.*',
                'posicion_arancelaria.pa AS nom_pa',
                'maestra_multiple.nombre AS empaque',
                'shipper.nombre_full as ship_nomfull',
                'shipper.direccion as ship_dir',
                'shipper.telefono as ship_tel',
                'shipper.correo as ship_email',
                'shipper.zip as ship_zip',
                'ciudad_shipper.nombre AS ship_ciudad',
                'deptos_shipper.descripcion AS ship_depto',
                'consignee.nombre_full as cons_nomfull',
                'consignee.direccion as cons_dir',
                'consignee.telefono as cons_tel',
                'consignee.documento as cons_documento',
                'consignee.correo as cons_email',
                'consignee.zip as cons_zip',
                'consignee.po_box as cons_pobox',
                'ciudad_consignee.nombre AS cons_ciudad',
                'deptos_consignee.descripcion AS cons_depto'
            )
            ->where($where)
            ->get();
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
        return view('templates/resultSearch', compact('detalle', 'status'));
    }
}
