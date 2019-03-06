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
      $key = 'print_'. Auth::id();
      $data = array("prints" => $request->data);
      $id = $this->getConfig($key);
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
