<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use App\Radicado;
use App\Empleado;
use App\RadicadoCliente;
use App\RadicadoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class RadicadoController extends Controller
{
  public function index()
  {
      return view('templates/radicado');
  }

  public function store(Request $request)
  {
      try {
        DB::beginTransaction();
          $data = new Radicado;
          $data->agencia_id = Auth::user()->agencia_id;
          $data->usuario_id = Auth::user()->id;
          $data->cliente_id = $request->cliente_id;
          $data->empleado_id = $request->empleado_id;
          $data->fecha = $request->fecha;
          if ($data->save()) {
            foreach ($request->detalle as $key => $value) {
              RadicadoDetalle::create([
                'radicado_id' => $data->id,
                'cantidad' => $value['cantidad'],
                'descripcion' => $value['descripcion']
              ]);
            }
              $answer = array(
                  "datos"  => $request->all(),
                  "code"   => 200,
                  "status" => 200,
              );
              DB::commit();
          } else {
              $answer = array(
                  "error"  => 'Error al intentar Eliminar el registro.',
                  "code"   => 600,
                  "status" => 500,
              );
              DB::rollback();
          }
          return $answer;
      } catch (\Exception $e) {
        DB::rollback();
          $answer = array(
              "error"  => $e,
              "code"   => 600,
              "status" => 500,
          );
          return $answer;
      }
  }

  public function update(Request $request, $id)
  {
      try {
          $data = Radicado::findOrFail($id);
          $data->update($request->all());
          $answer = array(
              "datos"  => $request->all(),
              "code"   => 200,
              "status" => 500,
          );
          return $answer;

      } catch (Exception $e) {

          $answer = array(
              "error"  => $e,
              "code"   => 600,
              "status" => 500,
          );
          return $answer;
      }
  }

  public function destroy($id)
  {
      $data = Radicado::findOrFail($id);
      $data->delete();
  }

  public function delete($id, $logical)
  {

      if (isset($logical) and $logical == 'true') {
          $data             = Radicado::findOrFail($id);
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

  public function restaurar($id)
  {
      $data             = Radicado::findOrFail($id);
      $data->deleted_at = null;
      $data->save();
  }

  public function getAll()
  {
    $data = Radicado::select('id', 'empleado_id', 'cliente_id', 'agencia_id', 'usuario_id', 'fecha')
    ->with(['empleado', 'cliente', 'usuario', 'agencia'])->whereNull('deleted_at')
    ->orderBy('id', 'DESC')->get();
      return \DataTables::of($data)->make(true);
  }

  public function getClientes()
  {
    return RadicadoCliente::whereNull('deleted_at')->get();
  }

  public function getEmpleados()
  {
    return Empleado::whereNull('deleted_at')->get();
  }

  public function imprimir($id)
  {
    $data = Radicado::select('id', 'empleado_id', 'cliente_id', 'agencia_id', 'usuario_id', 'fecha')
    ->with(['empleado', 'cliente', 'usuario', 'agencia'])->where('id', $id)->whereNull('deleted_at')
    ->orderBy('id', 'DESC')->first();
    $detalle = RadicadoDetalle::select('cantidad', 'descripcion')->where('radicado_id', $id)->get();

    $pdf     = PDF::loadView('pdf.radicado', compact('data', 'detalle'));
    $this->AddToLog('Impresion Radicado ' . $id);
    return $pdf->stream('Radicado-'.$id.'.pdf');
  }
}
