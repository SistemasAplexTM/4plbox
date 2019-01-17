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
      $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintConfigController@index'), Session::getId());
      return view('templates/printConfig', compact('wcpScript'));
    }

    public function save(Request $request)
    {
      $data = array('id_agency' => Auth::user()->agencia_id, 'prints' => $request->data);
      AplexConfig::insert([
        'key' => 'print',
        'value' => json_encode($data)
      ]);
      return array('code' => 200);
    }


}
