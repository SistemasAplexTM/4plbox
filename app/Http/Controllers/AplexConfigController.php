<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AplexConfig;
use App\Agencia;
use DataTables;

class AplexConfigController extends Controller
{
    public function index()
    {
        return view('templates/aplexConfig/index');
    }

    public function config()
    {
      $menu = array(
        array('icon' => 'envelope', 'route' => 'emailTemplate.index', 'url' => false, 'desc' => 'layouts.email_templates', 'perm' => 'emailTemplate.index'),
        array('icon' => 'print', 'route' => 'printConfig', 'url' => false, 'desc' => 'layouts.print_config', 'perm' => 'emailTemplate.index'),
        array('icon' => 'hand-holding-usd', 'route' => 'administracion/1', 'url' => true, 'desc' => 'layouts.payment_methods', 'perm' => 'administracion.index'),
        array('icon' => 'credit-card', 'route' => 'administracion/2', 'url' => true, 'desc' => 'layouts.payment_types', 'perm' => 'emailTemplate.index'),
        array('icon' => 'sitemap', 'route' => 'administracion/3', 'url' => true, 'desc' => 'layouts.groups_of_receipts', 'perm' => 'emailTemplate.index'),
        array('icon' => 'folder-open', 'route' => 'aerolinea_inventario', 'url' => true, 'desc' => 'layouts.inventory_airlines', 'perm' => 'aerolinea_inventario.index'),
        array('icon' => 'plane-alt', 'route' => 'transport/aerolineas', 'url' => true, 'desc' => 'layouts.airlines', 'perm' => 'transport.index'),
        array('icon' => 'road', 'route' => 'transport/aeropuertos', 'url' => true, 'desc' => 'layouts.airports', 'perm' => 'transport.index'),
        array('icon' => 'share-alt', 'route' => 'servicios.index', 'url' => false, 'desc' => 'layouts.services', 'perm' => 'servicios.index'),
        array('icon' => 'reply-all', 'route' => 'administracion/5', 'url' => true, 'desc' => 'layouts.type_boardings', 'perm' => 'administracion.index'),
        array('icon' => 'shopping-bag', 'route' => 'administracion/6', 'url' => true, 'desc' => 'layouts.type_packagings', 'perm' => 'administracion.index')
      );

      $menu2 = array(
        array('icon' => 'file-invoice', 'route' => 'config.document', 'url' => false, 'desc' => 'layouts.documents', 'perm' => 'administracion.index'),
        array('icon' => 'store', 'route' => 'agencia.index', 'url' => false, 'desc' => 'layouts.agencies', 'perm' => 'agencia.index'),
        array('icon' => 'money-bill', 'route' => 'arancel.index', 'url' => false, 'desc' => 'layouts.tariffs', 'perm' => 'arancel.index'),
        array('icon' => 'box-check', 'route' => 'status.index', 'url' => false, 'desc' => 'layouts.status', 'perm' => 'status.index'),
        array('icon' => 'truck', 'route' => 'transportador.index', 'url' => false, 'desc' => 'layouts.transporters', 'perm' => 'transportador.index'),
        array('icon' => 'street-view', 'route' => 'ciudad.index', 'url' => false, 'desc' => 'layouts.cities', 'perm' => 'ciudad.index'),
        array('icon' => 'globe', 'route' => 'departamento.index', 'url' => false, 'desc' => 'layouts.dptos_states', 'perm' => 'departamento.index'),
        array('icon' => 'map-marker', 'route' => 'pais.index', 'url' => false, 'desc' => 'layouts.countrieses', 'perm' => 'pais.index'),
        array('icon' => 'file', 'route' => 'tipoDocumento.index', 'url' => false, 'desc' => 'layouts.document_types', 'perm' => 'tipoDocumento.index'),
        array('icon' => 'history', 'route' => 'logActivity.index', 'url' => false, 'desc' => 'layouts.logs', 'perm' => 'logActivity.index'),
      );
      return view('templates.aplexConfig.config', compact('menu','menu2'));
    }

    public function get($key){
        return AplexConfig::where('key', $key)->first();
    }

    public function save(Request $request, $key, $type, $simple = false){
      $id = $this->get($key);
      $data = array($type => $request->all());
      if ($id) {
        AplexConfig::where('id', $id->id)->update([
          'key' => $key,
          'value' =>  ($simple != 'false') ? $request->type : json_encode($data)
        ]);
      }else{
        AplexConfig::insert([
          'key' => $key,
          'value' =>  ($simple != 'false') ? $request->type : json_encode($data)
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
