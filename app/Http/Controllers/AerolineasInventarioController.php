<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AerolineaInventario;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class AerolineasInventarioController extends Controller
{
    public function __construct(){
        // $this->middleware('permission:aerolinea_inventario.index')->only('index');
        // $this->middleware('permission:aerolinea_inventario.store')->only('store');
        // $this->middleware('permission:aerolinea_inventario.update')->only('update');
        // $this->middleware('permission:aerolinea_inventario.delete')->only('delete');
    }

    public function index(){
        // $this->assignPermissionsJavascript('aerolinea_inventario');
    	return view('templates.aerolineasInventario');
    }

    public function store(Request $request){
    	$success = true;
        DB::beginTransaction();
        try{
            $result = array();
            $count = substr($request->guia, -1);
            $consecutivo_creacion = $this->getLasConsecutivoCreacion() + 1;

            $guia = substr($request->guia, 0, -1);
            for ($i=0; $i <= $request->cantidad ; $i++) {
                if ($count == 7) {
                    $count = 0;
                }
              AerolineaInventario::create([
                'consecutivo_creacion' => $consecutivo_creacion,
                'aerolinea_id' => $request->aerolinea_id,
                'guia' => $guia++ . $count
              ]);
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
          DB::rollback();
            $success = false;
            $exception = $e;
            return $e;
        }
        if($success){
            return array(
                'code' => 200
            );
        }else{
            return array(
                'code' => 600,
                'exception' => $exception
            );
        }
    }

    public function update(Request $request){
    	$success = true;
        DB::beginTransaction();
        try{
    		AerolineaInventario::update($request->all());

            DB::commit();
        } catch (\Exception $e) {
          	DB::rollback();
            $success = false;
            $exception = $e;
            return $e;
        }
        if($success){
            return array(
                'code' => 200
            );
        }else{
            return array(
                'code' => 600,
                'exception' => $exception
            );
        }
    }

    public function delete($id){
        DB::table('aerolineas_inventario')->where('id', $id)->delete();
    }

    public function getAll(Request $request){
        $data = DB::table('aerolineas_inventario AS a')
        ->join('aerolineas_aeropuertos AS b', 'b.id', 'a.aerolinea_id')
        ->select(
            'a.id',
            'a.consecutivo_creacion',
            'a.aerolinea_id',
            'a.guia',
            'a.usado',
            'b.nombre AS aerolinea',
            'b.codigo'
        )
        ->when($request->used, function ($query, $data) {
          return $query->where('a.usado', $data);
        }, function ($query, $data)
        {
          return $query->where('a.usado', 0);
        })
        ->get();
        return DataTables::of($data)->make(true);
    }

    public function getByAerolinea($id){
        $data = DB::table('aerolineas_inventario AS a')
        ->join('aerolineas_aeropuertos AS b', 'b.id', 'a.aerolinea_id')
        ->select(
            'a.id',
            DB::raw("CONCAT(b.codigo, ' ', a.guia) AS nombre")
        )
        ->where('aerolinea_id', $id)
        ->where('usado', 0)
        ->get();
        return $data;
    }

    public function getLasConsecutivoCreacion(){
        $data = DB::table('aerolineas_inventario AS a')
        ->select(
            DB::raw('max(a.consecutivo_creacion) AS consecutivo_creacion')
        )
        ->first();
	 	return $data->consecutivo_creacion;
    }
}
