<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DocumentoDetalle;

class MinticController extends Controller
{
    public function index()
    {
      return view('templates.mintic');
    }

    public function all(Request $request)
    {
      if ($request->fechas != '') {
          $fechasArray = explode('-', $request->fechas);
          $fechaInicio = date("Y-m-d", strtotime(trim($fechasArray[0])));
          $fechaFin = date("Y-m-d", strtotime(trim($fechasArray[1])));
      }
      $data = DB::table('documento_detalle AS a')
      ->select(
        'a.agrupado',
        'a.num_warehouse',
        'a.mintic',
        DB::raw("DATE_FORMAT(a.created_at,'%Y-%m-%d') AS created_at")
        )->where([
          ['a.mintic', '<>', NULL],
          ['a.deleted_at', NULL]
        ])->whereRaw("(DATE_FORMAT(a.created_at,'%Y-%m-%d') BETWEEN '$fechaInicio' AND '$fechaFin') ")->get();

      return \DataTables::of($data)->make(true);;
    }

    public function searchDocument($document)
    {
      $data = DB::table('documento_detalle AS a')
          ->select(
              'a.id',
              'a.peso',
              'a.contenido',
              'a.agrupado',
              'a.flag',
              'a.num_warehouse AS warehouse',
              DB::raw("(
          			SELECT
          				b.num_warehouse
          			FROM
          				documento_detalle AS b
          			WHERE
          				b.deleted_at IS NULL
          			AND b.id = a.agrupado
                LIMIT 1
          	) AS warehouse_agrupado")
          )
          ->where([
            ['a.num_warehouse', $document],
            ['a.mintic', NULL],
            ['a.deleted_at', NULL]
           ])
          ->first();
          if ($data) {
            $answer = array('code' => 200, 'msg' => 'Correcto', 'data' => $data);
            if ($data->id != $data->agrupado) {
              $answer = array('code' => 300, 'msg' => "EL warehpuse ya está agrupado. Núm. warehouse:  $data->warehouse_agrupado");
            }
          }else{
            $answer = array('code' => 404, 'msg' => 'No se encuentra el warehouse', 'data' => $data);
          }

        return $answer;
    }
    public function createDetail(Request $request)
    {
      return $request->all();
      try {
        //
        // alto: "0"
        // ancho: "0"
        // arancel_id2: 245
        // contenido: "ropa"
        // contenido2: "ropa"
        // dimensiones: "12 Vol=0x0x0"
        // documento_id: "11"
        // largo: "0"
        // peso: "12"
        // peso2: "12"
        // posicion_arancelaria_id: 245
        // tipo_empaque_id: 9


        for ($z=0; $z <= count($request->detail); $z++) {
          $data = (new DocumentoDetalle)->fill($request->detail[$z]);

          $data->status_id = 2;
          if ($request->valor == '') {
              $data->valor = 0;
          }
          if ($request->piezas == '') {
              $data->piezas = 1;
          }
          if ($request->declarado2 == '') {
              $data->declarado2 = 0;
          }

          $data->created_at = $request->created_at;
          if ($data->tracking == '') {
              $data->tracking = null;
          }

          /* OBTENER EL PREFIJO DE LA CIUDAD DEL CONSIGNEE PARA HACER EL NUMERO DE GUIA */
          $prefijoGuia = DB::table('consignee as a')
              ->join('localizacion as b', 'a.localizacion_id', 'b.id')
              ->join('deptos as c', 'b.deptos_id', 'c.id')
              ->join('pais as d', 'c.pais_id', 'd.id')
              ->select('b.prefijo', 'd.iso2')
              ->where([
                  ['a.deleted_at', null],
                  ['a.id', $request->consignee_id],
              ])
              ->first();

          $documento = Documento::findOrFail($data->documento_id);

          $documentoD = DocumentoDetalle::select('documento_detalle.id')
              ->where([
                  ['documento_detalle.documento_id', $data->documento_id],
              ])->get();
          // $data->num_guia      = $documento->num_guia . '' . (count($documentoD) + 1);


          /* GENERAR NUMERO DE GUIA */
          $caracteres      = strlen($documento->consecutivo);
          $sumarCaracteres = 7 - $caracteres;
          $carcater        = '0';
          $prefijo         = (isset($prefijoGuia->prefijo) and $prefijoGuia->prefijo != '') ? $prefijoGuia->prefijo : '';
          $prefijoPais     = (isset($prefijoGuia->iso2) and $prefijoGuia->iso2 != '') ? $prefijoGuia->iso2 : '';
          for ($i = 1; $i <= $sumarCaracteres; $i++) {
              $prefijo = $prefijo . $carcater;
          }
          $data->num_guia = $prefijo . $documento->consecutivo . (count($documentoD) + 1) . $prefijoPais;
          $data->paquete  = (count($documentoD) + 1);

          /* GENERAR NUMERO DE WAREHOUSE */
          $data->num_warehouse = $documento->num_warehouse . '' . (count($documentoD) + 1);
          if ($documento->liquidado === 1) {
              $data->liquidado = 1;
          }
          // $data->save();
          if ($data->save()) {
              /* INSERTAR TRAKCING*/
              if($request->ids_tracking != ''){
                $this->addTrackingsToDocument($request->ids_tracking, $data->id, $request->consignee_id);
              }
              /* INSERTAR EN STATUS_DETALLE*/
              DB::table('status_detalle')->insert([
                  [
                      'status_id'            => $data->status_id,
                      'usuario_id'           => Auth::user()->id,
                      'documento_detalle_id' => $data->id,
                      'codigo'               => $data->num_warehouse,
                      'fecha_status'         => date('Y-m-d H:i:s'),
                      'created_at'           => date('Y-m-d H:i:s'),
                  ],
              ]);

              /* INSERTAR CAMPO AGRUPADO */
              $data->agrupado = $data->id;
              $data->save();

              $detalle = DocumentoDetalle::join('documento', 'documento_detalle.documento_id', '=', 'documento.id')
                  ->leftJoin('shipper', 'documento_detalle.shipper_id', '=', 'shipper.id')
                  ->leftJoin('consignee', 'documento_detalle.consignee_id', '=', 'consignee.id')
                  ->join('agencia', 'documento.agencia_id', '=', 'agencia.id')
                  ->leftJoin('posicion_arancelaria', 'documento_detalle.posicion_arancelaria_id', '=', 'posicion_arancelaria.id')
                  ->join('maestra_multiple', 'documento_detalle.tipo_empaque_id', '=', 'maestra_multiple.id')
                  ->select('documento_detalle.*', 'agencia.descripcion AS nom_agencia', 'posicion_arancelaria.pa AS nom_pa', 'posicion_arancelaria.id AS id_pa', 'shipper.nombre_full AS ship_nomfull', 'consignee.nombre_full AS cons_nomfull', 'maestra_multiple.nombre AS empaque', DB::raw("(SELECT Count(a.id) FROM tracking AS a WHERE a.documento_detalle_id = documento_detalle.id AND a.deleted_at IS NULL) as cantidad"))
                  ->where([['documento_detalle.deleted_at', null], ['documento_detalle.id', $data->id]])
                  ->first();
              $this->AddToLog('Documento detalle insertado (' . $data->id . ')');
              $answer = array(
                  "datos"  => $detalle,
                  "code"   => 200,
                  "status" => 200,
              );
            } else {
                $answer = array(
                    "error"  => 'Error al intentar Eliminar el registro.',
                    "code"   => 600,
                    "status" => 500,
                );
            }
        }
        return $answer;
      } catch (\Exception $e) {
        return $e;
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
