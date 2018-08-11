<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if($objAgencia->logo != null && $objAgencia->logo != ''){
            \Session::put('logo', $objAgencia->logo);
        }else{
            \Session::put('logo', 'logo.png');
        }
        \Session::put('agencia', $objAgencia->descripcion);
       
        return '/home';
    }

    // public function authenticate(Request $request)
    // {
    //     if (Auth::attempt(['email' => $email, 'password' => $password, 'actived' => 1])) {
    //         // Authentication passed...
    //         return redirect()->intended('/home');
    //     }else{
    //         return 'No estas activo.';
    //     }
    // }

}
