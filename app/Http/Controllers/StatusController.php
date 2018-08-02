<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use DataTables;
use App\Status;
use App\Http\Requests\StatusRequest;

class StatusController extends Controller
{
    public function __construct(){
        $this->middleware('permission:status.index')->only('index');
        $this->middleware('permission:status.store')->only('store');
        $this->middleware('permission:status.update')->only('update');
        $this->middleware('permission:status.destroy')->only('destroy');
        $this->middleware('permission:status.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('status');
        return view('templates/status');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatusRequest $request)
    {
        try{
            $data = (new Status)->fill($request->all());
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
    public function update(StatusRequest $request, $id)
    {
        try {
            $data = Status::findOrFail($id);
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
        $data = Status::findOrFail($id);
        $data->delete();
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
            $data = Status::findOrFail($id);
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
        $data = Status::findOrFail($id);
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
        return \DataTables::of(Status::query()->where('deleted_at', '=', NULL))->make(true);
    }

    public function getDataSelect()
    {
        $data   = Status::select('descripcion as name', 'id as value')
        ->where('deleted_at', null)->get();
        return array(
            "code" => 200,
            "data" => $data
        );
    }

    public function getDataSelectModalTagGuia()
    {
        $data   = Status::select('descripcion as name', 'id')
        ->where('deleted_at', null)->get();
        return \DataTables::of($data)->make(true);
    }
}
