<?php

namespace App\Http\Controllers;

use App\Cliente;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JavaScript;
use App\User;

class ClienteController extends Controller
{
    public function __construct(){
        $this->middleware('permission:clientes.index')->only('index');
        $this->middleware('permission:clientes.store')->only('store');
        $this->middleware('permission:clientes.update')->only('update');
        $this->middleware('permission:clientes.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('clientes');
        JavaScript::put([
            'data_agencia' => $this->getNameAgencia(),
        ]);
        return view('templates/clientes');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data              = (new Cliente)->fill($request->all());
            $data->agencia_id  = Auth::user()->agencia_id;
            $data->created_at  = date('Y-m-d H:i:s');
            if ($data->save()) {
                $this->AddToLog('Cliente creado id (' . $data->id . ')');
                $answer = array(
                    "datos"  => $request->all(),
                    "code"   => 200,
                    "status" => 200,
                );
            } else {
                $answer = array(
                    "error"  => 'Error al intentar Eliminar el registro.',
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = Cliente::findOrFail($id);
            $data->update($request->all());
            $data->save();
            $this->AddToLog('Cliente editado id (' . $data->id . ')');
            $answer = array(
                "datos"  => $request->all(),
                "code"   => 200,
                "status" => 500,
            );
            return $answer;

        } catch (\Exception $e) {
            $error = '';
            foreach ($e->errorInfo as $key => $value) {
                $error .= $key . ' - ' . $value . ' <br> ';
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
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
        $data = Cliente::findOrFail($id);
        $data->delete();
        $this->AddToLog('Cliente Eliminado de base de datos id (' . $id . ')');
    }

    /**
     * Actualiza el campo deleted_at del registro seleccionado.
     *
     * @param  int  $id
     * @param  boolean  $deleteLogical
     * @return \Illuminate\Http\Response
     */
    public function delete($id, $logical)
    {

        if (isset($logical) and $logical == 'true') {
            $data             = Cliente::findOrFail($id);
            $now              = new \DateTime();
            $data->deleted_at = $now->format('Y-m-d H:i:s');
            if ($data->save()) {
                $this->AddToLog('Cliente Eliminado id (' . $id . ')');
                $answer = array(
                    "datos" => 'Eliminación exitosa.',
                    "code"  => 200,
                );
            } else {
                $answer = array(
                    "error" => 'Error al intentar Eliminar el registro.',
                    "code"  => 600,
                );
            }

            return $answer;
        } else {
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
        $data             = Cliente::findOrFail($id);
        $data->deleted_at = null;
        $data->save();
    }

    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $sql = Cliente::join('localizacion AS b', 'clientes.localizacion_id', 'b.id')
        ->select(
        	'clientes.id',
    			'clientes.agencia_id',
    			'clientes.localizacion_id',
    			'clientes.nombre',
    			'clientes.direccion',
    			'clientes.telefono',
    			'clientes.email',
    			'clientes.zona',
    			'clientes.email_bcc',
    			'clientes.factura_agrupada',
    			'clientes.updated_at',
    			'b.nombre AS ciudad'
        )->where([
          ['clientes.agencia_id', Auth::user()->agencia_id],
          ['clientes.deleted_at', null]
        ])
        ->get();
        return \DataTables::of($sql)->make(true);
    }

    public function selectInput(Request $request, $tableName)
    {
        $term = $request->term ?: '';

        if ($tableName != 'localizacion') {
            if($tableName == 'agencia' && !Auth::user()->isRole('admin')){
                $tags = false;
            }else{
                $tags = DB::table($tableName)
                    ->select([$tableName . '.id', $tableName . '.descripcion as text'])
                    ->where([
                        [$tableName . '.descripcion', 'like', $term . '%'],
                        [$tableName . '.deleted_at', '=', null],
                    ])->get();
            }
        } else {
            $tags = DB::table($tableName)
                ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
                ->join('pais', 'deptos.pais_id', '=', 'pais.id')
                ->select(['localizacion.id', 'localizacion.nombre as text', 'deptos.descripcion as deptos', 'deptos.id as deptos_id', 'pais.descripcion as pais', 'pais.id as pais_id'])
                ->where([
                    ['localizacion.nombre', 'like', $term . '%'],
                    ['localizacion.deleted_at', '=', null],
                ])->get();
        }

        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return \Response::json($answer);
    }


    public function existEmail(Request $request)
    {
        try {
            $dataUser = Cliente::select('id')->where([
                ['email', $request->email]
            ])->first();

            if (count($dataUser) > 0) {
                $answer = array(
                    "valid"   => false,
                    "message" => "Este email ya existe en la base de datos",
                    "data"    => "",
                );
            } else {
                $answer = array(
                    "valid"   => true,
                    "message" => "",
                    "data"    => "",
                );
            }
            return $answer;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getDataById($id)
    {
      $data = Cliente::join('localizacion AS b', 'clientes.localizacion_id', 'b.id')
      ->select(
        'clientes.id',
        'clientes.agencia_id',
        'clientes.localizacion_id',
        'clientes.nombre AS primer_nombre',
        'clientes.direccion',
        'clientes.telefono',
        'clientes.email AS correo',
        'clientes.zona',
        'clientes.email_bcc AS notify_client',
        'clientes.factura_agrupada AS invoice_all',
        'b.nombre AS ciudad'
      )->where([
          ['clientes.id', '=', $id],
          ['clientes.deleted_at', '=', null],
      ])->first();
      return \Response::json($data);
    }

}
