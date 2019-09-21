<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AplexConfig;
use App\Agencia;
use DataTables;

class AplexConfigController extends Controller
{
    public function settings()
    {
        return view('templates/aplexConfig/index');
    }

    public function config()
    {
      $menu = array(
        array('icon' => 'store', 'route' => 'agencia.index', 'url' => false, 'desc' => 'layouts.agencies', 'perm' => 'agencia.index'),
        array('icon' => 'cog', 'route' => 'settings', 'url' => false, 'desc' => 'general.general', 'perm' => 'administracion.index'),
        array('icon' => 'box-check', 'route' => 'status.index', 'url' => false, 'desc' => 'layouts.status', 'perm' => 'status.index'),
        array('icon' => 'truck', 'route' => 'transportador.index', 'url' => false, 'desc' => 'layouts.transporters', 'perm' => 'transportador.index'),
        array('icon' => 'file', 'route' => 'tipoDocumento.index', 'url' => false, 'desc' => 'layouts.document_types', 'perm' => 'tipoDocumento.index'),
        array('icon' => 'envelope', 'route' => 'emailTemplate.index', 'url' => false, 'desc' => 'layouts.email_templates', 'perm' => 'emailTemplate.index'),
        array('icon' => 'print', 'route' => 'printConfig', 'url' => false, 'desc' => 'layouts.print_config', 'perm' => 'emailTemplate.index'),
        array('icon' => 'sitemap', 'route' => 'administracion/Grupos', 'url' => true, 'desc' => 'layouts.groups_of_receipts', 'perm' => 'emailTemplate.index'),
        array('icon' => 'folder-open', 'route' => 'aerolinea_inventario', 'url' => true, 'desc' => 'layouts.inventory_airlines', 'perm' => 'aerolinea_inventario.index'),
        array('icon' => 'share-alt', 'route' => 'servicios.index', 'url' => false, 'desc' => 'layouts.services', 'perm' => 'servicios.index'),
        array('icon' => 'dollar-sign', 'route' => 'administracion/Gasto-Costo', 'url' => true, 'desc' => 'layouts.cost', 'perm' => 'administracion.index')
      );

      $menu2 = array(
        array('icon' => 'hand-holding-usd', 'route' => 'administracion/Forma_de_Pago', 'url' => true, 'desc' => 'layouts.payment_methods', 'perm' => 'administracion.index'),
        array('icon' => 'credit-card', 'route' => 'administracion/Tipos_de_Pago', 'url' => true, 'desc' => 'layouts.payment_types', 'perm' => 'emailTemplate.index'),
        array('icon' => 'plane-alt', 'route' => 'transport/aerolineas', 'url' => true, 'desc' => 'layouts.airlines', 'perm' => 'transport.index'),
        array('icon' => 'road', 'route' => 'transport/aeropuertos', 'url' => true, 'desc' => 'layouts.airports', 'perm' => 'transport.index'),
        array('icon' => 'dolly-flatbed-alt', 'route' => 'administracion/Tipo_Embarque', 'url' => true, 'desc' => 'layouts.type_boardings', 'perm' => 'administracion.index'),
        array('icon' => 'shopping-bag', 'route' => 'administracion/Tipo_Empaque', 'url' => true, 'desc' => 'layouts.type_packagings', 'perm' => 'administracion.index'),
        array('icon' => 'money-bill', 'route' => 'arancel.index', 'url' => false, 'desc' => 'layouts.tariffs', 'perm' => 'arancel.index'),
        array('icon' => 'truck-container', 'route' => 'transportadoras_locales', 'url' => true, 'desc' => 'layouts.local_transporters', 'perm' => 'transportador.index'),
        array('icon' => 'map-marker', 'route' => 'pais.index', 'url' => false, 'desc' => 'layouts.countrieses', 'perm' => 'pais.index'),
        array('icon' => 'map-marked', 'route' => 'departamento.index', 'url' => false, 'desc' => 'layouts.dptos_states', 'perm' => 'departamento.index'),
        array('icon' => 'street-view', 'route' => 'ciudad.index', 'url' => false, 'desc' => 'layouts.cities', 'perm' => 'ciudad.index'),
        array('icon' => 'list', 'route' => 'menu.index', 'url' => false, 'desc' => 'layouts.menu', 'perm' => 'tipoDocumento.index'),
      );

      $menu3 = array(
        array('icon' => 'users', 'route' => 'user.index', 'url' => false, 'desc' => 'layouts.users', 'perm' => 'user.index'),
        array('icon' => 'sitemap', 'route' => 'rol.index', 'url' => false, 'desc' => 'layouts.roles', 'perm' => 'rol.index'),
        array('icon' => 'address-book', 'route' => 'accessControl.index', 'url' => false, 'desc' => 'layouts.access_controls', 'perm' => 'rol.index'),
        array('icon' => 'history', 'route' => 'logActivity.index', 'url' => false, 'desc' => 'layouts.logs', 'perm' => 'logActivity.index'),
      );
      return view('templates.aplexConfig.config', compact('menu','menu2', 'menu3'));
    }

    public function get($key){
        return AplexConfig::where('key', $key)->first();
    }

    public function save(Request $request, $key, $type, $simple = false){
      $id = $this->get($key);
      $data = array($type => $request->all());
      // return ($simple != 'false' || $simple == false) ? $request->type : json_encode($data['type']);
      if ($id) {
        AplexConfig::where('id', $id->id)->update([
          'key' => $key,
          'value' => ($simple != 'false' || $simple == false) ? $request->type : json_encode($data['type'])
        ]);
      }else{
        AplexConfig::insert([
          'key' => $key,
          'value' => ($simple != 'false' || $simple == false) ? $request->type : json_encode($data['type'])
        ]);
      }
    }

    public function getDataAgencyById($id){
        return Agencia::select('agencia.*')
            ->where([['agencia.id', '=', $id], ['agencia.deleted_at', '=', null]])
            ->first();
    }

    public function formatNumber()
    {
       $data = $this->getConfig('format_number');
       return array('data' => $data->value);
    }

    public function document()
    {
      return view('templates.aplexConfig.document');
    }
}
