<?php

namespace App\Http\Controllers;

use App\Menu;
use App\MenuRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

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
      $request->validate(
        [
          'name' => 'required|max:255',
          'route' => 'required'
        ],
        [
          'name.required' => 'El nombre es requerido',
          'route.required' => 'la ruta es requerida'
        ]
      );
      $menu = new Menu;
      $menu->name = $request->name;
      $menu->route = $request->route;
      $menu->module_id = $request->module_id;
      $menu->type = $request->menu;
      $menu->meta = json_encode(['icon' => $request->icon, 'color' => $request->color]);
      $menu->save();
      if ($request->roles_selected) {
        foreach ($request->roles_selected as $key => $value) {
          MenuRol::create(['menu_id' => $menu->id, 'rol_id' => $value]);
        }
      }
      return ['code' => 200];
    } catch (\Exception $e) {
      $answer = array(
        "error" => $e,
        "code"  => 600,
      );
      return $e;
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
      // return $request->all();
      $request->validate(
        [
          'name' => 'required|max:255',
          'route' => 'required'
        ],
        [
          'name.required' => 'El nombre es requerido',
          'route.required' => 'la ruta es requerida'
        ]
      );
      $menu = Menu::findOrFail($id);
      $menu->name = $request->name;
      $menu->route = $request->route;
      $menu->module_id = $request->module_id;
      $menu->meta = json_encode(['icon' => $request->icon, 'color' => $request->color]);
      $menu->save();
      MenuRol::where('menu_id', $menu->id)->delete();
      if ($request->roles_selected) {
        foreach ($request->roles_selected as $key => $value) {
          MenuRol::create(['menu_id' => $menu->id, 'rol_id' => $value]);
        }
      }
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
      return $e;
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

  public static function getPadres($front, $type)
  {
    if ($front && $front != 'false' && $front != false) {
      return Menu::whereHas('roles', function ($query) {
        $roles = Auth::user()->roles;
        foreach ($roles as $key => $value) {
          $rolesIn[] = $value->id;
        }
        $query->whereIn('rol_id', $rolesIn)->orderby('parent');
      })
        ->where('type', $type)
        ->orderby('parent')
        ->orderby('order_item')
        ->get()
        ->toArray();
    } else {
      return Menu::where('type', $type)->orderby('parent')
        ->orderby('order_item')
        ->get()
        ->toArray();
    }
  }

  public static function getMenu($front, $type)
  {
    $padres = Self::getPadres($front, $type);
    $menuAll = [];
    foreach ($padres as $line) {
      if ($line['parent'] != 0)
        break;
      $item = [array_merge($line, ['children' => Self::getHijos($padres, $line)])];
      $menuAll = array_merge($menuAll, $item);
    }
    return $menuAll;
  }

  public static function getById($id)
  {
    $menu = Menu::where('id', $id)->with('roles', 'modules')->first();
    return $menu;
  }

  public function updateOrder(Request $request)
  {
    try {
      return $this->saveOrder($request->all());
      // return response()->json(['respuesta' => 'ok']);
    } catch (\Exception $e) {
      $answer = array(
        "error" => $e,
        "code"  => 600,
      );
      return $answer;
    }
  }

  public static function saveOrder($menu)
  {
    $menus = $menu;
    foreach ($menus as $var => $value) {
      Menu::where('id', $value['id'])->update(['parent' => 0, 'order_item' => $var + 1]);
      if (!empty($value['children'])) {
        foreach ($value['children'] as $key => $vchild) {
          $update_id = $vchild['id'];
          $parent_id = $value['id'];
          Menu::where('id', $update_id)->update(['parent' => $parent_id, 'order_item' => $key + 1]);
          if (!empty($vchild['children'])) {
            foreach ($vchild['children'] as $key => $vchild1) {
              $update_id = $vchild1['id'];
              $parent_id = $vchild['id'];
              Menu::where('id', $update_id)->update(['parent' => $parent_id, 'order_item' => $key + 1]);
              if (!empty($vchild1['children'])) {
                foreach ($vchild1['children'] as $key => $vchild2) {
                  $update_id = $vchild2['id'];
                  $parent_id = $vchild1['id'];
                  Menu::where('id', $update_id)->update(['parent' => $parent_id, 'order_item' => $key + 1]);
                  if (!empty($vchild2['children'])) {
                    foreach ($vchild2['children'] as $key => $vchild3) {
                      $update_id = $vchild3['id'];
                      $parent_id = $vchild2['id'];
                      Menu::where('id', $update_id)->update(['parent' => $parent_id, 'order_item' => $key + 1]);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
