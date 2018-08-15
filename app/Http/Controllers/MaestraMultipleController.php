<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Redirect;
use DataTables;
use App\MaestraMultiple;
use App\Modulo;
use App\Http\Requests\MaestraMultipleRequest;

class MaestraMultipleController extends Controller
{
    public function __construct(){
        $this->middleware('permission:administracion.index')->only('index');
        $this->middleware('permission:administracion.store')->only('store');
        $this->middleware('permission:administracion.update')->only('update');
        $this->middleware('permission:administracion.destroy')->only('destroy');
        $this->middleware('permission:administracion.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $this->assignPermissionsJavascript('administracion');
        $data = Modulo::findOrFail($type);
        return view('templates/maestraMultiple')->with('type',$type)->with('name',$data->nombre);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaestraMultipleRequest $request, $type)
    {
        try{
            $data = (new MaestraMultiple)->fill($request->all());
            $data->created_at = date('Y-m-d H:i:s');
            if ($data->save()) {
                $answer=array(
                    "datos" => $request->all(),
                    "code" => 200,
                    "status" => 200,
                );
            }else{
                $answer=array(
                    "error" => 'Error al intentar registrar.',
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
    public function update(MaestraMultipleRequest $request, $type, $id)
    {
        try {
            $data = MaestraMultiple::findOrFail($id);
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
    public function destroy($type, $id)
    {
        try {
            $data = MaestraMultiple::findOrFail($id);
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
    public function delete($type, $id,$logical)
    {
        if(isset($logical) and $logical == 'true'){
            $data = MaestraMultiple::findOrFail($id);
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
        $data = MaestraMultiple::findOrFail($id);
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
        $maestra = MaestraMultiple::select(['id', 'nombre', 'descripcion', 'modulo_id'])
        ->where([
                ['deleted_at', '=', NULL],
                ['modulo_id', '=', $type]
            ]);
        
        return Datatables::of($maestra)->make(true);
    }

    public function selectInput(Request  $request, $type, $tableName){
        $term = $request->term ?: '';

        $tags = DB::table($tableName)
            ->select([$tableName.'.id', $tableName.'.nombre as text'])
        ->where([
                [$tableName.'.nombre', 'like', $term.'%'],
                [$tableName.'.modulo_id', '=', $type],
                [$tableName.'.deleted_at', '=', NULL]
            ])->get();
        $answer = array(
            'code' => 200,
            'items' => $tags
        );
        return \Response::json($answer);
    }
}
