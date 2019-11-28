<?php

namespace App\Http\Controllers;

use App\PermissionRole;
use App\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessControlController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:accessControl.index')->only('index');
    //     $this->middleware('permission:accessControl.store')->only('store');
    //     $this->middleware('permission:accessControl.update')->only('update');
    //     $this->middleware('permission:accessControl.destroy')->only('destroy');
    //     $this->middleware('permission:accessControl.delete')->only('delete');
    // }

    public function index()
    {
        // $this->assignPermissionsJavascript('accessControl');
        return view('permissions/accessControl');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->all()['special'] != null) {
                /* SI EL PERMISO ESPECIAL ES DIFERENTE DE NULL, NO SE PUEDEN REGISTRAR PERMISOS PARA ESE ROL.. SOLO
                SE ACTUALIZA EL CAMPO SPECIAL DEL ROL */
                $data          = Rol::findOrfail($request->all()['role_id']);
                $data->special = $request->all()['special'];
                $data->save();
            } else {
                /* SI EL PERMISO ESPECIAL ES NULL, ENTONCES SE ACTUALIZA COMO NULL EL PERMISO ESPECIAL EN LA TABLA ROL */
                $data          = Rol::findOrfail($request->all()['role_id']);
                $data->special = $request->all()['special'];
                $data->save();

                $p1 = array();
                $p2 = array();
                /* CONSULTO LOS permissions_roles QUE HAY EN LA BASE DE DATOS EXEPTUANDO LOS QUE NO PERTENECEN AL CRUD */
                $permissions_roles = PermissionRole::join('permissions AS b', 'permission_role.permission_id', 'b.id')
                    ->where([
                        ['role_id', $request->all()['role_id']],
                        ['b.crud', '<>', '0'],
                    ])
                    ->get();
                foreach ($permissions_roles as $key) {
                    $p1[] = $key->permission_id; //permisos que existen en la BD de el rol seleccionado
                }
                /* INSERTO LOS PERMISOS QUE SE ELIGIERON PARA EL ROL SELECCIONADO (si existe ya el registro no se crea) */
                foreach ($request->all()['datos'] as $key => $value) {
                    /* EL CHECKBOX QUE CONTENGA LA PALABRA 'all' NO SE INSERTARA */
                    if (explode("_", $value['name'])[0] != 'all') {
                        $data = PermissionRole::firstOrCreate(['permission_id' => $value['value'], 'role_id' => $request->all()['role_id']]);
                        $p2[] = $value['value']; //permisos que se eligieron para rol seleccionado
                    }
                }
                /* BUSCO LOS PERMISOS QUE EL USUARIO QUITO PARA EL ROL SELECCIONADO Y LOS ELIMINO */
                $resultado = array_diff($p1, $p2);
                if (count($resultado) != 0) {
                    foreach ($resultado as $key) {
                        $deletedRows = PermissionRole::where([
                            ['permission_id', $key],
                            ['role_id', $request->all()['role_id']],
                        ])->delete();
                    }
                }
            }
            $answer = array(
                "datos"  => '',
                "code"   => 200,
                "status" => 200,
            );

            DB::commit();
            return $answer;
        } catch (\Exception $e) {
            DB::rollback();
            $answer = array(
                "error" => $e,
                "code"  => 600,
            );
            return $answer;
        }
    }

    public function saveSpecialPermissions(Request $request)
    {
        DB::beginTransaction();
        try {
            $p1 = array();
            $p2 = array();
            /* CONSULTO LOS permissions_roles QUE HAY EN LA BASE DE DATOS EXEPTUANDO LOS QUE PERTENECEN AL CRUD */
            $permissions_roles = PermissionRole::join('permissions AS b', 'permission_role.permission_id', 'b.id')
                ->where([
                    ['role_id', $request->all()['role_id']],
                    ['b.crud', '0'],
                    ['b.module', $request->all()['name_module']],
                ])
                ->get();
            foreach ($permissions_roles as $key) {
                $p1[] = $key->permission_id; //permisos que existen en la BD de el rol seleccionado
            }
            /* INSERTO LOS PERMISOS QUE SE ELIGIERON PARA EL ROL SELECCIONADO (si existe ya el registro no se crea) */
            foreach ($request->all()['datos'] as $key => $value) {
                /* EL CHECKBOX QUE CONTENGA LA PALABRA 'all' NO SE INSERTARA */
                $data = PermissionRole::firstOrCreate(['permission_id' => $value['value'], 'role_id' => $request->all()['role_id']]);
                $p2[] = $value['value']; //permisos que se eligieron para rol seleccionado
            }
            /* BUSCO LOS PERMISOS QUE EL USUARIO QUITO PARA EL ROL SELECCIONADO Y LOS ELIMINO */
            $resultado = array_diff($p1, $p2);
            if (count($resultado) != 0) {
                foreach ($resultado as $key) {
                    $deletedRows = PermissionRole::where([
                        ['permission_id', $key],
                        ['role_id', $request->all()['role_id']],
                    ])->delete();
                }
            }

            $answer = array(
                "datos"  => '',
                "code"   => 200,
                "status" => 200,
            );

            DB::commit();
            return $answer;
        } catch (Exception $e) {
            DB::rollback();
            $answer = array(
                "error" => $e,
                "code"  => 600,
            );
            return $answer;
        }
    }

    public function getPermisionsRole($role_id, $module_id = null)
    {
        // OBTENER LOS PERMISOS DE UN ROL EN ESPECIFICO MEDIANTE SU id
        $special_permissions = null;
        $qb = DB::table('permissions AS a')
            ->leftJoin('modulo AS b', 'a.module_id', 'b.id')
            ->select(
                'a.module',
                'b.nombre',
                DB::raw("sum(if(a.crud = 'c', a.id,0)) AS c"),
                DB::raw("sum(if(a.crud = 'r', a.id,0)) AS r"),
                DB::raw("sum(if(a.crud = 'u', a.id,0)) AS u"),
                DB::raw("sum(if(a.crud = 'd', a.id,0)) AS d"),
                DB::raw("COUNT(if(a.crud = '0', a.id,0)) -4 AS special")
            )
            ->groupBy('a.module', 'b.nombre')
            ->havingRaw("(COUNT(if(a.crud = '0', a.id,0)) -4) > 0");

        if ($module_id) {
            $qb->where('a.module_id', $module_id);
            $special_permissions = $this->getSpecialPermisions($module_id, $role_id);
        }
        $data = $qb->get();

        $permissions = DB::table('permission_role AS a')
            ->select('a.permission_id AS id')
            ->where('a.role_id', $role_id)
            ->get();


        $answer = array(
            "data"        => $data,
            "permissions" => $permissions,
            "special_permissions" => $special_permissions,
            "code"        => 200,
        );
        return $answer;
    }

    public function getSpecialPermisions($module, $role_id)
    {
        // OBTENER PERMISOS ESPECIOALES DE CADA MODULO Y DE UN ROL ESPECIFICO
        $data = DB::table('permissions AS a')
            ->select(
                'a.id',
                'a.module',
                'a.name',
                'a.slug',
                'a.description'
            )
            ->where([
                ['a.crud', '0'],
                ['a.module', $module]
            ])
            ->orWhere('a.module_id', $module)
            ->get();
        $permissions = DB::table('permission_role AS a')
            ->select('a.permission_id AS id')
            ->where('a.role_id', $role_id)
            ->get();

        $answer = array(
            "data"        => $data,
            "permissions" => $permissions,
            "code"        => 200,
        );
        return $answer;
    }
}
