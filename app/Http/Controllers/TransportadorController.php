<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use DataTables;
use App\Transportador;
use App\Http\Requests\TransportadorRequest;

class TransportadorController extends Controller
{
    public function __construct(){
        $this->middleware('permission:transportador.index')->only('index');
        $this->middleware('permission:transportador.store')->only('store');
        $this->middleware('permission:transportador.update')->only('update');
        $this->middleware('permission:transportador.destroy')->only('destroy');
        $this->middleware('permission:transportador.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('transportador');
        return view('templates/transportador');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransportadorRequest $request)
    {
        try{
            $data = (new Transportador)->fill($request->all());
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
    public function update(TransportadorRequest $request, $id)
    {
        try {
            $data = Transportador::findOrFail($id);
            $data->update($request->all());
            $answer=array(
                "datos" => $request->all(),
                "code" => 200,
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
        $data = Transportador::findOrFail($id);
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
            $data = Transportador::findOrFail($id);
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
        $data = Transportador::findOrFail($id);
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
        return \DataTables::of(Transportador::query()->where('deleted_at', '=', NULL))->make(true);
    }

    public function uploadImage(Request $request)
    {
      if ($request->file('file')) {
          $data = Transportador::findOrFail($request->id);
          //obtenemos el nombre del archivo
          $data->logo = trim($request->file('file')->getClientOriginalName());
          $data->logo_url = trim('storage/'.$data->logo);
          $data->save();
          //indicamos que queremos guardar un nuevo archivo en el disco local
          \Storage::disk('public')->put('storage/'.$data->logo, \File::get($request->file('file'))); //se guardara en 'public/storage'
      }
      return $request->all();
    }

    public function getLogo($id)
    {
      $data = Transportador::findOrFail($id);
      return ['name' => $data->logo, 'url' => $data->logo_url];
    }
}
