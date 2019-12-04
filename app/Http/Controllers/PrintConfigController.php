<?php

namespace App\Http\Controllers;

use Neodynamic\SDK\Web\WebClientPrint;
use Session;
use App\AplexConfig;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Auth;

class PrintConfigController extends Controller
{
  public function index()
  {
    $this->assignPermissionsJavascript();
    $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintConfigController@index'), Session::getId());
    $wcppScriptDetect = WebClientPrint::createWcppDetectionScript(action('WebClientPrintController@processRequest'), Session::getId());
    return view('templates/printConfig', compact('wcpScript', 'wcppScriptDetect'));
  }

  public function save(Request $request)
  {
    $key_id = 'print_' . Auth::user()->agencia_id;
    $id = $this->getConfig($key_id);

    $datInsert = [
      'id' => 1,
      'label' => $request->data['labels'],
      'default' => $request->data['default']
    ];
    Session::put('printer', (object) $datInsert);
    $data = [$datInsert];

    if (isset($id->value) and $id->value != '') {
      $printers = json_decode($id->value);
      /* VERIFICAR SI EXISTE LA IMPRESORA ENVIADA A REGISTRAR */
      $cont = 0;
      $cant = 1;
      foreach ($printers as $key => $value) {
        if ($value->label == $request->data['labels'] and $value->default == $request->data['default']) {
          $cont++;
        }
        $cant++;
      }
      $datInsert['id'] = $cant;
      if ($cont == 0) {
        array_push($printers, $datInsert);
        $data = $printers;
      }
    }
    $msn = 'Registro creado';
    $code = 200;
    if ($id) {
      if ($cont == 0) {
        AplexConfig::where('id', $id->id)->update([
          'key' => $key_id,
          'value' =>  json_encode($data)
        ]);
      } else {
        $msn = 'El registro ya existe!';
        $code = 600;
      }
    } else {
      AplexConfig::insert([
        'key' => $key_id,
        'value' => json_encode($data)
      ]);
    }
    return array('data' => $msn, 'code' => $code);
  }

  public function getPrintersSaved()
  {
    $key = 'print_' . Auth::user()->agencia_id;
    $data = $this->getConfig($key);

    return json_decode($data->value);
  }

  public function deletePrinter($id)
  {
    $key_id = 'print_' . Auth::user()->agencia_id;
    $data = $this->getConfig($key_id);
    $printers = json_decode($data->value);
    unset($printers[$id]);
    $insert = [];
    if ($printers) {
      foreach ($printers as $key => $value) {
        array_push($insert, $value);
      }
    }
    // exit();
    AplexConfig::where('id', $data->id)->update([
      'key' => $key_id,
      'value' =>  json_encode($insert)
    ]);
    return array('data' => $insert, 'code' => 200);
  }
}
