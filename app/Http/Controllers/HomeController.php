<?php

namespace App\Http\Controllers;

include_once(app_path() . '\WebClientPrint\WebClientPrint.php');
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\PrintFile;
use Neodynamic\SDK\Web\PrintFilePDF;
use Neodynamic\SDK\Web\PrintFileTXT;
use Neodynamic\SDK\Web\PrintRotation;
use Neodynamic\SDK\Web\PrintOrientation;
use Neodynamic\SDK\Web\TextAlignment;
use Neodynamic\SDK\Web\ClientPrintJob;
use Session;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->assignPermissionsJavascript('documento');
      $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('DocumentoController@printFile'), Session::getId());
      return view('templates.documento.index', ['wcpScript' => $wcpScript]);
    }
}
