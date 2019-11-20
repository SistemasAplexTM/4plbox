<?php
namespace App\Http\Controllers;

use App\Http\Requests\ServiciosRequest;
use App\Servicios;
use Auth;
use DataTables;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:servicios.index')->only('index');
        $this->middleware('permission:servicios.store')->only('store');
        $this->middleware('permission:servicios.update')->only('update');
        $this->middleware('permission:servicios.destroy')->only('destroy');
        $this->middleware('permission:servicios.delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('servicios');
        return view('templates/servicios');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiciosRequest $request)
    {
        try {
            $data             = (new Servicios)->fill($request->all());
            $data->created_at = date('Y-m-d H:i:s');
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
    public function update(ServiciosRequest $request, $id)
    {
        try {
            $data = Servicios::findOrFail($id);
            $data->update($request->all());
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
        $data = Servicios::findOrFail($id);
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
            $data             = Servicios::findOrFail($id);
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
        $data             = Servicios::findOrFail($id);
        $data->deleted_at = null;
        $data->save();
    }

    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll($id_embarque = false)
    {
        $where = [['servicios.deleted_at', null]];
        if ($id_embarque) {
            $where[] = array('servicios.tipo_embarque_id', $id_embarque);
        }
        $data = Servicios::join('maestra_multiple as b', 'servicios.tipo_embarque_id', 'b.id')
            ->leftJoin('posicion_arancelaria as c', 'servicios.posicion_arancel_id', 'c.id')
            ->leftJoin('pais as p', 'servicios.pais_id', 'p.id')
            ->select('servicios.*', 'p.descripcion AS pais', 'b.nombre as tipo_embarque', 'c.id as pa_id', 'c.pa')
            ->where($where)
            ->get();
        return \DataTables::of($data)->make(true);
    }

    public function getAllServiciosAgencia($id_embarque)
    {
        $data = Servicios::join('agencia_detalle as b', 'servicios.id', '=', 'b.servicios_id')
            ->leftJoin('posicion_arancelaria as c', 'servicios.posicion_arancel_id', 'c.id')
            ->leftJoin('pais as p', 'servicios.pais_id', 'p.id')
            ->select('servicios.id', 'p.descripcion AS pais', 'servicios.tipo_embarque_id', 'servicios.nombre', 'servicios.tarifa', 'servicios.cobro_opcional', 'servicios.cobro_peso_volumen', 'servicios.peso_minimo', 'servicios.seguro', 'servicios.impuesto', 'b.tarifa_principal', 'b.tarifa_agencia', 'b.tarifa_minima', 'b.seguro_principal', 'b.seguro as seguro_agencia', 'c.id as pa_id', 'c.pa')
            ->where([
                ['servicios.deleted_at', null],
                ['b.deleted_at', null],
                ['b.agencia_id', Auth::user()->agencia_id],
                ['servicios.tipo_embarque_id', $id_embarque],
            ])
            ->get();
        return \DataTables::of($data)->make(true);
    }
}
