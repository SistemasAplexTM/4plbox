<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use DataTables;
use App\Moneda;

class MonedaController extends Controller
{
    public function index()
    {
        return view('templates/Moneda');
    }

    public function store(Request $request)
    {
        try{
            $data = (new Moneda)->fill($request->all());
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

    public function update(Request $request, $id)
    {
        try {
            $data = Moneda::findOrFail($id);
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

    public function destroy($id)
    {
        $data = Moneda::findOrFail($id);
        $data->delete();
    }

    public function delete($id,$logical)
    {

        if(isset($logical) and $logical == 'true'){
            $data = Moneda::findOrFail($id);
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
        $data = Moneda::findOrFail($id);
        $data->deleted_at = NULL;
        $data->save();
    }

    public function getAll()
    {
        return \DataTables::of(Moneda::query()->where('deleted_at', '=', NULL))->make(true);
    }
}
