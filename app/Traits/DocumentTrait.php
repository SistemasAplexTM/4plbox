<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DocumentoDetalle;

trait DocumentTrait
{
    public function getAllLoad($where, $filter)
    {
      $dates = false;
      if($filter['dates'] == '' and $filter['warehouse'] == '' and $filter['consignee_id'] == ''){
        $fFin = strtotime('+1 day' , strtotime(date('Y-m-d')));
        $fFin = date('Y-m-d' , $fFin);
        $nuevafecha = strtotime('-2 day' , strtotime($fFin)) ;
        $fIni = date('Y-m-d' , $nuevafecha);
        $dates = array(
          'inicio' => $fIni,
          'fin' => $fFin,
        );
      }else{
        if($filter['dates'] != ''){
          $dates = array(
            'inicio' => $filter['dates'][0],
            'fin' => $filter['dates'][1],
          );
        }
      }
      $where[] = ['b.carga_courier', 0];
      $sql = DB::table('documento AS b')
          ->leftJoin('shipper', 'b.shipper_id', '=', 'shipper.id')
          ->leftJoin('consignee', 'b.consignee_id', '=', 'consignee.id')
          ->leftJoin('clientes AS cl', 'cl.id', 'consignee.cliente_id')
          ->join('agencia AS e', 'b.agencia_id', '=', 'e.id')
          ->leftJoin(DB::raw("(SELECT
                                z.id As detalle_id,
                                Count(DISTINCT z.consolidado) AS consolidado,
                                z.consolidado AS consolidado_status,
                                z.agrupado,
                                z.flag,
                                z.num_guia,
                                z.num_warehouse,
                                z.documento_id
                              FROM
                                documento_detalle AS z
                              WHERE
                                z.deleted_at IS NULL
                              GROUP BY
                                z.documento_id,
                                z.id,
                                z.consolidado,
                                z.agrupado,
                                z.num_guia,
                                z.num_warehouse,
                                z.peso,
                                z.valor,
                                z.flag
                              ) AS t"), "b.id", "t.documento_id")
          ->select('b.id as id', 'b.valor_libra', 'b.valor', 'b.liquidado', 'b.tipo_documento_id as tipo_documento_id',
          'b.consecutivo as codigo',
           'b.created_at AS fecha',
           'b.num_warehouse AS warehouse',
           'b.consignee_id',
           'cl.nombre AS cliente',
           'shipper.nombre_full as ship_nomfull', 'consignee.nombre_full as cons_nomfull',
           'consignee.correo as email_cons', 'e.descripcion as agencia',
              DB::raw("(SELECT Count(a.id) AS cantidad FROM documento_detalle AS a WHERE a.documento_id = b.id AND a.deleted_at IS NULL) as cantidad"),
              DB::raw("(SELECT IFNULL(SUM(a.piezas), 0) AS piezas FROM documento_detalle AS a WHERE a.documento_id = b.id AND a.deleted_at IS NULL) as piezas"),
              DB::raw("(SELECT Sum(documento_detalle.peso) FROM documento_detalle WHERE documento_detalle.documento_id = b.id AND documento_detalle.deleted_at IS NULL) as peso"),
              DB::raw("(SELECT Sum(documento_detalle.volumen) FROM documento_detalle WHERE documento_detalle.documento_id = b.id AND documento_detalle.deleted_at IS NULL) as volumen"),
              DB::raw("0 as num_guia"),
              DB::raw("0 as agrupadas"),
              'b.num_warehouse',
              DB::raw("SUM(t.consolidado_status) AS consolidado_status"),
              'b.carga_courier',
              DB::raw('"ciudad" AS ciudad')
          )
          ->where($where)
          ->when($filter['warehouse'], function ($query, $data) {
            return $query->where('b.num_warehouse', 'LIKE', "%".$data."");
          })
          ->when($dates, function ($query, $data) {
            return $query->whereBetween('b.created_at', [$data['inicio'],$data['fin']]);
          })
          ->when($filter['consignee_id'], function ($query, $data) {
            return $query->where('b.consignee_id', $data);
          })
          ->groupBy(
              'b.id',
              'b.liquidado',
              'b.tipo_documento_id',
              'b.consecutivo',
              'b.valor_libra',
              'b.valor',
              'b.num_warehouse',
              'b.created_at',
              'b.consignee_id',
              'shipper.nombre_full',
              'consignee.nombre_full',
              'cliente',
              'consignee.correo',
              'e.descripcion',
              'b.carga_courier'
          )
          ->orderBy('b.created_at', 'DESC');
        return $sql;
    }

    public function getAllCourier($where, $filter, $type)
    {
      $dates = false;
      if($filter['dates'] == '' and $filter['warehouse'] == '' and $filter['consignee_id'] == '' and $type != 4){
        $fFin = strtotime('+1 day' , strtotime(date('Y-m-d')));
        $fFin = date('Y-m-d' , $fFin);
        $nuevafecha = strtotime('-2 day' , strtotime($fFin));
        $fIni = date('Y-m-d' , $nuevafecha);
        $dates = array(
          'inicio' => $fIni,
          'fin' => $fFin,
        );
      }else{
        if($filter['dates'] != '' and $type != 4){
          $dates = array(
            'inicio' => $filter['dates'][0],
            'fin' => $filter['dates'][1],
          );
        }else{
          if($type == 4){
            $fFin = strtotime('+5 day' , strtotime(date('Y-m-d')));
            $fFin = date('Y-m-d' , $fFin);
            $nuevafecha = strtotime('-6 day' , strtotime($fFin));
            $fIni = date('Y-m-d' , $nuevafecha);
            $dates = array(
              'inicio' => $fIni,
              'fin' => $fFin,
            );
          }
        }
      }

      if ($type != 4) {
        $where[] = ['b.carga_courier', 1];
      }

        $label_1 = "<a style='float:right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerDocumentoAgrupado(";
        $label_2 = ")'><i class='fa fa-times' style='font-size: 15px;'></i></a>";

        $qr_group_1 = DB::raw('(
                  SELECT
                    GROUP_CONCAT(
                      CONCAT(
                        "<label>- ",
                        x.num_warehouse,
                        " (",
                        x.peso,
                        " lbs) ",
                        " ($ ",
                        x.valor,
                        ".00) </label>",
                        "'.$label_1.'",
                        x.id,
                        "'.$label_2.'"
                      )
                    ) AS groupy
                  FROM
                    documento_detalle AS x
                  WHERE
                    x.deleted_at IS NULL
                  AND x.agrupado = a.id
                  AND x.flag = 1
                ) AS guias_agrupadas');

        $qr_group = DB::raw('(
                  SELECT
                    GROUP_CONCAT(
                      CONCAT(
                        x.num_warehouse,
                        "@",
                        x.peso,
                        "@",
                        x.valor,
                        "@",
                        x.id
                      )
                    ) AS groupy
                  FROM
                    documento_detalle AS x
                  WHERE
                    x.deleted_at IS NULL
                  AND x.agrupado = a.id
                  AND x.flag = 1
                ) AS guias_agrupadas');

        $sql = DB::table('documento AS b')
          ->leftJoin('documento_detalle AS a', 'b.id', 'a.documento_id')
          ->leftJoin('consignee AS c', 'b.consignee_id', 'c.id')
          ->leftJoin('clientes AS cl', 'cl.id', 'c.cliente_id')
          ->leftJoin('shipper AS d', 'b.shipper_id', 'd.id')
          ->leftJoin('agencia AS e', 'b.agencia_id', 'e.id')
          ->select(
            	'a.id AS detalle_id',
              'b.id',
            	'b.valor_libra',
            	'b.valor',
            	'b.liquidado',
            	'b.tipo_documento_id',
            	'b.consecutivo AS codigo',
            	'b.created_at AS fecha',
            	'b.num_warehouse AS warehouse',
              'b.consignee_id',
            	'c.nombre_full AS cons_nomfull',
            	'c.correo AS email_cons',
              'cl.nombre AS cliente',
            	'd.nombre_full AS ship_nomfull',
            	'e.descripcion AS agencia',
            	'a.piezas',
            	'a.peso',
            	'a.volumen',
            	'a.num_warehouse',
            	'a.num_guia',
            	'a.consolidado AS consolidado_status',
            	'a.agrupado',
            	'a.flag',
            	'b.carga_courier',
              DB::raw("(SELECT
                    			x.descripcion AS estatus
                    		FROM
                    			status_detalle AS z
                    		INNER JOIN `status` AS x ON z.status_id = x.id
                    		WHERE
                    			z.documento_detalle_id = a.id
                    		ORDER BY
                    			z.id DESC
                    		LIMIT 1
                    	) AS estatus"),
              DB::raw("(SELECT
                    			x.color AS estatus_color
                    		FROM
                    			status_detalle AS z
                    		INNER JOIN `status` AS x ON z.status_id = x.id
                    		WHERE
                    			z.documento_detalle_id = a.id
                    		ORDER BY
                    			z.id DESC
                    		LIMIT 1
                    	) AS estatus_color"),
              DB::raw('(SELECT
                          Count(z.id)
                        FROM
                          documento_detalle AS z
                        WHERE
                          z.deleted_at IS NULL
                          AND z.agrupado = a.id
                          AND z.flag = 1
                        ) AS agrupadas'),
              DB::raw('(SELECT
                          z.num_warehouse
                        FROM
                          documento_detalle AS z
                        WHERE
                          z.deleted_at IS NULL
                          AND z.id = a.agrupado
                          AND z.flag = 0
                        ) AS padre'),
                $qr_group,
                'a.mintic',
                DB::raw('"ciudad" AS ciudad')
            )
            ->where($where)
            ->when($filter['warehouse'], function ($query, $data) {
              return $query->where('a.num_warehouse', 'LIKE', "%".$data."%");
            })
            ->when($dates, function ($query, $data) {
              return $query->whereBetween('b.created_at', [$data['inicio'],$data['fin']]);
            })
            ->when($filter['consignee_id'], function ($query, $data) {
              return $query->where('b.consignee_id', $data);
            })
            ->orderBy('a.agrupado', 'DESC')
            ->orderBy('a.flag', 'ASC')
            ->orderBy('b.created_at', 'ASC');
        return $sql;
    }

    public function getAllConsolidated($filter)
    {
      $sql    = DB::table('documento AS b')
          ->leftJoin('shipper', 'b.shipper_id', '=', 'shipper.id')
          ->leftJoin('consignee AS c', 'b.consignee_id', '=', 'c.id')
          ->leftJoin('localizacion as l', 'b.ciudad_id', 'l.id')
          ->join('agencia AS e', 'b.agencia_id', '=', 'e.id')
          ->leftJoin('transportador as central_destino', 'b.central_destino_id', '=', 'central_destino.id')
          ->select('b.id as id', 'b.transporte_id', 'central_destino.nombre as central_destino', 
          'valor_libra', 'b.valor', 'b.liquidado', 'b.tipo_documento_id as tipo_documento_id', 'b.consecutivo as codigo',
           'b.created_at as fecha', 'shipper.nombre_full as ship_nomfull', 'c.nombre_full as cons_nomfull', 
           'c.correo as email_cons', 'e.descripcion as agencia',
              DB::raw("(SELECT IFNULL(COUNT(consolidado_detalle.id),0) FROM consolidado_detalle WHERE consolidado_detalle.deleted_at IS NULL AND consolidado_detalle.consolidado_id = b.id) as cantidad"),
              DB::raw("(SELECT IFNULL(Sum(documento_detalle.peso2),0) FROM consolidado_detalle INNER JOIN documento_detalle ON consolidado_detalle.documento_detalle_id = documento_detalle.id WHERE consolidado_detalle.deleted_at IS NULL AND consolidado_detalle.consolidado_id = b.id) as peso"),
              DB::raw("(SELECT IFNULL(Sum(documento_detalle.volumen),0) FROM consolidado_detalle INNER JOIN documento_detalle ON consolidado_detalle.documento_detalle_id = documento_detalle.id WHERE consolidado_detalle.deleted_at IS NULL AND consolidado_detalle.consolidado_id = b.id) as volumen"),
              DB::raw('(SELECT
                  ROUND(Sum(b.peso2) * 0.453592) AS peso_total
                FROM
                  consolidado_detalle AS a
                INNER JOIN documento_detalle AS b ON a.documento_detalle_id = b.id
                WHERE
                  a.deleted_at IS NULL
                AND b.deleted_at IS NULL
                AND a.consolidado_id = b.id
              ) AS peso_total'),
              DB::raw('(SELECT
                  Sum(b.declarado2) AS declarado_total
                FROM
                  consolidado_detalle AS a
                INNER JOIN documento_detalle AS b ON a.documento_detalle_id = b.id
                WHERE
                  a.deleted_at IS NULL
                AND b.deleted_at IS NULL
                AND a.consolidado_id = b.id
              ) AS declarado_total'),
              'l.nombre AS ciudad'
          )
          ->where($filter)
          ->orderBy('b.created_at', 'DESC');
          return $sql;
    }

    public function pdfLabelDetail($filter, $codigo, $consolidado = false, $id_detail_consol = false)
    {
      $sql = DocumentoDetalle::join('documento as a', 'documento_detalle.documento_id', 'a.id')
          ->join('agencia AS b', 'a.agencia_id', 'b.id')
          ->leftJoin('shipper', 'documento_detalle.shipper_id', '=', 'shipper.id')
          ->leftJoin('consignee', 'documento_detalle.consignee_id', '=', 'consignee.id')
          ->leftJoin('localizacion AS ciudad_consignee', 'consignee.localizacion_id', '=', 'ciudad_consignee.id')
          ->leftJoin('localizacion AS ciudad_shipper', 'shipper.localizacion_id', '=', 'ciudad_shipper.id')
          ->leftJoin('deptos AS deptos_consignee', 'ciudad_consignee.deptos_id', '=', 'deptos_consignee.id')
          ->leftJoin('deptos AS deptos_shipper', 'ciudad_shipper.deptos_id', '=', 'deptos_shipper.id')
          ->leftJoin('pais', 'pais.id', '=', 'deptos_consignee.pais_id')
          ->select(
              'documento_detalle.id',
              'documento_detalle.contenido',
              'documento_detalle.contenido2',
              // 'documento_detalle.tracking',
              'documento_detalle.volumen',
              'documento_detalle.valor',
              'documento_detalle.declarado2',
              'documento_detalle.piezas',
              'documento_detalle.largo',
              'documento_detalle.ancho',
              'documento_detalle.alto',
              'documento_detalle.peso',
              'documento_detalle.peso2',
              'documento_detalle.' . $codigo . ' as codigo',
              'documento_detalle.num_warehouse',
              'documento_detalle.num_guia',
              'documento_detalle.created_at',
              'b.descripcion as agencia',
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
              'deptos_consignee.descripcion AS cons_depto',
              'pais.descripcion AS cons_pais',
              'pais.iso2 AS cons_pais_code',
              'ciudad_consignee.prefijo',
              DB::raw("(SELECT GROUP_CONCAT(tracking.codigo) FROM tracking WHERE tracking.documento_detalle_id = documento_detalle.id) as tracking")
          )
          ->where($filter)
          ->get();
          return $sql;
    }

    public function pdfLabelDetailConsolidado($filter, $codigo)
    {
      $sql = DB::table('consolidado_detalle AS c')
          ->join('documento_detalle as d', 'c.documento_detalle_id', 'd.id')
          ->join('documento as a', 'd.documento_id', 'a.id')
          ->join('agencia AS b', 'a.agencia_id', 'b.id')
          ->leftJoin('shipper', 'd.shipper_id', '=', 'shipper.id')
          ->leftJoin('consignee', 'd.consignee_id', '=', 'consignee.id')
          ->leftJoin('localizacion AS ciudad_consignee', 'consignee.localizacion_id', '=', 'ciudad_consignee.id')
          ->leftJoin('localizacion AS ciudad_shipper', 'shipper.localizacion_id', '=', 'ciudad_shipper.id')
          ->leftJoin('deptos AS deptos_consignee', 'ciudad_consignee.deptos_id', '=', 'deptos_consignee.id')
          ->leftJoin('deptos AS deptos_shipper', 'ciudad_shipper.deptos_id', '=', 'deptos_shipper.id')
          ->leftJoin('pais', 'pais.id', '=', 'deptos_consignee.pais_id')
          ->select(
              'd.id',
              'd.contenido',
              'd.contenido2',
              'd.tracking',
              'd.volumen',
              'd.valor',
              'd.declarado2',
              'd.piezas',
              'd.largo',
              'd.ancho',
              'd.alto',
              'd.peso',
              'd.peso2',
              'd.' . $codigo . ' as codigo',
              'd.num_warehouse',
              'd.num_guia',
              'd.created_at',
              'b.descripcion as agencia',
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
              'deptos_consignee.descripcion AS cons_depto',
              'pais.descripcion AS cons_pais',
              'pais.iso2 AS cons_pais_code',
              'ciudad_consignee.prefijo'
          )
          ->where($filter)
          ->get();
          return $sql;
    }

}
