<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Redirect;
use DataTables;
use App\Ciudad;
use App\Pais;
use App\Http\Requests\CiudadRequest;

class CiudadController extends Controller
{
    public function __construct(){
        $this->middleware('permission:ciudad.index')->only('index');
        $this->middleware('permission:ciudad.store')->only('store');
        $this->middleware('permission:ciudad.update')->only('update');
        $this->middleware('permission:ciudad.destroy')->only('destroy');
        $this->middleware('permission:ciudad.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('ciudad');
        return view('templates/ciudad');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CiudadRequest $request)
    {
        try{
            $data = (new Ciudad)->fill($request->all());
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
    public function update(CiudadRequest $request, $id)
    {
        try {
            $data = Ciudad::findOrFail($id);
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
            $data = Ciudad::findOrFail($id);
            $data->delete();
            $answer = array(
                "datos" => 'Eliminación exitosa.',
                "code"  => 200,
            );
        } catch (\Exception $e) {
            $error = '';
            foreach ($e->errorInfo as $key => $value) {
                // $error .= $key . ' - ' . $value . ' <br> ';
                if($value == '23000'){
                    $error .= 'No es posible eliminar el registro, esta asociado con otro registro <br> ';
                }
            }
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
            $data = Ciudad::findOrFail($id);
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
    public function restaurar($id)
    {
        $data = Ciudad::findOrFail($id);
        $data->deleted_at = NULL;
        $data->save();
    }


    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $sql = DB::table('localizacion')
        ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
        ->join('pais', 'deptos.pais_id', '=', 'pais.id')
            ->select('localizacion.*', 'deptos.descripcion as deptos', 'pais.descripcion as pais', 'pais.id as pais_id')
            ->where([['localizacion.deleted_at', '=', NULL]])
            ->orderBy('localizacion.nombre');
        return \DataTables::of($sql)->make(true);
    }


    public function selectInput(Request  $request, $tableName, $id_condition = false){
        $term = $request->term ?: '';

        if(isset($id_condition) and $id_condition){
        	$tags = DB::table($tableName)->select(['id', 'descripcion as text'])->where([
        		['pais_id', '=', $id_condition],
                ['descripcion', 'like', '%'.$term.'%'],
            ])->get();
        }else{
	        $tags = DB::table($tableName)->select(['id', 'descripcion as text'])->where([
	                ['descripcion', 'like', '%'.$term.'%'],
                    [$tableName.'.deleted_at', '=', NULL]
	            ])->get();        	
        }

        $answer = array(
            'code' => 200,
            'items' => $tags
        );
        return \Response::json($answer);
    }
}
