<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ProductDetailCuba;

class PuntosController extends Controller
{
    public function index($id)
    {
      $id = base64_decode($id);
      $agency = DB::table('agencia')
      ->select('logo')
      ->where('id', $id)
      ->first();
      if ($agency == null) {
        return redirect('/');
      }
      return view('cuba/index', compact('agency'));
    }

    public function saveShipperConsignee(Request $request, $type, $shipper_id = null)
    {
      $name = $request->primer_nombre . ' - ' . $request->primer_apellido;
      $name = $this->getNamesAndFullNames($name);
      $id = DB::table($type)->insertGetId([
        'primer_nombre' => $name[0][0],
        'segundo_nombre' => (isset($name[0][1])) ? $name[0][1] : '',
        'primer_apellido' => $name[1][0],
        'segundo_apellido' => (isset($name[1][1])) ? $name[1][1] : '',
        'nombre_full' => $request->primer_nombre . ' ' . $request->primer_apellido,
        'documento' => $request->documento,
        'correo' => $request->correo,
        'direccion' => $request->direccion,
        'localizacion_id' => $request->localizacion_id,
        'telefono' => $request->telefono,
        'zip' => $request->zip,
        'agencia_id' => $request->agencia_id
      ]);
      if ($shipper_id) {
        DB::table('shipper_consignee')->insert(['shipper_id' => $shipper_id, 'consignee_id' => $id]);
      }
    }

    public function saveProductDetail(Request $request)
    {
      foreach ($request->dataTable as $key => $value) {
        ProductDetailCuba::insert([
          'documento_id' => $request->documento_id,
          'producto_cuba_id' => (isset($value['id'])) ? $value['id'] : 0,
          'descripcion' => $value['product'],
          'cantidad' => $value['cant']
        ]);
      }
      return array('code' => 200);
    }
}
