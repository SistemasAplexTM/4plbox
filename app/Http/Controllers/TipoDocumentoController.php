<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoDocumentoRequest;
use App\TipoDocumento;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoDocumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tipoDocumento.index')->only('index');
        $this->middleware('permission:tipoDocumento.store')->only('store');
        $this->middleware('permission:tipoDocumento.update')->only('update');
        $this->middleware('permission:tipoDocumento.destroy')->only('destroy');
        $this->middleware('permission:tipoDocumento.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('tipoDocumento');
        return view('templates/tipoDocumento');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoDocumentoRequest $request)
    {
        try {
            $data = new TipoDocumento;
            /* OBTENER DATOS DE FUNCIONALIDADES */
            $fun = array();
            if (count($request->funcionalidades) > 0) {
                foreach ($request->funcionalidades as $key => $value) {
                    $fun[] = array(
                        'id'   => $value['id'],
                        'name' => $value['text'],
                    );
                }
            }
            /* OBTENER DATOS DE CREDENCIALES */
            $cred = array();
            if (count($request->credenciales) > 0) {
                foreach ($request->credenciales as $key => $value) {
                    $cred[] = array(
                        'id'   => $value['id'],
                        'name' => $value['text'],
                    );
                }
            }
            /* CREAR OBJETO PARA REGISTRAR */
            $data->nombre              = $request->nombre;
            $data->prefijo             = $request->prefijo;
            $data->icono               = $request->icono;
            $data->consecutivo_inicial = $request->consecutivo_inicial;
            $data->email_plantilla_id  = $request->email_plantilla_id;
            $data->email_copia         = $request->email_copia;
            $data->email_copia_oculta  = $request->email_copia_oculta;
            $data->funcionalidades     = json_encode($fun);
            $data->credenciales        = json_encode($cred);
            $data->created_at          = date('Y-m-d H:i:s');

            if ($data->save()) {
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoDocumentoRequest $request, $id)
    {
        try {
            $data = TipoDocumento::findOrFail($id);

            /* OBTENER DATOS DE FUNCIONALIDADES */
            $fun = array();
            if (count($request->funcionalidades) > 0) {
                foreach ($request->funcionalidades as $key => $value) {
                    $fun[] = array(
                        'id'   => $value['id'],
                        'name' => $value['text'],
                    );
                }
            }
            /* OBTENER DATOS DE CREDENCIALES */
            $cred = array();
            if (count($request->credenciales) > 0) {
                foreach ($request->credenciales as $key => $value) {
                    $cred[] = array(
                        'id'   => $value['id'],
                        'name' => $value['text'],
                    );
                }
            }
            /* CREAR OBJETO PARA REGISTRAR */
            $data->nombre              = $request->nombre;
            $data->prefijo             = $request->prefijo;
            $data->icono               = $request->icono;
            $data->consecutivo_inicial = $request->consecutivo_inicial;
            $data->email_plantilla_id  = $request->email_plantilla_id;
            $data->email_copia         = $request->email_copia;
            $data->email_copia_oculta  = $request->email_copia_oculta;
            $data->funcionalidades     = json_encode($fun);
            $data->credenciales        = json_encode($cred);
            $data->created_at          = date('Y-m-d H:i:s');

            if ($data->update()) {
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
        $data = TipoDocumento::findOrFail($id);
        $data->delete();
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
            $data             = TipoDocumento::findOrFail($id);
            $now              = new \DateTime();
            $data->deleted_at = $now->format('Y-m-d H:i:s');
            if ($data->save()) {
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
        $data             = TipoDocumento::findOrFail($id);
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
        $data = DB::table('tipo_documento as a')
            ->leftjoin('plantillas_correo as b', 'a.email_plantilla_id', 'b.id')
            ->select('a.*', 'b.nombre as name_plantilla', 'b.descripcion_plantilla')
            ->where([
                ['a.deleted_at', null],
            ])
            ->get();
        return \DataTables::of($data)->make(true);
    }

    public function selectInput(Request $request, $tableName)
    {
        $term = $request->term ?: '';
        $name = 'nombre';
        if ($tableName == 'credencial') {
            $tableName = 'roles';
            $name = 'name';
        }
        $tags = DB::table($tableName)
            ->select([$tableName . '.id', $tableName . '.' . $name . ' as text'])
            ->where([
                [$tableName . '.' . $name, 'like', $term . '%'],
                [$tableName . '.deleted_at', '=', null],
            ])->get();
        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return \Response::json($answer);
    }

    public function getPlantillasEmail()
    {
        $data = DB::table('plantillas_correo as a')
            ->select('a.id', 'a.nombre as name', 'a.descripcion_plantilla')
            ->where([
                ['a.deleted_at', null],
            ])
            ->get();
        $answer = array(
            'code' => 200,
            'data' => $data,
        );
        return $answer;
    }

}
