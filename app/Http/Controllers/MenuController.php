<?php

namespace App\Http\Controllers;

use App\Menu;
use App\MenuDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('templates/menu');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
          $request->validate([
            'name' => 'required|max:255',
            'route' => 'required'
          ],
          [
             'name.required' => 'El nombre es requerido',
             'route.required' => 'la ruta es requerida'
         ]);
          Menu::insert([
            'name' => $request->name,
            'route' => $request->route,
            'meta'=> json_encode(['icon' => $request->icon])
          ]);
          return ['code' => 200];
        } catch (\Exception $e) {
            $answer = array(
                "error" => $e,
                "code"  => 600,
            );
            return $answer;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $Menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = Menu::findOrFail($id);
            $data->update($request->all());
            $this->AddToLog('Menu editado (id :' . $data->id . ')');
            $answer = array(
                "datos" => $request->all(),
                "code"  => 200,
            );
            return $answer;

        } catch (\Exception $e) {
            $answer = array(
                "error" => $e,
                "code"  => 600,
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
    public function destroy($id, $table = false)
    {
        try {
            if ($table == 'detail') {
                $data = MenuDetalle::findOrFail($id);
                if ($data->delete()) {
                    $this->AddToLog('Menu detalle eliminado (id :' . $data->id . ')');
                    $answer = array(
                        "code" => 200,
                    );
                }
            } else {
                $data = Menu::findOrFail($id);
                if ($data->delete()) {
                    $this->AddToLog('Menu eliminado (id :' . $data->id . ')');
                    $answer = array(
                        "code" => 200,
                    );
                }
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Restaura registro eliminado
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restaurar($id, $table = false)
    {
        if ($table == 'detail') {
            $data             = MenuDetalle::withTrashed()->findOrFail($id);
            $data->deleted_at = null;
            $data->save();
            $answer = array(
                'code' => 200,
            );
        } else {
            $data             = Menu::withTrashed()->findOrFail($id);
            $data->deleted_at = null;
            $data->save();
            $answer = array(
                'code' => 200,
            );
        }
        return $answer;
    }

  public static function getHijos($padres, $line)
   {
       $children = [];
       foreach ($padres as $line1) {
           if ($line['id'] == $line1['parent']) {
               $children = array_merge($children, [array_merge($line1, ['children' => Self::getHijos($padres, $line1)])]);
           }
       }
       return $children;
   }

   public static function getPadres($front)
   {
       if ($front) {
           // return $this->whereHas('roles', function ($query) {
           //     $query->where('rol_id', session()->get('rol_id'))->orderby('menu_id');
           // })->orderby('menu_id')
           //     ->orderby('orden')
           //     ->get()
           //     ->toArray();
       } else {
           return Menu::orderby('parent')
               ->orderby('order_item')
               ->get()
               ->toArray();
       }
   }

   public static function getMenu($front = false)
   {
       $padres = Self::getPadres(false);
       $menuAll = [];
       foreach ($padres as $line) {
           if ($line['parent'] != 0)
               break;
           $item = [array_merge($line, ['children' => Self::getHijos($padres, $line)])];
           $menuAll = array_merge($menuAll, $item);
       }
       return $menuAll;
   }

}
