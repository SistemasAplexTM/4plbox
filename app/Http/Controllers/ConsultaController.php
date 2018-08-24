<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Auth;

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
        	$where = null;
        	$having = null;

        	if($request->all()['fechas'] != null){
        		$fechasArray = explode('-', $request->all()['fechas']);
                $fIni = date("Y-m-d", strtotime(trim($fechasArray[0])));
                $fFin = date("Y-m-d", strtotime(trim($fechasArray[1])));
        		$where .= " AND (DATE_FORMAT(b.created_at,'%Y-%m-%d') BETWEEN '".$fIni."' AND '".$fFin."')";
        		$flag = true;
        	}
        	if($request->all()['shipper_id'] != null){
        		$where .= " AND b.shipper_id = " . $request->all()['shipper_id'];
        		$flag = true;
        	}
        	if($request->all()['consignee_id'] != null){
        		$where .= " AND b.consignee_id = " . $request->all()['consignee_id'];;
        		$flag = true;
        	}
        	if($request->all()['status_id'] != null){
        		$having = " HAVING (SELECT x.id FROM status_detalle AS z INNER JOIN status AS x ON z.status_id = x.id WHERE z.deleted_at IS NULL AND z.documento_detalle_id = a.id ORDER BY z.id DESC LIMIT 1) = ". $request->all()['status_id'];
        		$flag = true;
        	}
        	if ($flag) {
        		$sql = DB::select(DB::raw("SELECT DATE_FORMAT(b.created_at,'%Y-%m-%d') AS fecha, a.num_warehouse, c.nombre_full AS shipper, d.nombre_full AS consignee, a.piezas, a.peso, a.volumen, (SELECT x.descripcion FROM status_detalle AS z INNER JOIN status AS x ON z.status_id = x.id WHERE z.deleted_at IS NULL AND z.documento_detalle_id = a.id ORDER BY z.id DESC LIMIT 1) AS estado FROM documento_detalle AS a INNER JOIN documento AS b ON a.documento_id = b.id INNER JOIN shipper AS c ON b.shipper_id = c.id INNER JOIN consignee AS d ON b.consignee_id = d.id WHERE a.deleted_at IS NULL $where $having" ));
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

    public function pdf(Request $request)
    {
        $data = $this->getAll($request);
        $agencia = $this->getDataAgenciaById(true);
        if(isset($data->original['data'])){
            $data = json_decode(json_encode($data->original['data']));
        }else{
            $data = false;
        }
        $pdf = PDF::loadView('pdf.consulta', compact('data', 'agencia'))->setPaper('a4', 'landscape');
        return $pdf->stream('Informe bodega.pdf');
    }

}
