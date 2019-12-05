<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\AplexConfig;
use Session;
use JavaScript;

use Neodynamic\SDK\Web\WebClientPrint;

class LoginController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  // protected $redirectTo = '/home';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  public function showLoginForm()
  {
    $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('Auth\LoginController@showLoginForm'), Session::getId());
    $wcppScriptDetect = WebClientPrint::createWcppDetectionScript(action('WebClientPrintController@processRequest'), Session::getId());
    return view('auth.login', compact('wcpScript', 'wcppScriptDetect'));
  }

  public function redirectPath()
  {
    /* AGENCIA */
    $objAgencia = DB::table('agencia AS a')
      ->join('localizacion AS b', 'b.id', 'a.localizacion_id')
      ->join('deptos AS c', 'c.id', 'b.deptos_id')
      ->join('pais AS d', 'd.id', 'c.pais_id')
      ->select([
        'a.id',
        'a.descripcion as descripcion',
        'a.telefono',
        'a.email',
        'a.direccion',
        'a.zip',
        'a.logo',
        'b.nombre AS ciudad',
        'c.descripcion AS depto',
        'd.descripcion AS pais',
      ])->where([
        ['a.id', Auth::user()->agencia_id],
        ['a.deleted_at', '=', null],
      ])->first();
    /* GUARDAR DATOS EN VARIABLES DE SESION */
    if ($objAgencia->logo != null && $objAgencia->logo != '') {
      \Session::put('logo', $objAgencia->logo);
    } else {
      \Session::put('logo', 'icon-no-image.svg');
    }
    \Session::put('agencia', $objAgencia->descripcion);

    JavaScript::put([
      'user' => Auth::user(),
      'agency' => $objAgencia
    ]);

    return 'home';
  }

  public function login(Request $request)
  {
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'actived' => 1])) {
      $key = 'print_' . Auth::user()->agencia_id;
      $printers = json_decode($request->printers);
      $data = $this->getConfig($key);
      $printersDB =  false;
      if ($data) {
        $printersDB =  json_decode($data->value);
      }
      // var_dump($printersDB);
      // exit();
      $default = null;
      if ($printers && $printersDB) {
        foreach ($printers as $key => $value) {
          if ($value->isDefault) {
            foreach ($printersDB as $valueBD) {
              // echo $valueBD->label;
              // echo $value['name'];
              if ($valueBD->default == $value->name) {
                $default = $valueBD;
              }
            }
          }
        }
      }
      Session::put('printer', $default);
      return redirect()->route($this->redirectPath());
    } else {
      return back()
        ->withErrors(['email' =>  trans('auth.failed')])
        ->withInput(['email' => $request->email]);
    }
  }
}
