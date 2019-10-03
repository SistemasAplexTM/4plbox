<?php

namespace App\Http\Controllers;

use App\Agencia;
use App\Documento;
use App\DocumentoDetalle;
use App\MaestraMultiple;
use App\Servicios;
use App\TipoDocumento;
use Auth;
use Barryvdh\DomPDF\Facade as PDF;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use JavaScript;
use Redirect;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports/index');
    }

    public function getReportDispatch()
    {
      return DB::table('documento_detalle AS a')
      ->join('documento AS b', 'a.documento_id', 'b.id')
      ->select(
        'a.id',
        'a.num_guia',
          DB::raw("
            (
            	SELECT
            		h.descripcion
            	FROM
            		documento AS c
            		INNER JOIN consignee AS d ON c.consignee_id = d.id
            		INNER JOIN localizacion AS f ON d.localizacion_id = f.id
            		INNER JOIN deptos AS g ON g.id = f.deptos_id
            		INNER JOIN pais AS h ON g.pais_id = h.id
            	WHERE
            		c.id = a.documento_id
          	) AS pais"
          )
        )
      ->whereRaw("a.id NOT IN ( ( SELECT b.documento_detalle_id FROM consolidado_detalle AS b ) )")
      ->get();
    }

    public function getReportDispatchById($id)
    {
      return DB::table('consolidado_detalle AS a')
      ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
      ->select(
        'b.id',
        'b.num_guia',
        DB::raw("
            (
            	SELECT
            		h.descripcion
            	FROM
            		documento AS c
            		INNER JOIN consignee AS d ON c.consignee_id = d.id
            		INNER JOIN localizacion AS f ON d.localizacion_id = f.id
            		INNER JOIN deptos AS g ON g.id = f.deptos_id
            		INNER JOIN pais AS h ON g.pais_id = h.id
            	WHERE
            		c.id = b.documento_id
          	) AS pais"
          )
        )
      ->where('a.consolidado_id', $id)
      ->get();
    }

    public function reportDispatchPrint($id)
    {
      $data = DB::table('consolidado_detalle AS a')
      ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
      ->select(
        'b.id',
        'b.num_guia',
        DB::raw("
            (
            	SELECT
            		h.descripcion
            	FROM
            		documento AS c
            		INNER JOIN consignee AS d ON c.consignee_id = d.id
            		INNER JOIN localizacion AS f ON d.localizacion_id = f.id
            		INNER JOIN deptos AS g ON g.id = f.deptos_id
            		INNER JOIN pais AS h ON g.pais_id = h.id
            	WHERE
            		c.id = b.documento_id
          	) AS pais"
          )
        )
      ->where('a.consolidado_id', $id)
      ->get();

      $pdf = PDF::loadView('reports.printReportDispatch', compact('data'))->setPaper('a4', 'landscape');
      return $pdf->stream('despacho.pdf');
    }

    public function reportDispatch(Request $request)
    {
      $data = $request->all();
      $documento = Documento::create([
        'usuario_id' => auth()->id(),
        'agencia_id' => auth()->user()->agencia_id,
        'tipo_documento_id' => 4
      ])->id;
      $consecutive = DB::select("CALL getConsecutivoByTipoDocumento(?,?,?)", array(4, $documento, date('Y-m-d H:i:s')));
      $consecutivo = $consecutive[0]->consecutivo;
      Documento::where('id', $documento)->update(['consecutivo' => $consecutivo]);
      foreach ($data as $key => $value) {
        DB::table('consolidado_detalle')
        ->insert([
          'consolidado_id' => $documento,
          'documento_detalle_id' => $value['id'],
          'num_bolsa' => 1
        ]);
      }
      return ['code' => 200, 'data' => $documento];
    }

    public function updateReportDispatch(Request $request, $id)
    {
      DB::table('consolidado_detalle')->where('consolidado_id', $id)->delete();
      foreach ($request->data as $key => $value) {
        DB::table('consolidado_detalle')
        ->insert([
          'consolidado_id' => $id,
          'documento_detalle_id' => $value['id'],
          'num_bolsa' => 1
        ]);
      }
      foreach ($request->dataById as $valueById) {
        DB::table('consolidado_detalle')
        ->insert([
          'consolidado_id' => $id,
          'documento_detalle_id' => $valueById['id'],
          'num_bolsa' => 1
        ]);
      }
      return ['code' => 200];
    }
}
