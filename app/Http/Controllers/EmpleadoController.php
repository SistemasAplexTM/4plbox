<?php

namespace App\Http\Controllers;

use DataTables;
use App\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
  public function index()
  {
      return view('templates/empleado');
  }

  public function store(Request $request)
  {
      try {
          $data             = (new Empleado)->fill($request->all());
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
          $data = Empleado::findOrFail($id);
          $data->update($request->all());
          $answer = array(
              "datos"  => $request->all(),
              "code"   => 200,
              "status" => 500,
          );
          return $answer;

      } catch (\Exception $e) {

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
      $data = Empleado::findOrFail($id);
      $data->delete();
  }

  public function delete($id, $logical)
  {

      if (isset($logical) and $logical == 'true') {
          $data             = Empleado::findOrFail($id);
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
      $data             = Empleado::findOrFail($id);
      $data->deleted_at = null;
      $data->save();
  }

  public function getAll()
  {
      return \DataTables::of(Empleado::query()->where([
        ['deleted_at', null],
        ]))->make(true);
  }
}
