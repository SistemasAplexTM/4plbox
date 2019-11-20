<?php

namespace App\Http\Controllers;

include_once(app_path() . '\WebClientPrint\WebClientPrint.php');
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
      return view('templates/printConfig', compact('wcpScript','wcppScriptDetect'));
    }

    public function save(Request $request)
    {
      $key_id = 'print_'. Auth::user()->agencia_id;
      $id = $this->getConfig($key_id);
     
      $datInsert = [
          'id' => 1,
          'label' => $request->data['labels'],
          'default' => $request->data['default']
      ];
      $data = [$datInsert];

      if (isset($id->value) and $id->value != '') {
        $printers = json_decode($id->value);
        /* VERIFICAR SI EXISTE LA IMPRESORA ENVIADA A REGISTRAR */
        $cont = 0;
        $cant = 1;
        foreach ($printers as $key => $value) {
          if ($value->label != $request->data['labels']) {
            $cont++;
          }
          if ($value->default != $request->data['default']) {
            $cont++;
          }
          $cant++;
        }
        $datInsert['id'] = $cant;
        if ($cont > 0) {
          array_push($printers, $datInsert);
          $data = $printers;
        }
      }
      if ($id) {
        AplexConfig::where('id', $id->id)->update([
          'key' => $key_id,
          'value' =>  json_encode($data)
        ]);
      }else{
        AplexConfig::insert([
          'key' => $key_id,
          'value' => json_encode($data)
        ]);
      }
      return array('code' => 200);
    }

    public function getPrintersSaved()
    {
      $key = 'print_'. Auth::user()->agencia_id;
      $data = $this->getConfig($key);

      return json_decode($data->value);
    }

    public function deletePrinter($id)
    {
      $key_id = 'print_'. Auth::user()->agencia_id;
      DB::table('aplex_config')->where('key', $key_id)->update(['value' => DB::raw('JSON_REMOVE(value, "$.['.$id.']")')]);
      // DB::table('aplex_config')
      //       ->where('key', $key_id)
      //       ->update(['value->id' => $id]);
      $data = $this->getConfig($key_id);
      $printers = json_decode($data->value);
      echo '<pre>';
      print_r($printers);
      echo '</pre>';
      exit();
    }

}
