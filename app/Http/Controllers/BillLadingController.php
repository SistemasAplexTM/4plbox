<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BillLading;
use App\BillLadingDetail;
use App\BillLadingOtherCharges;
use Auth;
use Illuminate\Support\Facades\DB;

class BillLadingController extends Controller
{
    public function index()
    {
        return view('templates/bill/index');
    }

    public function create($bill = false, $consolidado_id = null)
    {
        $bill = $bill;
        $consolidado_id = $consolidado_id;
        return view('templates/bill/create', compact('bill', 'consolidado_id'));
    }

    public function edit($id)
    {
        $data    = $this->getBillForImpress($id);
        $detalle = $this->getBillDetalleForImpress($id);
        $other = $this->getOtherCharges($id);
        return array('data' => $data, 'detalle' => $detalle, 'other' => $other);
    }

    public function getAll()
    {
        $sql = BillLading::select(
        	'bill_lading.*',
        	DB::raw('(SELECT z.gross_weight FROM bill_lading_detail AS z WHERE z.bill_lading_id = bill_lading.id AND z.deleted_at IS NULL) AS peso_kl')
    	)
            ->where('bill_lading.deleted_at', NULL);
        return \DataTables::of($sql)->make(true);
    }

    public function store(Request $request)
    {
        $success = true;
        DB::beginTransaction();
        try {
            $bill               = (new BillLading)->fill($request->all());
            $bill->date_document= date('Y-m-d', strtotime($request->created_at));
            $bill->agencia_id   = Auth::user()->agencia_id;
            $bill->users_id     = Auth::user()->id;
            if ($bill->save()) {
                /* REGISRAR DELTALLE */
                if($request->detail[0]['number_packages'] != ''){
                    foreach ($request->detail as $value) {
                        $billD                  = (new BillLadingDetail)->fill($value);
                        $billD->bill_lading_id  = $bill->id;
                        $billD->created_at      = $request->created_at;
                        $billD->save();
                    }
                }
                /* REGISRAR OTROS CARGOS */
                if(count($request->other) > 0 and $request->other[0]['description'] != ''){
                    foreach ($request->other as $value2) {
                        $billO                  = (new BillLadingOtherCharges)->fill($value2);
                        $billO->bill_lading_id  = $bill->id;
                        $billO->created_at      = $request->created_at;
                        $billO->save();
                    }
                }

                if($request->consolidado_id != 'null' and $request->consolidado_id != ''){
                DB::table('documento')
                    ->where('id', $request->consolidado_id)
                    ->update(['bill_id' => $bill->id]);
                }
            }


            DB::commit();
            return array('code'  => 200,'id_bill' => $bill->id);
        } catch (Exception $e) {
            DB::rollback();
            $success   = false;
            $exception = $e;
            return $e;
        }
    }

    public function update(Request $request, $id_bill)
    {

        DB::beginTransaction();
        try {
            $bill               = BillLading::findOrFail($id_bill);
            $bill->updated_at   = $request->updated_at;


            if ($bill->update($request->all())) {
                /* REGISRAR DELTALLE */
                if($request->detail[0]['number_packages'] != ''){
                    BillLadingDetail::where('bill_lading_id', $id_bill)->delete();
                    foreach ($request->detail as $value) {
                        $billD                  = BillLadingDetail::updateOrCreate($value);
                        $billD->bill_lading_id  = $bill->id;
                        // $billD->updated_at      = $request->updated_at;
                        $billD->save();
                    }
                }
                /* REGISRAR OTROS CARGOS */
                if(count($request->other) > 0 and $request->other[0]['description'] != ''){
                    BillLadingOtherCharges::where('bill_lading_id', $id_bill)->delete();
                    foreach ($request->other as $value2) {
                        $billO                  = BillLadingOtherCharges::updateOrCreate($value2);
                        $billO->bill_lading_id  = $bill->id;
                        // $billO->updated_at      = $request->updated_at;
                        $billO->save();
                    }
                }
            }
            DB::commit();
            return array('id_bill' => $id_bill);
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function imprimir($id_bill, $simple = false)
    {
        /* esta cantidad es para la cantidad de masters a imprimir*/
        $cantidad = 1;
        if ($simple) {
            $cantidad = 8;
        }
        $data    = $this->getBillForImpress($id_bill);
        $detalle = $this->getBillDetalleForImpress($id_bill);
        $other   = $this->getOtherCharges($id_bill);
        return view('pdf.billPdf', compact('cantidad', 'data', 'detalle', 'other'));
    }

    public function getBillForImpress($id_bill)
    {
        $data = DB::table('bill_lading AS a')
            ->join('agencia AS b', 'a.agencia_id', 'b.id')
            ->join('users AS c', 'a.users_id', 'c.id')
            ->select(
                'a.id',
                'a.num_bl',
                'a.date_document',
                'a.exporter',
                'a.exporter_zip',
                'a.consignee',
                'a.notify_party',
                'a.document_number',
                'a.export_references',
                'a.forwarding_agent',
                'a.point_origin',
                'a.domestic_routing',
                'a.loading_pier',
                'a.containered',
                'a.pre_carriage_by',
                'a.place_of_receipt',
                'a.exporting_carrier',
                'a.port_loading',
                'a.foreign_port',
                'a.placce_delivery',
                'a.agent_for_carrier',
                'a.type_move',
                'a.created_at',
                'c.name AS usuario',
                'b.descripcion AS agencia',
                'b.logo AS agencia_logo'
            )
            ->where('a.id', $id_bill)
            ->first();
        return $data;
    }

    public function getBillDetalleForImpress($id_bill)
    {
        $data = DB::table('bill_lading_detail AS a')
            ->select(
                'a.id',
                'a.marks_numbers',
                'a.number_packages',
                'a.description',
                'a.gross_weight',
                'a.measurement',
                'a.created_at'
            )
            ->where('a.bill_lading_id', $id_bill)
            ->get();
        return $data;
    }

    public function getOtherCharges($id_bill)
    {
        $data = DB::table('bill_lading_other_charges AS a')
            ->select(
                'a.id',
                'a.description',
                'a.ammount_pp',
                'a.ammount_cll',
                'a.created_at'
            )
            ->where([
                ['a.bill_lading_id', $id_bill]
            ])
            ->get();
        return $data;
    }

    public function delete($id,$logical)
    {

        if(isset($logical) and $logical == 'true'){
            $data = BillLading::findOrFail($id);
            $now = new \DateTime();
            $data->deleted_at =$now->format('Y-m-d H:i:s');
            if($data->save()){
                    $answer=array(
                        "datos" => 'Eliminación exitosa.',
                        "code" => 200
                    );
               }  else{
                    $answer=array(
                        "error" => 'Error al intentar Eliminar el registro.',
                        "code" => 600
                    );
               }

                return $answer;
        }else{
            $this->destroy($id);
        }
    }

    public function restaurar($id)
    {
        $data = BillLading::findOrFail($id);
        $data->deleted_at = NULL;
        $data->save();
    }

    public function getParties()
    {
        $sql = DB::table('bill_parties AS a')
            ->select(
                'a.id',
                'a.display_name',
                'a.account_number',
                'a.zip',
                'a.text_exporter'
            )
            ->get();
        return \DataTables::of($sql)->make(true);
    }

    public function createPartie(Request $request){
        DB::beginTransaction();
        try {
            DB::table('bill_parties')->insert([
                [
                    'display_name'      => $request->display_name,
                    'account_number'    => $request->account_number,
                    'zip'               => $request->zip,
                    'text_exporter'     => $request->text_exporter,
                    'created_at'        => $request->created_at
                ],
            ]);
            DB::commit();
            return array('code' => 200);
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function editPartie(Request $request, $id){
        DB::beginTransaction();
        try {
            DB::table('bill_parties')
            ->where('id', $id)
            ->update(
                    ['display_name'    => $request->display_name,
                    'account_number'   => $request->account_number,
                    'zip'              => $request->zip,
                    'text_exporter'    => $request->text_exporter,
                    'updated_at'       => $request->updated_at]
            );
            DB::commit();
            return array('code' => 200);
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function destroyPartie($id)
    {
        DB::table('bill_parties')->where('id', $id)->delete();
    }
}
