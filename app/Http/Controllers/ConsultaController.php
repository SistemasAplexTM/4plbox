<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{
    public function index()
    {
        return view('templates/consulta');
    }

    public function getAll(Request $request)
    {
        try {
        	$flag = false;
        	$where = [['a.deleted_at', null]];

        	if($request->all()['fechas'] != null){
        		$fechasArray = explode('-', $request->all()['fechas']);
                $fIni = date("Y-m-d", strtotime(trim($fechasArray[0])));
                $fFin = date("Y-m-d", strtotime(trim($fechasArray[1])));
        		$where[] = [DB::raw("b.created_at BETWEEN '".$fIni."' AND '".$fFin."'")];
        		$flag = true;
        	}
        	if($request->all()['shipper_id'] != null){
        		$where[] = ['b.shipper_id', $request->all()['shipper_id']];
        		$flag = true;
        	}
        	if($request->all()['consignee_id'] != null){
        		$where[] = ['b.consignee_id', $request->all()['consignee_id']];
        		$flag = true;
        	}
        	if($request->all()['status_id'] != null){
        		// $where[] = ['b.status_id', $request->all()['status_id']];
        		$flag = true;
        	}
        	if ($flag) {
	            $sql = DB::table('documento_detalle as a')
	                ->join('documento as b', 'a.documento_id', 'b.id')
	                ->join('shipper as c', 'b.shipper_id', 'c.id')
	                ->join('consignee as d', 'b.consignee_id', 'd.id')
	                ->select(
	                    'b.created_at AS fecha',
						'a.num_warehouse',
						'c.nombre_full AS shipper',
						'd.nombre_full AS consignee',
						'a.piezas',
						'a.peso',
						'a.volumen',
	                    DB::raw("(SELECT
									x.descripcion
								FROM
									status_detalle AS z
								INNER JOIN `status` AS x ON z.status_id = x.id
								WHERE
									z.deleted_at IS NULL
								AND z.documento_detalle_id = a.id
								ORDER BY
									z.id DESC
								LIMIT 1
	                        ) AS estado")
	                )
	                ->where($where)
	                ->get();
                return \DataTables::of($sql)->make(true);
        	}else{
        		return array(
	                "data"  => false,
	                "recordsFiltered" => 0,
					"recordsTotal" => 0
	            );
        	}
        } catch (Exception $e) {
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
}
