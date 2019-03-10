<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TransportadorasLocales;
use App\Pais;
class LocalTransportersController extends Controller
{
    public function index()
    {
        return view('templates.localTransporters');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
      try {
          $data              = (new TransportadorasLocales)->fill($request->all());
          $data->created_at  = date('Y-m-d H:i:s');
          if ($data->save()) {
              $this->AddToLog('Transportadora local creada id (' . $data->id . ')');
              $answer = array(
                  "datos"  => $request->all(),
                  "code"   => 200,
                  "status" => 200,
              );
          } else {
              $answer = array(
                  "error"  => 'Error al intentar crear el registro.',
                  "code"   => 600,
                  "status" => 500,
              );
          }
          return $answer;
      } catch (\Exception $e) {
          $error = $e;
          // foreach ($e->errorInfo as $key => $value) {
          //     $error .= $key . ' - ' . $value . ' <br> ';
          // }
          $answer = array(
              "error"  => $error,
              "code"   => 600,
              "status" => 500,
          );
          return $answer;
      }
    }

    public function update(Request $request, $id)
    {
      try {
          $data = TransportadorasLocales::findOrFail($id);
          $data->update($request->all());
          $answer=array(
              "datos" => $request->all(),
              "code" => 200,
          );
          return $answer;

      } catch (\Exception $e) {
          $answer=array(
                  "error" => $e,
                  "code" => 600,
                  "status" => 500,
              );
          return $answer;
      }
    }

    public function destroy($id)
    {
      $data = TransportadorasLocales::findOrFail($id);
      $data->delete();
    }

    public function delete($id,$logical)
    {

        if(isset($logical) and $logical == 'true'){
            $data = TransportadorasLocales::findOrFail($id);
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
        $data = TransportadorasLocales::findOrFail($id);
        $data->deleted_at = NULL;
        $data->save();
    }

    public function getAll()
    {
        $sql = TransportadorasLocales::join('pais AS b', 'transportadoras_locales.pais_id', 'b.id')
        ->select(
        	'transportadoras_locales.id',
    			'transportadoras_locales.pais_id',
    			'transportadoras_locales.nombre',
    			'transportadoras_locales.url_rastreo',
    			'b.descripcion AS pais'
        )
        ->where('transportadoras_locales.deleted_at', null)
        ->get();

        return \DataTables::of($sql)->make(true);
    }
    public function getAllPais()
    {
        return Pais::all();
    }
}
