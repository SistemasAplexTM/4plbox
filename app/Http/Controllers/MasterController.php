<?php

namespace App\Http\Controllers;

use App\AerolineaInventario;
use App\Master;
use App\MasterDetalle;
use App\DocumentoDetalle;
use App\Documento;
use App\MasterCostos;
use App\MasterCargosAdicionales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MasterController extends Controller
{
    public function __construct(){
        $this->middleware('permission:master.index')->only('index');
        $this->middleware('permission:master.create')->only('store','create');
        $this->middleware('permission:master.update')->only('update');
        $this->middleware('permission:master.destroy')->only('destroy');
    }
    public function index()
    {
        $this->assignPermissionsJavascript('master');
        return view('templates.master.list');
    }
    public function store(Request $request)
    {
        $success = true;
        DB::beginTransaction();
        try {
            $master             = (new Master)->fill($request->all());
            $master->agencia_id = Auth::user()->agencia_id;
            if ($master->save()) {
                if($request->consolidado_id != null){
                DB::table('documento')
                    ->where('id', $request->consolidado_id)
                    ->update(['master_id' => $master->id]);
                }
            }
            AerolineaInventario::where('id', $request->aerolinea_inventario_id)->update(['usado' => 1]);
            $detalle            = (new MasterDetalle)->fill($request->all());
            $detalle->peso_kl = $detalle->peso;
            $detalle->peso = $detalle->peso * 2.20462;
            $detalle->master_id = $master->id;
            $detalle->save();

            DB::table('master_cargos_adicionales')->where('master_id', $master->id)->delete();
            if($request->other_c[0]['oc_value'] != ''){
                foreach ($request->other_c as $value) {
                    DB::table('master_cargos_adicionales')->insert(
                        [
                            'master_id' => $master->id,
                            'descripcion' => $value['oc_description'],
                            'agent_carrier' => $value['oc_due'],
                            'valor' => $value['oc_value'],
                            'created_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                }
            }

            DB::commit();
            return array('id_master' => $master->id);
        } catch (\Exception $e) {
            DB::rollback();
            $success   = false;
            $exception = $e;
            return $e;
        }
        if ($success) {
            return array(
                'code'  => 200,
                'error' => false,
            );
        } else {
            return array(
                'code'      => 600,
                'error'     => true,
                'exception' => $exception,
            );
        }
    }

    public function show($id)
    {
        $data    = $this->getMasterForImpress($id);
        $detalle = $this->getMasterDetalleForImpress($id);
        return array('data' => $data, 'detalle' => $detalle);
    }

    public function create($master = null, $consolidado_id = null)
    {
        $master = $master;
        $consolidado_id = $consolidado_id;
        $peso = 0;
        $piezas = 1;
        if($consolidado_id != 'null' and $consolidado_id != null){
          $peso_consolidado  = DB::table('consolidado_detalle AS a')
          ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
          ->select(DB::raw("ROUND(Sum(b.peso2) * 0.453592) AS peso_total"))
          ->where([['b.deleted_at', null], ['a.deleted_at', null], ['a.consolidado_id', $consolidado_id]])
          ->first();
          if($peso_consolidado and $peso_consolidado != null){
            $peso = $peso_consolidado->peso_total;
          }

          $piezas_consolidado  = DB::table('consolidado_detalle AS a')
          ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
          ->select(DB::raw("Count(DISTINCT a.num_bolsa) AS cantidad"))
          ->where([['b.deleted_at', null], ['a.deleted_at', null], ['a.consolidado_id', $consolidado_id]])
          ->first();
          if($piezas_consolidado and $piezas_consolidado != null){
            $piezas = $piezas_consolidado->cantidad;
          }
        }
        return view('templates.master.create', compact('master', 'consolidado_id', 'peso', 'piezas'));
    }
    public function update(Request $request, $master)
    {
        DB::beginTransaction();
        try {
            $masterObj = Master::findOrFail($master);
            $masterObj->updated_at = $request->updated_at;
            $masterObj->update($request->all());
            $detalle = MasterDetalle::where('master_id', $master);
            $detalle->update([
                'piezas'         => $request->piezas,
                'peso'           => $request->peso * 2.20462,
                'peso_kl'        => $request->peso,
                'unidad_medida'  => $request->unidad_medida,
                'rate_class'     => $request->rate_class,
                'commodity_item' => $request->commodity_item,
                'peso_cobrado'   => $request->peso_cobrado,
                'tarifa'         => $request->tarifa,
                'total'          => $request->total,
                'minima'         => $request->minima,
                'descripcion'    => $request->descripcion,
            ]);
            DB::table('master_cargos_adicionales')->where('master_id', $master)->delete();
            if($request->other_c[0]['oc_value'] != ''){
                foreach ($request->other_c as $value) {
                    DB::table('master_cargos_adicionales')->insert(
                        [
                            'master_id' => $master,
                            'descripcion' => $value['oc_description'],
                            'agent_carrier' => $value['oc_due'],
                            'valor' => $value['oc_value'],
                            'created_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                }
            }
            if ($request->consolidado_id != null) {
                DB::table('documento')
                    ->where('id', $request->consolidado_id)
                    ->update(['master_id' => $master]);
            }
            DB::commit();
            return array('id_master' => $master);
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function getSoC($dato, $type)
    {
        // $data = Transportador::all();
        // $data = Transportador::where('nombre', 'LIKE', '%'.$dato.'%')->get();
        switch ($type) {
            case 's':
                $where = array(['a.shipper', 1]);
                break;
            case 'c':
                $where = array(['a.consignee', 1]);
                break;
            case 'cr':
                $where = array(['a.carrier', 1]);
                break;
            default:
                $where = '';
                break;
        }
        $data = DB::table('transportador AS a')
            ->select(
                'a.id',
                'a.nombre AS name',
                'direccion',
                'telefono',
                'contacto',
                'ciudad',
                'estado',
                'pais',
                'zip'
            )
            ->where($where)
            ->where('a.nombre', 'LIKE', '%' . $dato . '%')
            ->get();
        return array('items' => $data);
    }

    public function imprimir($id_master, $simple = false)
    {
        /* esta cantidad es para la cantidad de masters a imprimir*/
        $cantidad = 1;
        if ($simple) {
            $cantidad = 8;
        }
        $data    = $this->getMasterForImpress($id_master);
        $detalle = $this->getMasterDetalleForImpress($id_master);
        $other   = $this->getOtherCharges($id_master);
        // $data    = array('data' => $data, 'detalle' => $detalle, 'other' => $other, 'cantidad' => $cantidad);
        return view('pdf.masterPdf_1', compact('cantidad', 'data', 'detalle', 'other'));
        $pdf     = \PDF::loadView('pdf.masterPdf_1', $data);
        // $pdf = \PDF::loadView('templates.master.imprimir', $data);
        return $pdf->stream($data->num_master.'.pdf');
    }

    public function getMasterForImpress($id_master)
    {
        $data = DB::table('master AS a')
            ->join('agencia AS b', 'a.agencia_id', 'b.id')
            ->join('localizacion AS c', 'b.localizacion_id', 'c.id')
            ->join('transportador AS d', 'a.consignee_id', 'd.id')
            ->join('transportador AS e', 'a.shipper_id', 'e.id')
            ->join('transportador AS j', 'a.carrier_id', 'j.id')
            ->join('aerolineas_aeropuertos AS f', 'a.aeropuertos_id', 'f.id')
            ->join('aerolineas_aeropuertos AS g', 'a.aerolineas_id', 'g.id')
            ->join('aerolineas_aeropuertos AS h', 'a.aeropuertos_id_destino', 'h.id')
            ->leftJoin('aerolineas_inventario AS i', 'a.aerolinea_inventario_id', 'i.id')
            ->leftJoin('documento AS z', 'a.id', 'z.master_id')
            ->leftJoin('localizacion', 'z.ciudad_id', '=', 'localizacion.id')
            ->leftJoin('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
            ->leftJoin('pais AS x', 'deptos.pais_id', 'x.id')
            ->select(
                'a.num_master',
                'a.master_id',
                'a.account_information',
                'a.agent_iata_code',
                'a.num_account',
                'a.reference_num',
                'a.optional_shipping_info',
                'a.currency',
                'a.chgs_code',
                'a.handing_information',
                'a.master_detail',
                'a.to1',
                'a.to2',
                'a.to3',
                'a.by1',
                'a.by2',
                'a.by_first_carrier',
                'a.fecha_vuelo1',
                'a.fecha_vuelo2',
                'a.amount_insurance',
                'a.other_charges',
                'a.tax',
                'a.total_prepaid',
                'a.total_other_charge_due_agent',
                'a.total_other_charge_due_carrier',
                'a.shipper_id',
                'a.shipper',
                'a.consignee_id',
                'a.consignee',
                'a.carrier_id',
                'a.carrier',
                'a.aerolineas_id',
                'b.descripcion',
                'b.direccion',
                'b.responsable',
                'b.telefono',
                'b.zip',
                'c.nombre',
                'c.prefijo',
                'd.nombre AS nombre_consignee',
                'd.direccion AS direccion_consignee',
                'd.telefono AS telefono_consignee',
                'd.ciudad AS ciudad_consignee',
                'd.estado AS estado_consignee',
                'd.pais AS pais_consignee',
                'd.zip AS zip_consignee',
                'd.contacto AS contacto_consignee',

                'e.nombre AS nombre_shipper',
                'e.direccion AS direccion_shipper',
                'e.telefono AS telefono_shipper',
                'e.zip AS zip_shipper',
                'e.ciudad AS ciudad_shipper',
                'e.estado AS estado_shipper',
                'e.pais AS pais_shipper',
                'e.contacto AS contacto_shipper',

                'j.nombre AS nombre_carrier',
                'j.direccion AS direccion_carrier',
                'j.telefono AS telefono_carrier',
                'j.zip AS zip_carrier',
                'j.ciudad AS ciudad_carrier',
                'j.estado AS estado_carrier',
                'j.pais AS pais_carrier',
                'j.contacto AS contacto_carrier',

                'f.nombre AS nombre_aeropuerto',
                'f.id AS aeropuertos_id',
                'g.nombre AS nombre_aerolinea',
                'g.direccion AS dir_aerolinea',
                'g.zip AS zip_aerolinea',
                'g.codigo AS codigo_aerolinea',
                'h.nombre AS aeropuerto_destino',
                'h.id AS aeropuertos_id_destino',
                'h.codigo AS aeropuerto_codigo',
                'i.guia AS aerolinea_inventario',
                'z.id AS consolidado_id',
                'z.consecutivo AS consolidado',
                DB::raw('SUBSTRING_INDEX(`z`.`created_at`, " ", 1) as fecha'),
                'x.descripcion AS pais'
            )
            ->where('a.id', $id_master)
            ->first();
        return $data;
    }

    public function getMasterDetalleForImpress($id_master)
    {
        $data = DB::table('master_detalle AS a')
            ->select(
                'a.piezas',
                'a.rate_class',
                'a.commodity_item',
                'a.peso_cobrado',
                'a.tarifa',
                'a.minima',
                'a.total',
                'a.descripcion',
                'a.peso',
                'a.peso_kl',
                'a.unidad_medida'
            )
            ->where('a.master_id', $id_master)
            ->first();
        return $data;
    }

    public function getAll()
    {
        $data = DB::table('master AS a')
            ->join('master_detalle AS b', 'b.master_id', 'a.id')
            ->join('aerolineas_aeropuertos AS c', 'a.aerolineas_id', 'c.id')
            ->join('aerolineas_aeropuertos AS d', 'd.id', 'a.aeropuertos_id_destino')
            ->join('localizacion AS e', 'd.localizacion_id', 'e.id')
            ->join('transportador AS g', 'a.consignee_id', 'g.id')
            ->leftJoin('documento AS f', 'f.master_id', 'a.id')
            ->select(
                'a.id',
                'a.master_id',
                'a.carrier_id',
                'c.nombre AS aerolinea',
                'e.nombre AS ciudad',
                'a.num_master',
                'f.consecutivo',
                'f.id AS consolidado_id',
                'b.peso',
                'b.peso_kl',
                'b.tarifa',
                'g.nombre AS consignee',
                'g.contacto AS contacto',
                'a.created_at',
                DB::raw("(SELECT
                z.trm
                FROM
                impuesto_master AS z
                WHERE
                z.master_id = a.id AND
                z.deleted_at IS NULL) AS trm"),
                DB::raw("(SELECT
                z.fecha_liquidacion
                FROM
                impuesto_master AS z
                WHERE
                z.master_id = a.id AND
                z.deleted_at IS NULL) AS fecha_liquidacion")
            )
            ->where([
                ['a.deleted_at', null],
            ])
            ->get();
        return \DataTables::of($data)->make(true);
    }

    public function vueSelectConsolidados($data)
    {
        $where = [
                ['a.ciudad_id', '<>', null],
                ['a.master_id', null],
                ['a.deleted_at', null],
            ];
        if(!Auth::user()->isRole('admin')){
            $where[] = ['a.agencia_id', Auth::user()->agencia_id];
        }
        $term = $data;
        $tags = DB::table('documento AS a')
            ->leftJoin('localizacion AS b', 'a.ciudad_id', 'b.id')
            ->leftJoin('deptos AS c', 'b.deptos_id', 'c.id')
            ->leftJoin('pais AS d', 'c.pais_id', 'd.id')
            ->select(['a.id', 'a.consecutivo AS consolidado', DB::raw('SUBSTRING_INDEX(`a`.`created_at`, " ", 1) as fecha'), 'd.descripcion AS pais'])
            ->whereRaw("(a.consecutivo like '%" . $term . "%' OR d.descripcion like '%" . $term . "%')")
            ->where($where)->get();
        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return $answer;
    }

    public function getOtherCharges($id_master)
    {
        $data = DB::table('master_cargos_adicionales AS a')
            ->select(
                'a.descripcion AS oc_description',
                'a.agent_carrier AS oc_due',
                'a.valor AS oc_value'
            )
            ->where([
                ['a.master_id', $id_master]
            ])
            ->get();
        $answer = array(
            'code'  => 200,
            'data' => $data
        );
        return $answer;
    }

    public function imprimirLabel($id_master)
    {
        $data    = $this->getMasterForImpress($id_master);
        $detalle = DB::table('master_detalle AS a')
                    ->select(
                        'a.id',
                        'a.piezas',
                        'a.rate_class',
                        'a.commodity_item',
                        'a.peso_cobrado',
                        'a.tarifa',
                        'a.total',
                        'a.descripcion',
                        'a.peso',
                        'a.peso_kl',
                        'a.unidad_medida'
                    )
                    ->where('a.master_id', $id_master)
                    ->get();
        $pdf     = \PDF::loadView('pdf.masterLabelPdf', compact('data', 'detalle'))
        ->setPaper(array(0, 0, 360, 576)); //multiplicar pulgadas por 72 (5 x 8 pulgadas en este label)
        return $pdf->stream('master.pdf');
    }

    public function imprimirGuias($consolidado_id, $option = null)
    {
        $detalle = DB::select("CALL getDataGuiasDetalleByConsolidadoId(?)", array($consolidado_id));

        return view('pdf/labels/guiasHijasColombiana', compact('detalle'));
    }

    public function getDataConsolidados($type)
    {
      $where = [
              ['a.ciudad_id', '<>', null],
              ['a.master_id', null],
              ['a.bill_id', null],
              ['a.deleted_at', null],
          ];
      if(!Auth::user()->isRole('admin')){
          $where[] = ['a.agencia_id', Auth::user()->agencia_id];
      }
      if($type == 0){
        $where[] = ['a.transporte_id', 1];
      }else{
        $where[] = ['a.transporte_id', 2];
      }
      $data = DB::table('documento AS a')
          ->leftJoin('localizacion AS b', 'a.ciudad_id', 'b.id')
          ->leftJoin('deptos AS c', 'b.deptos_id', 'c.id')
          ->leftJoin('pais AS d', 'c.pais_id', 'd.id')
          ->select(['a.id', 'a.consecutivo AS consolidado', DB::raw('SUBSTRING_INDEX(`a`.`created_at`, " ", 1) as fecha'), 'b.nombre AS ciudad'])
          ->where($where)->get();
      return $data;
    }

    public function createHawb($id)
    {
      DB::beginTransaction();
      try {
        /* BUSCAR HOUSES DE ESTA MASTER */
        $houses = Master::select(DB::raw('Count(master.id) AS cantidad'))
        ->where([['master.master_id', $id], ['master.deleted_at', NULL]])
        ->first();
        /* CREACION DE LA NUEVA MASTER */
        $master = Master::find($id);
        $newMaster = $master->replicate();
        $newMaster->master_id = $id;
        $newMaster->num_master = $master->num_master . '_' . ($houses->cantidad + 1);
        $newMaster->save();

        /* ENCONTRAR DETALLE ASOCIADO */
        $detalle = MasterDetalle::select(
          'id',
          'master_id',
          'piezas',
          'peso',
          'peso_kl',
          'unidad_medida',
          'rate_class',
          'commodity_item',
          'peso_cobrado',
          'tarifa',
          'total',
          'descripcion'
          )
        ->where([['master_detalle.master_id', $id], ['master_detalle.deleted_at', NULL]])
        ->first();
        $newDetalle = $detalle->replicate();
        $newDetalle->master_id = $newMaster->id;
        $newDetalle->save();

        /* ENCONTRAR CARGOS ADICIONALES */
        $cargos_id = MasterCargosAdicionales::select('id')
        ->where([['master_id', $id]])
        ->get();
        if($cargos_id){
          foreach ($cargos_id as $val) {
            $cargos = MasterCargosAdicionales::find($val->id);
            $newCargos = $cargos->replicate();
            $newCargos->master_id = $newMaster->id;
            $newCargos->save();
          }
        }

        DB::commit();
        return array('code' => 200);
      } catch (Exception $e) {
          DB::rollback();
          return $e;
      }
    }

    public function saveTaxMaster(Request $request)
    {
      DB::beginTransaction();
      try {
        if($request->cost_edit){
          DB::table('impuesto_master')
          ->where('master_id', $request->master_id)
          ->update([
            'fecha_liquidacion' => $request->cost_date,
            'rate' => $request->cost_rate,
            'trm' => $request->cost_trm,
            'peso' => $request->cost_weight,
          ]);
          $data = DB::table('impuesto_master')->where('master_id', $request->master_id)->first();
          $this->saveMasterTaxDetail($data->id, $request->master_id);
        }else{
          $id = DB::table('impuesto_master')->insertGetId(
              [
                  'master_id' => $request->master_id,
                  'fecha_liquidacion' => $request->cost_date,
                  'rate' => $request->cost_rate,
                  'trm' => $request->cost_trm,
                  'peso' => $request->cost_weight,
                  'created_at' => date('Y-m-d H:i:s')
              ]
          );
          $this->saveMasterTaxDetail($id, $request->master_id);
        }
        DB::commit();
        return array('code' => 200);
      } catch (\Exception $e) {
          DB::rollback();
          return $e;
      }
    }

    public function saveMasterTaxDetail($impuesto_master_id, $master_id)
    {
      DB::beginTransaction();
      try {
        $consolidate = Documento::where('master_id', $master_id)->first();
        $masterD = MasterDetalle::where('master_id', $master_id)->first();
        $hijas = DB::table('consolidado_detalle AS a')
        ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
        ->join('posicion_arancelaria AS c', 'c.id', 'b.arancel_id2')
        ->select(
            'a.id',
            'a.documento_detalle_id',
            'b.declarado2 AS declarado',
            'b.peso2 AS peso',
            'c.arancel',
            'c.iva'
        )
        ->where([['a.deleted_at', null], ['a.consolidado_id', $consolidate->id]])
        ->get();
        foreach ($hijas as $key => $val) {

          $seguro = $val->declarado * 0.005;
          $flete = $val->peso * $masterD->tarifa;
          $baseArancel = $val->declarado + $seguro + $flete;
          $arancel = round($baseArancel * $val->arancel, 2);
          $baseIva = $baseArancel + $arancel;
          $iva = round($baseIva * $val->iva, 2);

          $data = array(
            'impuesto_master_id' => $impuesto_master_id,
            'documento_id' => $val->documento_detalle_id,
            'seguro' => $seguro,
            'flete' => $flete,
            'costo' => $val->declarado,
            'arancel' => $arancel,
            'iva' => $iva,
            'total_impuesto' => $arancel + $iva,
            'porcentaje_iva' => $val->iva,
            'porcentaje_arancel' => $val->arancel
          );
          DB::table('impuesto_hijas')->insert($data);
        }
        DB::commit();
        return true;
      } catch (\Exception $e) {
          DB::rollback();
          return $e;
      }
    }

    public function impuestosMaster($id)
    {
      return view('templates.master.impuestosMaster');
    }

    public function saveCostMaster(Request $request)
    {
      DB::beginTransaction();
      try {
        $cost = (new MasterCostos)->fill($request->all());
        $cost->save();
        DB::commit();
        return array('code' => 200);
      } catch (\Exception $e) {
          DB::rollback();
          return $e;
      }
    }

    public function getCosts($master_id)
    {
      DB::beginTransaction();
      try {
        $data = DB::table('master_costos AS a')
        ->join('moneda AS b', 'a.moneda_id', 'b.id')
        ->leftJoin('maestra_multiple AS c', 'a.costos_id', 'c.id')
        ->select(
            'a.id',
            'a.master_id',
            'a.moneda_id',
            'a.costos_id',
            'a.descripcion',
            'a.valor',
            'a.trm',
            'a.costo_gasto',
            'b.moneda',
            'b.simbolo',
            'c.nombre'
        )
        ->where([
          ['a.deleted_at', null],
          ['a.master_id', $master_id]
        ])
        ->orderBy('a.costo_gasto', 'ASC')
        ->get();
        DB::commit();
        return array('data' => $data);
      } catch (\Exception $e) {
          DB::rollback();
          return $e;
      }
    }

    public function deleteCost($id)
    {
      DB::beginTransaction();
      try {
        $data = MasterCostos::findOrFail($id);
        $data->delete();
        DB::commit();
        return array('code' => 200);
      } catch (\Exception $e) {
          DB::rollback();
          return $e;
      }
    }

    public function generateXml($id)
    {
      $data = array('ano' => '220');
      $content = view('templates.master.fileXml', compact('data'))->render();
      \File::put(storage_path().'/file.xml', $content);
      return response()->make($content, 200)
      ->header('Content-Type', 'application/xml')
      ->header('Content-Disposition', 'attachment; filename="Dmuisca.xml"');

    }
}
