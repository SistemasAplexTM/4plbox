<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Redirect;
use DataTables;
use App\AerolineasAeropuertos;
use App\Pais;
use App\Http\Requests\AerolineasAeropuertosRequest;

class AerolineasAeropuertosController extends Controller
{
     public function __construct(){
        $this->middleware('permission:transport.index')->only('index');
        $this->middleware('permission:transport.store')->only('store');
        $this->middleware('permission:transport.update')->only('update');
        $this->middleware('permission:transport.destroy')->only('destroy');
        $this->middleware('permission:transport.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $this->assignPermissionsJavascript('transport');
        return view('templates/transport')->with('type',$type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type)
    {
        try{
            $data = (new AerolineasAeropuertos)->fill($request->all());
            $data->created_at = date('Y-m-d H:i:s');
            if ($data->save()) {
                $answer=array(
                    "datos" => $request->all(),
                    "code" => 200,
                    "status" => 200,
                );
            }else{
                $answer=array(
                    "error" => 'Error al intentar Eliminar el registro.',
                    "code" => 600,
                    "status" => 500,
                );
            }
            return $answer;
        } catch (\Exception $e) {
            $error = '';
            foreach ($e->errorInfo as $key => $value) {
                $error .= $key . ' - ' .  $value . ' <br> ';
            }
            $answer=array(
                    "error" => $error,
                    "code" => 600,
                    "status" => 500,
                );
            return $answer;
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AerolineasAeropuertosRequest $request, $type, $id)
    {
        try {
            $data = AerolineasAeropuertos::findOrFail($id);
            $data->update($request->all());
            $answer=array(
                "datos" => $request->all(),
                "code" => 200,
                "status" => 500,
            );
            return $answer;
            
        } catch (\Exception $e) {
            $error = '';
            foreach ($e->errorInfo as $key => $value) {
                $error .= $key . ' - ' .  $value . ' <br> ';
            }
            $answer=array(
                    "error" => $error,
                    "code" => 600,
                    "status" => 500,
                );
            return $answer;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = AerolineasAeropuertos::findOrFail($id);
            $data->delete();
            $answer = array(
                "datos" => 'Eliminación exitosa.',
                "code"  => 200,
            );
        } catch (Exception $e) {
            $error = '';
            
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
        }
        return $answer;
    }

    /**
     * Actualiza el campo deleted_at del registro seleccionado.
     *
     * @param  int  $id
     * @param  boolean  $deleteLogical
     * @return \Illuminate\Http\Response
     */
    public function delete($id,$logical)
    {
        
        if(isset($logical) and $logical == 'true'){
            $data = AerolineasAeropuertos::findOrFail($id);
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

    /**
     * Restaura registro eliminado
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restaurar($type, $id)
    {
        $data = AerolineasAeropuertos::findOrFail($id);
        $data->deleted_at = NULL;
        $data->save();
    }


    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll($type)
    {
        $sql = DB::table('aerolineas_aeropuertos')
        ->join('localizacion', 'aerolineas_aeropuertos.localizacion_id', '=', 'localizacion.id')
        ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
        ->join('pais', 'deptos.pais_id', '=', 'pais.id')
            ->select('aerolineas_aeropuertos.*', DB::raw('CONCAT(aerolineas_aeropuertos.codigo," - ", aerolineas_aeropuertos.nombre) AS name'), 'localizacion.nombre as ciudad', 'localizacion.id as ciudad_id', 'deptos.descripcion as estado', 'deptos.id as estado_id', 'pais.descripcion as pais', 'pais.id as pais_id')
            ->where([
                ['aerolineas_aeropuertos.deleted_at', '=', NULL],
                ['aerolineas_aeropuertos.tipo', '=', $type]
            ])
            ->orderBy('aerolineas_aeropuertos.nombre');
        return \DataTables::of($sql)->make(true);
    }


    public function selectInput(Request  $request, $tableName){
        $term = (isset($request->term)) ? $request->term : '';

        $tags = DB::table($tableName)
        ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
        ->join('pais', 'deptos.pais_id', '=', 'pais.id')
            ->select(['localizacion.id', 'localizacion.nombre as text', 'deptos.descripcion as deptos', 'deptos.id as deptos_id', 'pais.descripcion as pais', 'pais.id as pais_id'])
        ->where([
                ['localizacion.nombre', 'like', '%'.$term.'%'],
                ['localizacion.deleted_at', '=', NULL]
            ])->get();
        $answer = array(
            'code' => 200,
            'items' => $tags
        );
        return \Response::json($answer);
    }
}
