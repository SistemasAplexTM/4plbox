<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use DataTables;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('permission:user.index')->only('index');
        $this->middleware('permission:user.store')->only('store');
        $this->middleware('permission:user.update')->only('update');
        $this->middleware('permission:user.destroy')->only('destroy');
        $this->middleware('permission:user.delete')->only('delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('user');
        return view('templates/user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $data             = (new User)->fill($request->all());
            $data->password   = bcrypt($request->password);
            $data->created_at = date('Y-m-d H:i:s');
            if ($data->save()) {
                /* INSERCION DE TABLA PIVOT ROLE_USER */
                DB::table('role_user')->insert([
                    [
                        'role_id'    => $request->rol_id,
                        'user_id'    => $data->id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                ]);
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
            $error = '';
            foreach ($e->errorInfo as $key => $value) {
                $error .= $key . ' - ' . $value . ' <br> ';
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data           = User::findOrFail($id);
            if(isset($request['data']['password']) and $request['data']['password'] != null){
                $data->password = bcrypt($request['data']['password']);                
            }
            $data->email = $request['data']['email'];
            $data->actived = $request['data']['actived'];
            $data->name = $request['data']['name'];
            $data->agencia_id = $request['data']['agencia_id'];
            $data->save();
            /* ACTUALIZACION DE TABLA PIVOT ROLE_USER */
            DB::table('role_user')
            ->where('user_id', $data->id)
            ->update(['role_id'    => $request['data']['rol_id']]);
            $answer = array(
                "datos" => $request['data'],
                "code"  => 200,
            );
            return $answer;

        } catch (Exception $e) {
            $error = '';
            foreach ($e->errorInfo as $key => $value) {
                $error .= $key . ' - ' . $value . ' <br> ';
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::findOrFail($id);
        $data->delete();
    }

    /**
     * Actualiza el campo deleted_at del registro seleccionado.
     *
     * @param  int  $id
     * @param  boolean  $deleteLogical
     * @return \Illuminate\Http\Response
     */
    public function delete($id, $logical)
    {

        if (isset($logical) and $logical == 'true') {
            $data             = User::findOrFail($id);
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

    /**
     * Restaura registro eliminado
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restaurar($id)
    {
        $data             = User::findOrFail($id);
        $data->deleted_at = null;
        $data->save();
    }

    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $where = [['users.deleted_at', null]];
        if(!Auth::user()->isRole('admin')){
            $where[] = array('users.agencia_id', Auth::user()->agencia_id);
        }
        $data = User::leftjoin('agencia as a', 'users.agencia_id', 'a.id')
            ->join('role_user as b', 'users.id', 'b.user_id')
            ->join('roles as c', 'c.id', 'b.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.agencia_id', 'users.actived', 'a.descripcion as name_agencia', 'c.id AS rol_id', 'c.name as rol_name')
            ->where($where)->get();
        return \DataTables::of($data)->make(true);
    }

    public function getDataSelect($table)
    {
        $field = 'descripcion';
        if ($table == 'roles') {
            $field = 'name';
        }

        if($table == 'agencia' && !Auth::user()->isRole('admin')){
                $data = DB::table($table)
                ->select('id', $field . ' as name')
                ->where([
                    ['deleted_at', null],
                    ['id', Auth::user()->agencia_id]
                ])->get();
            }else{
                $data = DB::table($table)
                ->select('id', $field . ' as name')
                ->where([
                    ['deleted_at', null],
                ])->get();
            }

        
        $answer = array(
            'data' => $data,
        );
        return $answer;
    }

    public function validarUsername(Request $request)
    {
        try {
            $dataUser = DB::table('users')->select('name')->where('name', $request->email)->first();
            if (count($dataUser) > 0) {
                $answer = array(
                    "valid"   => false,
                    "message" => "El nombre de usuario ya existe en la base de datos.",
                );
            } else {
                $answer = array(
                    "valid"   => true,
                    "message" => "",
                );
            }
            return $answer;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getNameAgenciaUser(){
        $answer = array(
            'data' => $this->getNameAgencia()
        );
        return $answer;
    }
}
