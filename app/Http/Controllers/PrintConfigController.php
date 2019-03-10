<?php

namespace App\Http\Controllers;

include_once(app_path() . '\WebClientPrint\WebClientPrint.php');
use Neodynamic\SDK\Web\WebClientPrint;
use Session;
use App\AplexConfig;

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
      // $key = 'print_'. Auth::user()->agencia_id;
      // $config = $this->getConfig($key);
      // $keyPrinterPc = null;
      // $cont = 0;
      // if($config){
      //   if ($config->value != null) {
      //     $printers = json_decode($config->value);
      //     foreach ($printers as $key => $value) {
      //       if($request->data['labels'] === $value->labels){
      //         $keyPrinterPc = $value->labels;
      //       }
      //       $cont++;
      //     }
      //     if($keyPrinterPc == null){
      //       // ACTUALIZAR CONFIGURACION DE IMPRESORAS
      //       $new = "pc_".$cont; // CREANDO NOMBRE DEL PC DEL CLIENTE
      //       $printers->$new = $request->data; // ADICIONO EL NUEVO REGISTRO AL OBJETO DE IMPRESORAS DE LA BD
      //       AplexConfig::where('id', $config->id)->update([
      //         'value' =>  json_encode($printers)
      //       ]);
      //     }
      //   }
      // }else{
      //   $data = array("pc_0" => $request->data);
      //   AplexConfig::insert([
      //     'key' => $key,
      //     'value' => json_encode($data)
      //   ]);
      // }
      $key = 'print_'. Auth::user()->agencia_id;
      $data = array("prints" => $request->data);
      $id = $this->getConfig($key);
      // echo "<pre>";
      // print_r(json_decode($data->value)[0]->labels);
      // echo "</pre>";
      if ($id) {
        AplexConfig::where('id', $id->id)->update([
          'key' => $key,
          'value' =>  json_encode($data)
        ]);
      }else{
        AplexConfig::insert([
          'key' => $key,
          'value' => json_encode($data)
        ]);
      }
      return array('code' => 200);
    }

}
