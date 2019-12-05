<?php

use Illuminate\Http\Request;
use App\Shipper;
use App\Consignee;
use Illuminate\Support\Facades\DB;

Route::get('/', 'Auth\LoginController@showLoginForm');
/* RUTA PARA CAMBIAR EL LENGUAJE */
Route::get('lang/{lang}', function ($lang) {
  \Session::put('lang', $lang);
  return \Redirect::back();
})->middleware('web')->name('change_lang');

Auth::routes();

Route::get('/home', 'DocumentoController@index')->name('home');

Route::get('master/buscar/{dato}/{type?}', 'MasterController@getSoC');
Route::group(['middleware' => 'auth'], function () {
  Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

  Route::get('commandBackup', function () {
    Artisan::call('backup:run', ['--only-db' => true]);
    return "successfully!";
  });
  /* REGISTRO DE LOG DE ACTIVIDADES DE USUARIOS */
  Route::get('logActivity', 'LogActivityController@index')->name('logActivity.index');
  Route::get('logActivity/all', 'LogActivityController@getAll')->name('logActivity.getAll');

  /* RUTA PARA ACCEDER A LOS ARCHIVOS GUARDADOS EN EL SISTEMA */
  Route::get('storage/{archivo}', function ($archivo) {
    $public_path = public_path();
    $url         = $public_path . '/storage/' . $archivo;
    //verificamos si el archivo existe y lo retornamos
    if (Storage::exists($archivo)) {
      return response()->download($url);
    }
    //si no se encuentra lanzamos un error 404.
    abort(404);
  });
  /* EXPORT EXCEL CONSOLIDADO */
  Route::get('exportInternalManifest/{id}', 'DocumentoController@internalManifest')->name('internalManifest');
  Route::get('exportLiquimp/{id}', 'DocumentoController@exportLiquimp')->name('exportLiquimp');
  Route::get('exportCellar/{id}', 'DocumentoController@exportCellar')->name('exportCellar');
  /* CONSULTAR SHIPPERS O CONSIGNEE */
  Route::get('consulta', 'ConsultaController@index')->name('consulta.index');
  Route::get('consulta/all', 'ConsultaController@getAll')->name('consulta.getAll');
  Route::get('consulta/pdf', 'ConsultaController@pdf')->name('consulta.pdf');

  /* VISTA RESULTADOS DE LA BUSQUEDA DEL SELECT EN EL NAVBAR */
  Route::get('resultSearch/{id}', 'ResultSearchController@index')->name('resultSearch.index');

  /* VISTA MANTENIMIENTO */
  Route::get('mantenimiento', 'MantenimientoController@index')->name('mantenimiento.index');

  /* MODULO PivotPuntosDetalle */
  Route::post('puntos', 'PivotPuntosDetalleController@store');
  Route::delete('puntos/{id}', 'PivotPuntosDetalleController@destroy');
  Route::get('puntos/{id}', 'PivotPuntosDetalleController@getByIdDetail')->name('getData');
  Route::get('puntos/getProductsClient/{id}', 'PivotPuntosDetalleController@getProductsClient');

  /*--- MODULO USER ---*/
  Route::resource('user', 'UserController', ['except' => ['show', 'create', 'edit']]);
  Route::get('user/delete/{id}/{logical?}', 'UserController@delete')->name('user.delete')->middleware('permission:user.delete');
  Route::get('user/restaurar/{id}', 'UserController@restaurar');
  Route::get('user/all/{data}', 'UserController@getAll')->name('datatable/all');
  Route::get('user/getDataSelect/{table}', 'UserController@getDataSelect');
  Route::post('user/validarUsername', 'UserController@validarUsername');
  Route::get('user/getNameAgenciaUser', 'UserController@getNameAgenciaUser');

  /*--- MODULO ROLES ---*/
  Route::resource('rol', 'RolController', ['except' => ['show', 'create', 'edit']]);
  Route::get('rol/all', 'RolController@getAll')->name('datatable/all');
  Route::get('rol/allPermissions', 'RolController@getAllPermissions')->name('datatable.allPermissions');
  Route::get('rol/delete/{id}/{logical?}', 'RolController@delete')->name('rol.delete');
  Route::get('rol/restaurar/{id}', 'RolController@restaurar');
  Route::get('rol/getPermissions/{id}', 'RolController@getPermissions');

  /*--- MODULO ACCESS CONTROL ---*/
  Route::resource('accessControl', 'AccessControlController', ['except' => ['show', 'create', 'edit']]);
  Route::get('accessControl/all', 'AccessControlController@getAll')->name('datatable/all');
  Route::get('accessControl/allPermissions', 'AccessControlController@getAllPermissions')->name('datatable.allPermissions');
  Route::get('accessControl/delete/{id}/{logical?}', 'AccessControlController@delete')->name('accessControl.delete');
  Route::get('accessControl/getPermisionsRole/{role_id}/{module_id?}', 'AccessControlController@getPermisionsRole');
  Route::get('accessControl/getSpecialPermisions/{module}/{role_id}', 'AccessControlController@getSpecialPermisions');
  Route::post('accessControl/saveSpecialPermissions', 'AccessControlController@saveSpecialPermissions');

  /***********************************************************************************************************************/

  /*--- MASTER ---*/
  Route::resource('master', 'MasterController');
  // Reg al final, por que genera conficto el hecho de que el show se llama con "nombre/variable"
  Route::get('master/create/{master}/{consolidado_id?}', 'MasterController@create');
  Route::get('master/all/reg', 'MasterController@getAll');
  Route::get('master/delete/{id}/{logical?}', 'MasterController@delete')->name('modulo.delete');
  Route::get('master/restaurar/{id}', 'MasterController@restaurar');
  Route::get('master/imprimir/{id_master}/{simple?}', 'MasterController@imprimir');
  Route::get('master/getOtherCharges/{id}', 'MasterController@getOtherCharges');
  Route::get('master/imprimirLabel/{id_master}', 'MasterController@imprimirLabel');
  Route::get('master/imprimirGuias/{consolidado_id}/{option?}', 'MasterController@imprimirGuias');
  Route::post('master/getDataConsolidados/{type}', 'MasterController@getDataConsolidados');
  Route::get('master/hawb/{id}', 'MasterController@createHawb');
  Route::get('master/{id}/getDataPrintBagsConsolidate/{type?}', 'DocumentoController@getDataPrintBagsConsolidate');
  Route::get('master/{id}/impuestosMaster', 'MasterController@impuestosMaster');
  Route::post('master/saveTaxMaster', 'MasterController@saveTaxMaster');
  Route::post('master/saveCostMaster', 'MasterController@saveCostMaster');
  Route::get('master/getCosts/{master_id}', 'MasterController@getCosts');
  Route::delete('master/deleteCost/{id}', 'MasterController@deleteCost');
  Route::get('master/generateXml/{id}', 'MasterController@generateXml');

  /*--- MODULO TRACKINGS ---*/
  Route::resource('tracking', 'TrackingController', ['except' => ['show', 'create', 'edit', 'update']]);
  Route::post('tracking/addOrDeleteDocument', 'TrackingController@addOrDeleteDocument');
  Route::get('tracking/all/{grid?}/{add?}/{id?}/{req_consignee?}/{bodega?}', 'TrackingController@getAll')->name('datatable/all');
  Route::get('tracking/getTrackingByCreateReceipt', 'TrackingController@getTrackingByCreateReceipt');
  Route::get('tracking/getTrackingByIdConsignee/{consignee_id}', 'TrackingController@getTrackingByIdConsignee');
  Route::get('tracking/delete/{id}/{logical?}', 'TrackingController@delete')->name('tracking.delete');
  Route::get('tracking/getAllShipperConsignee/{table}', 'TrackingController@getAllShipperConsignee');
  Route::get('tracking/searchTracking/{tracking}', 'TrackingController@searchTracking');
  Route::post('tracking/updateTrackingReceipt', 'TrackingController@updateTrackingReceipt');
  Route::post('tracking/validar_tracking', 'TrackingController@validar_tracking');
  Route::get('tracking/reenviarEmail/trackingRecibido/{id}/{track}', 'TrackingController@trackingRecibido');

  /*--- MODULO RECIBO DE ENTREGA ---*/
  Route::resource('receipt', 'ReceiptController', ['except' => ['show', 'create', 'edit', 'update']]);
  Route::get('receipt/all', 'ReceiptController@getAll')->name('datatable/all');
  Route::get('receipt/delete/{id}/{logical?}', 'ReceiptController@delete')->name('receipt.delete');
  Route::get('receipt/getConsignee/{data?}', 'ReceiptController@getConsignee');
  Route::get('receipt/searchDocument/{docunent}', 'ReceiptController@searchDocument');
  Route::get('receipt/searchReceiptDetail/{id_receipt}', 'ReceiptController@searchReceiptDetail');
  Route::post('receipt/saveDetail', 'ReceiptController@storeDeail');
  Route::get('receipt/getDocument/{id}', 'ReceiptController@getDocument');
  Route::post('receipt/checkReceipt', 'ReceiptController@checkReceipt');
  Route::get('receipt/printReceipt/{id}', 'ReceiptController@printReceipt');
  Route::get('receipt/changeStatus/{id}', 'ReceiptController@changeStatus');
  // Route::post('receipt/validar_tracking', 'ReceiptController@validar_tracking');

  /*--- MODULO MODULOS ---*/
  Route::resource('modulo', 'ModuloController', ['except' => ['show', 'create', 'edit']]);
  Route::get('modulo/all', 'ModuloController@getAll')->name('datatable/all');
  Route::get('getForSelect/modulo/{type?}', 'ModuloController@getForSelect');
  Route::get('modulo/delete/{id}/{logical?}', 'ModuloController@delete')->name('modulo.delete');
  Route::get('modulo/restaurar/{id}', 'ModuloController@restaurar');

  /*--- MODULO MONEDA ---*/
  Route::resource('moneda', 'MonedaController', ['except' => ['show', 'create', 'edit']]);
  Route::get('moneda/all', 'MonedaController@getAll')->name('datatable/all');
  Route::get('moneda/delete/{id}/{logical?}', 'MonedaController@delete')->name('moneda.delete');
  Route::get('moneda/restaurar/{id}', 'MonedaController@restaurar');

  /*---- Rutas para la tabla MaestraMultiple ----*/
  Route::get('administracion/{type}/all', 'MaestraMultipleController@getAll');
  Route::get('administracion/{type}', 'MaestraMultipleController@index')->name('administracion.index');
  Route::get('administracion/{type}/restaurar/{id}', 'MaestraMultipleController@restaurar');
  Route::get('administracion/{type}/delete/{id}/{logical?}', 'MaestraMultipleController@delete')->name('administracion.delete');
  Route::post('administracion/{type}', 'MaestraMultipleController@store')->name('administracion.store');
  Route::put('administracion/{type}/{id}', 'MaestraMultipleController@update')->name('administracion.update');
  Route::delete('administracion/{type}/{id}', 'MaestraMultipleController@destroy')->name('administracion.destroy');
  Route::get('administracion/{type}/selectInput/{tableName?}', 'MaestraMultipleController@selectInput');
  Route::get('administracion/{type}/getSelect', 'MaestraMultipleController@getSelect');

  /*--- MODULO PAIS ---*/
  Route::resource('pais', 'PaisController', ['except' => ['show', 'create', 'edit']]);
  Route::get('pais/all', 'PaisController@getAll')->name('datatable/all');
  Route::get('pais/delete/{id}/{logical?}', 'PaisController@delete')->name('pais.delete');
  Route::get('pais/restaurar/{id}', 'PaisController@restaurar');

  /*--- MODULO DEPTO - ESTADO ---*/
  Route::resource('departamento', 'DepartamentoController', ['except' => ['show', 'create', 'edit']]);
  Route::get('departamento/all', 'DepartamentoController@getAll')->name('datatable/all');
  Route::get('departamento/delete/{id}/{logical?}', 'DepartamentoController@delete')->name('departamento.delete');
  Route::get('departamento/restaurar/{id}', 'DepartamentoController@restaurar');
  Route::get('departamento/selectInput/{tableName}', 'DepartamentoController@selectInput');

  /*--- MODULO CIUDAD ---*/
  Route::resource('ciudad', 'CiudadController', ['except' => ['show', 'create', 'edit']]);
  Route::get('ciudad/all', 'CiudadController@getAll')->name('datatable/all');
  Route::get('ciudad/delete/{id}/{logical?}', 'CiudadController@delete')->name('ciudad.delete');
  Route::get('ciudad/restaurar/{id}', 'CiudadController@restaurar');
  Route::get('ciudad/selectInput/{tableName}/{idCondition?}', 'CiudadController@selectInput');

  /*--- MODULO AEROLINES - AEROPUERTOS ---*/
  /*    Route::resource('transport/{type}', 'AerolineasAeropuertosController', ['except' => ['show', 'create', 'edit', 'update']]);*/
  Route::delete('transport/{id}', 'AerolineasAeropuertosController@destroy')->name('transport.destroy');
  Route::post('transport/{type}', 'AerolineasAeropuertosController@store')->name('transport.store');
  Route::put('transport/{type}/{id}', 'AerolineasAeropuertosController@update');
  Route::get('transport/{type}', 'AerolineasAeropuertosController@index')->name('transport.index');
  Route::put('transport/{type}/{id}', 'AerolineasAeropuertosController@update')->name('transport.update');
  Route::get('transport/{type}/all', 'AerolineasAeropuertosController@getAll')->name('datatable/all');
  Route::get('transport/delete/{id}/{logical?}', 'AerolineasAeropuertosController@delete')->name('transport.delete');
  Route::get('transport/{type}/restaurar/{id}', 'AerolineasAeropuertosController@restaurar');
  Route::get('transport/selectInput/{tableName}', 'AerolineasAeropuertosController@selectInput');

  /*--- MODULO SERVICIOS ---*/
  Route::resource('servicios', 'ServiciosController', ['except' => ['show', 'create', 'edit']]);
  Route::get('servicios/all/{id_embarque?}', 'ServiciosController@getAll')->name('datatable/all');
  Route::get('servicios/getAllServiciosAgencia/{id_embarque?}', 'ServiciosController@getAllServiciosAgencia');
  Route::get('servicios/delete/{id}/{logical?}', 'ServiciosController@delete')->name('servicios.delete');
  Route::get('servicios/restaurar/{id}', 'ServiciosController@restaurar');

  /*--- MODULO TRANSPORTADOR ---*/
  Route::resource('transportador', 'TransportadorController', ['except' => ['show', 'create', 'edit']]);
  Route::get('transportador/all', 'TransportadorController@getAll')->name('datatable/all');
  Route::get('transportador/delete/{id}/{logical?}', 'TransportadorController@delete')->name('transportador.delete');
  Route::get('transportador/restaurar/{id}', 'TransportadorController@restaurar');
  Route::get('transportador/getLogo/{id}', 'TransportadorController@getLogo');
  Route::post('transportador/uploadImage', 'TransportadorController@uploadImage');

  /*--- MODULO STATUS ---*/
  Route::resource('status', 'StatusController', ['except' => ['show', 'create', 'edit']]);
  Route::get('status/all', 'StatusController@getAll')->name('datatable/all');
  Route::get('status/delete/{id}/{logical?}', 'StatusController@delete')->name('status.delete');
  Route::get('status/restaurar/{id}', 'StatusController@restaurar');
  Route::get('status/getDataSelect', 'StatusController@getDataSelect');
  Route::get('status/getDataSelectModalTagGuia', 'StatusController@getDataSelectModalTagGuia');
  Route::get('status/getDataSelectTransportadoras/{id}', 'StatusController@getDataSelectTransportadoras');

  /*--- MODULO STATUS-REPORT ---*/
  Route::resource('statusReport', 'StatusReportController', ['except' => ['show', 'create', 'edit']]);
  Route::get('statusReport/all', 'StatusReportController@getAll')->name('datatable/all');
  Route::get('statusReport/getAllGrid/{id_documento}', 'StatusReportController@getAllGrid');
  Route::get('statusReport/delete/{id}/{logical?}', 'StatusReportController@delete')->name('statusReport.delete');
  Route::get('statusReport/restaurar/{id}', 'StatusReportController@restaurar');
  Route::get('statusReport/getStatusByIdDetalle/{id}', 'StatusReportController@getStatusByIdDetalle');

  /*--- MODULO ARANCEL ---*/
  Route::resource('arancel', 'ArancelController', ['except' => ['show', 'create', 'edit']]);
  Route::get('arancel/all', 'ArancelController@getAll')->name('datatable/all');
  Route::get('arancel/delete/{id}/{logical?}', 'ArancelController@delete')->name('arancel.delete');
  Route::get('arancel/restaurar/{id}', 'ArancelController@restaurar');
  Route::get('arancel/getPositionById/{id}', 'ArancelController@getPositionById');

  /*--- MODULO AGENCIA ---*/
  Route::resource('agencia', 'AgenciaController', ['except' => ['show', 'update']]);
  Route::put('agencia/{id}', 'AgenciaController@update')->name('agencia.update')->middleware('permission:agencia.update');
  Route::put('agencia/updateDetail/{id_detail}', 'AgenciaController@updateDetail')->name('agencia.update_detail');
  Route::get('agencia/all', 'AgenciaController@getAll')->name('datatable/all');
  Route::get('agencia/{id_agencia}/allUrls', 'AgenciaController@getAllUrls');
  Route::get('agencia/delete/{id}/{logical?}/{table?}', 'AgenciaController@delete')->name('agencia.delete')->middleware('permission:agencia.delete');
  Route::get('agencia/restaurar/{id}', 'AgenciaController@restaurar');
  Route::get('agencia/selectInput/{tableName}', 'AgenciaController@selectInput');
  Route::get('agencia/getSelectBranch', 'AgenciaController@getSelectBranch');
  Route::get('agencia/getAgencies', 'AgenciaController@getAgencies');
  Route::post('agencia/saveURL/{id}', 'AgenciaController@saveURL');
  Route::get('agencia/getURL/{id}', 'AgenciaController@getURL');

  /*--- MODULO REMITENTES ---*/
  Route::resource('shipper', 'ShipperController', ['except' => ['show', 'create', 'edit']]);
  Route::get('shipper/all/{data?}/{id_consignee?}/{id_agencia?}', 'ShipperController@getAll')->name('datatable/all');
  Route::get('shipper/delete/{id}/{logical?}', 'ShipperController@delete')->name('shipper.delete');
  Route::get('shipper/restaurar/{id}', 'ShipperController@restaurar');
  Route::get('shipper/selectInput/{tableName}', 'ShipperController@selectInput');
  Route::get('shipper/getDataById/{id}', 'ShipperController@getDataById');
  Route::get('shipper/getContacts/{id}', 'ShipperController@getContacts');

  /*--- MODULO DESTINATARIOS ---*/
  Route::resource('consignee', 'ConsigneeController', ['except' => ['show', 'create', 'edit']]);
  Route::get('consignee/all/{data?}/{id_shipper?}/{id_agencia?}', 'ConsigneeController@getAll')->name('datatable/all');
  Route::get('consignee/delete/{id}/{logical?}', 'ConsigneeController@delete')->name('arancel.delete');
  Route::get('consignee/restaurar/{id}', 'ConsigneeController@restaurar');
  Route::get('consignee/selectInput/{tableName}', 'ConsigneeController@selectInput');
  Route::get('consignee/getDataById/{id}', 'ConsigneeController@getDataById');
  Route::get('consignee/generarCasillero/{id}', 'ConsigneeController@generarCasillero');
  Route::get('consignee/reenviarEmailCasillero/{id}', 'ConsigneeController@reenviarEmailCasillero');
  Route::get('consignee/getSelect', 'ConsigneeController@getSelect');
  Route::get('consignee/getContacts/{id}', 'ConsigneeController@getContacts');
  Route::post('consignee/assignContact/{id}/{table}', 'ConsigneeController@assignContact');
  Route::get('consignee/getExisting/{data?}/{consignee_id?}/{table?}', 'ConsigneeController@getExisting');

  /*--- MODULO EMAIL TEMPLATES ---*/
  Route::resource('emailTemplate', 'EmailTemplateController', ['except' => ['show', 'create', 'edit']]);
  Route::get('emailTemplate/all', 'EmailTemplateController@getAll')->name('datatable/all');
  Route::get('emailTemplate/delete/{id}/{logical?}', 'EmailTemplateController@delete')->name('emailTemplate.delete');
  Route::get('emailTemplate/restaurar/{id}', 'EmailTemplateController@restaurar');
  Route::get('emailTemplate/getContent/{id}', 'EmailTemplateController@getContent');

  /*--- MODULO PRINT CONFIG ---*/
  Route::get('printConfig', 'PrintConfigController@index')->name('printConfig');
  Route::get('printConfig/getPrintersSaved', 'PrintConfigController@getPrintersSaved');
  Route::get('printConfig/deletePrinter/{id}', 'PrintConfigController@deletePrinter');
  Route::post('printConfig', 'PrintConfigController@save');

  /*--- MODULO TIPO DOCUMENTOS ---*/
  Route::resource('tipoDocumento', 'TipoDocumentoController', ['except' => ['show', 'create', 'edit']]);
  Route::get('tipoDocumento/all/{list?}', 'TipoDocumentoController@getAll')->name('datatable/all');
  Route::get('tipoDocumento/delete/{id}/{logical?}', 'TipoDocumentoController@delete')->name('tipoDocumento.delete');
  Route::get('tipoDocumento/restaurar/{id}', 'TipoDocumentoController@restaurar');
  Route::get('tipoDocumento/selectInput/{tableName}', 'TipoDocumentoController@selectInput');
  Route::get('tipoDocumento/getPlantillasEmail', 'TipoDocumentoController@getPlantillasEmail');

  /*--- MODULO DOCUMENTO ---*/
  Route::resource('documento', 'DocumentoController', ['except' => ['create', 'show']]);

  Route::post('documento/insertDetail', 'DocumentoController@insertDetail')->name('documento.insertDetail');
  Route::post('documento/editDetail', 'DocumentoController@editDetail')->name('documento.editDetail');
  Route::post('documento/updatedDocument/{id}', 'DocumentoController@update')->name('documento.update');
  Route::post('documento/ajaxCreate/{document}', 'DocumentoController@ajaxCreate')->name('documento.ajaxCreate');
  Route::post('documento/{id}/additionalChargues', 'DocumentoController@additionalChargues')->name('documento.additionalChargues');
  Route::post('documento/ajaxCreateNota/{id}', 'DocumentoController@ajaxCreateNota')->name('documento.ajaxCreateNota');
  Route::post('documento/{id}/createContactsConsolidadoDetalle', 'DocumentoController@createContactsConsolidadoDetalle');
  Route::post('documento/{id}/addStatusToGuias', 'DocumentoController@addStatusToGuias')->name('documento.addStatusToGuias');
  Route::post('documento/{id}/getStatusDocument', 'DocumentoController@getStatusDocument')->name('documento.getStatusDocument');
  Route::post('documento/{id}/agruparGuiasConsolidadoCreate', 'DocumentoController@agruparGuiasConsolidadoCreate');
  Route::get('documento/{id}/removerGuiaAgrupada/{id_detalle}/{id_guia_detalle}/{document?}', 'DocumentoController@removerGuiaAgrupada')->name('documento.removerGuiaAgrupada');
  Route::get('documento/sendEmailDocument/{id}', 'DocumentoController@sendEmailDocument');
  Route::get('documento/{id}/deleteDetailConsolidado/{id_detail}/{logical}', 'DocumentoController@deleteDetailConsolidado')->name('documento.deleteDetailConsolidado');
  Route::get('documento/{id}/liquidar', 'DocumentoController@liquidar');
  Route::get('documento/{id}/additionalChargues/getAll/{documento_id}', 'DocumentoController@additionalCharguesGetAll')->name('documento.charguesGetAll');
  Route::get('documento/{id}/additionalChargues/delete/{chargue_id}', 'DocumentoController@additionalCharguesDelete')->name('documento.additionalCharguesDelete');


  Route::put('documento/updateDetail/{id_detail}', 'DocumentoController@updateDetail')->name('documento.updateDetail');
  Route::post('documento/{id}/updateDetailConsolidado/', 'DocumentoController@updateDetailConsolidado')->name('datatable.updateDetailConsolidado');
  Route::post('documento/{id}/updateDetailDocument/', 'DocumentoController@updateDetailDocument')->name('datatable.updateDetailDocument');
  Route::get('documento/all/{tableName}', 'DocumentoController@getAll')->name('datatable.all');
  Route::get('documento/delete/{id}/{logical?}/{table?}', 'DocumentoController@delete')->name('documento.delete');
  Route::get('documento/restaurar/{id}/{table?}', 'DocumentoController@restaurar');
  Route::get('documento/selectInput/{tableName}', 'DocumentoController@selectInput');
  Route::get('documento/create/{document}', 'DocumentoController@create')->name('documento.create');
  Route::get('documento/{id}/buscarGuias/{num_guia}/{num_bolsa}/{pais_id}/{range_value?}', 'DocumentoController@buscarGuias');
  Route::get('documento/{id}/getAllGuiasDisponibles/{pais_id?}/{transporte_id?}', 'DocumentoController@getAllGuiasDisponibles');
  Route::get('documento/{id}/getAllConsolidadoDetalle/{num_bolsa?}', 'DocumentoController@getAllConsolidadoDetalle');
  Route::get('documento/{id}/restoreShipperConsignee/{id_detalle}/{table}', 'DocumentoController@restoreShipperConsignee');
  Route::get('documento/getDataSelectWarehousesModalTagGuia/{id}', 'DocumentoController@getDataSelectWarehousesModalTagGuia');
  Route::get('documento/getAllGridNotas/{id_documento}', 'DocumentoController@getAllGridNotas');
  Route::get('notas/delete/{id}/{logical?}', 'DocumentoController@deleteNota')->name('documento.deleteNota');
  Route::get('documento/getHistoryConsignee/{id}', 'DocumentoController@getHistoryConsignee');
  Route::get('documento/getHistoryDocument/{document}', 'DocumentoController@getHistoryDocument');
  Route::get('documento/{id}/getGuiasAgrupar/{id_detalle}/{document?}', 'DocumentoController@getGuiasAgrupar');
  Route::put('documento/{id}/updatePositionArancel', 'DocumentoController@updatePositionArancel');
  Route::get('documento/{id}/getDataDetailDocument', 'DocumentoController@getDataDetailDocument');
  Route::get('documento/{id}/getBoxesConsolidado', 'DocumentoController@getBoxesConsolidado');
  Route::get('documento/{id}/removeBoxConsolidado/{num_bolsa}', 'DocumentoController@removeBoxConsolidado');
  Route::get('documento/{id}/changeBoxConsolidado/{num_bolsa}/{consol_id}', 'DocumentoController@changeBoxConsolidado');
  Route::get('documento/{id}/closeDocument', 'DocumentoController@closeDocument');
  Route::get('documento/getDataByDocument/{id}', 'DocumentoController@getDataByDocument');
  Route::get('documento/{id}/getDataPrintBagsConsolidate', 'DocumentoController@getDataPrintBagsConsolidate');
  Route::get('documento/getDataDocument/{data}', 'DocumentoController@getDataPrintBagsConsolidate');
  Route::get('documento/getDataSearchDocument/{data?}/{type?}', 'DocumentoController@getDataSearchDocument');
  Route::get('documento/updateShipperConsignee/{id}/{data_id}/{op}', 'DocumentoController@updateShipperConsignee');
  Route::post('documento/uploadFileStatus', 'DocumentoController@uploadFileStatus');
  Route::get('documento/validateUploadDocs', 'DocumentoController@validateUploadDocs');
  Route::get('documento/insertStatusUploadDocument', 'DocumentoController@insertStatusUploadDocument');
  Route::get('documento/getDataShipperConsignee/{table}/{data}', 'DocumentoController@getDataShipperConsignee');
  Route::get('documento/getDataShipperConsigneeById/{table}/{id}', 'DocumentoController@getDataShipperConsigneeById');

  /*  REPORTES - IMPRESIONES EN PDF */
  Route::get('impresion-documento/{id}/{document}/{id_detalle?}', 'DocumentoController@pdf')->name('documento.pdf');
  Route::get('impresion-documento-label/{id}/{document}/{id_detalle?}/{consolidado?}/{id_detail_consol?}', 'DocumentoController@pdfLabel')->name('documento.pdfLabel');
  Route::get('impresion-documento/pdfContrato', 'DocumentoController@pdfContrato')->name('documento.pdfContrato');
  Route::get('impresion-documento-tsa/pdfTsa/{carrier_id}', 'DocumentoController@pdfTsa')->name('documento.pdfTsa');
  Route::get('impresion-group/pdfConsolidadoGroup/{id}/{document}/{num_bolsa}', 'DocumentoController@pdfConsolidadoGroup')->name('documento.pdfConsolidadoGroup');

  /* PREALERTA */
  Route::get('prealerta', 'PrealertaController@prealertaList')->name('prealerta.list')->middleware('permission:prealerta.list');

  /*--- AEROLINEA INVENTARIO ---*/
  Route::get('aerolinea_inventario/delete/{id}', 'AerolineasInventarioController@delete')->name('aerolinea_inventario.delete');
  Route::put('aerolinea_inventario/{id}', 'AerolineasInventarioController@update')->name('aerolinea_inventario.update');
  Route::post('aerolinea_inventario', 'AerolineasInventarioController@store')->name('aerolinea_inventario.store');
  Route::get('aerolinea_inventario/get/{aerolinea}', 'AerolineasInventarioController@getByAerolinea');
  Route::get('aerolinea_inventario/all/{used?}', 'AerolineasInventarioController@getAll');
  Route::get('aerolinea_inventario', 'AerolineasInventarioController@index')->name('aerolinea_inventario.index');

  /*--- MODULO CLIENTES ---*/
  Route::resource('clientes', 'ClienteController', ['except' => ['show', 'create', 'edit']]);
  Route::get('clientes/all', 'ClienteController@getAll')->name('datatable/all');
  Route::get('clientes/delete/{id}/{logical?}', 'ClienteController@delete');
  Route::get('clientes/restaurar/{id}', 'ClienteController@restaurar');
  Route::get('clientes/selectInput/{tableName}', 'ClienteController@selectInput');
  Route::get('clientes/getDataById/{id}', 'ClienteController@getDataById');

  /*--- MODULO TRANSPORTADORAS LOCALES ---*/
  Route::resource('transportadoras_locales', 'LocalTransportersController', ['except' => ['show', 'create', 'edit']]);
  Route::get('transportadoras_locales/all', 'LocalTransportersController@getAll')->name('datatable/all');
  Route::get('transportadoras_locales/getPaises', 'LocalTransportersController@getAllPais');
  Route::get('transportadoras_locales/delete/{id}/{logical?}', 'LocalTransportersController@delete');
  Route::get('transportadoras_locales/restaurar/{id}', 'LocalTransportersController@restaurar');

  /*--- MODULO BL ---*/
  Route::resource('bill', 'BillLadingController', ['except' => ['show', 'create']]);
  Route::get('bill/create/{bill?}/{consolidado_id?}', 'BillLadingController@create');
  Route::get('bill/all', 'BillLadingController@getAll')->name('datatable/all');
  Route::get('bill/delete/{id}/{logical?}', 'BillLadingController@delete')->name('BillLading.delete');
  Route::get('bill/imprimir/{id_bill}/{simple?}', 'BillLadingController@imprimir');
  Route::get('bill/restaurar/{id}', 'BillLadingController@restaurar');
  Route::get('bill/parties/getParties', 'BillLadingController@getParties');
  Route::post('bill/createPartie', 'BillLadingController@createPartie');
  Route::put('bill/editPartie/{id}', 'BillLadingController@editPartie');
  Route::delete('bill/destroyPartie/{id}', 'BillLadingController@destroyPartie');

  /* MODULO APLEXCONFIG */
  // Route::get('setup', 'SetupController@index');
  Route::get('settings', 'AplexConfigController@settings')->name('settings');
  Route::get('configs', 'AplexConfigController@config')->name('config.index');
  Route::get('aplexConfig/document', 'AplexConfigController@document')->name('config.document');
  Route::get('menu', 'MenuController@index')->name('menu.index');
  Route::post('menu', 'MenuController@store');
  Route::get('getMenu/{front}/{type}', 'MenuController@getMenu');
  Route::get('menu/id/{id}', 'MenuController@getById');
  Route::put('menu/updateOrder', 'MenuController@updateOrder');
  Route::put('menu/{id}', 'MenuController@update');

  Route::get('getConfig/{key}', 'Controller@getConfig');
  Route::post('config/{key}/{type}/{simple?}', 'AplexConfigController@save');

  /* MODULO INFORMES */
  Route::get('report', 'ReportController@index')->name('report.index');

  Route::post('reportDispatch/{id}', 'ReportController@updateReportDispatch');
  Route::get('reportDispatch/{id}', 'ReportController@getReportDispatchById');
  Route::get('reportDispatch/{id}/print', 'ReportController@reportDispatchPrint');
  Route::get('reportDispatch', 'ReportController@getReportDispatch');
  Route::post('reportDispatch', 'ReportController@reportDispatch');

  /* MODULO MINTIC */
  Route::get('mintic', 'MinticController@index');
  Route::get('mintic/all', 'MinticController@all');
  Route::get('mintic/searchDocument/{document}', 'MinticController@searchDocument');
  Route::post('mintic/createDetail', 'MinticController@createDetail');

  /* MODULO INVOICE */
  Route::get('invoice', 'InvoiceController@index')->name('invoce.index');
  Route::get('invoice/getAll', 'InvoiceController@getAll');
  Route::get('invoice/pdfLabels/{invoice_id?}', 'InvoiceController@pdfLabels');
  /* CAMBIAR STATUS CONSOLIDADO */
  Route::post('cambiarStatusConsolidado/{document_id}', 'StatusController@cambiarStatusConsolidado');


  /*--- MODULO EMPLEADOS ---*/
  Route::resource('empleado', 'EmpleadoController', ['except' => ['show', 'create', 'edit']]);
  Route::get('empleado/all', 'EmpleadoController@getAll')->name('datatable/all');
  Route::get('empleado/delete/{id}/{logical?}', 'EmpleadoController@delete')->name('empleado.delete');
  Route::get('empleado/restaurar/{id}', 'EmpleadoController@restaurar');

  /*--- MODULO RADICADO CLIENTES ---*/
  Route::resource('radicado_clientes', 'RadicadoClienteController', ['except' => ['show', 'create', 'edit']]);
  Route::get('radicado_clientes/all', 'RadicadoClienteController@getAll')->name('datatable/all');
  Route::get('radicado_clientes/delete/{id}/{logical?}', 'RadicadoClienteController@delete')->name('radicado_clientes.delete');
  Route::get('radicado_clientes/restaurar/{id}', 'RadicadoClienteController@restaurar');

  /*--- MODULO RADICADO ---*/
  Route::resource('radicado', 'RadicadoController', ['except' => ['show', 'create', 'edit']]);
  Route::get('radicado/all', 'RadicadoController@getAll')->name('datatable/all');
  Route::get('radicado/delete/{id}/{logical?}', 'RadicadoController@delete')->name('radicado.delete');
  Route::get('radicado/restaurar/{id}', 'RadicadoController@restaurar');
  Route::get('radicado/getClientes', 'RadicadoController@getClientes');
  Route::get('radicado/getEmpleados', 'RadicadoController@getEmpleados');
  Route::get('radicado/imprimir/{id}', 'RadicadoController@imprimir');
});
Route::get('DocumentoController', 'DocumentoController@printFile');
Route::any('WebClientPrintController', 'WebClientPrintController@processRequest');

Route::get('aplexConfig/config/{key}', 'AplexConfigController@get')->name('config.config');
Route::get('aplexConfig/getDataAgencyById/{id}', 'AplexConfigController@getDataAgencyById')->name('aplexConfig.getDataAgencyById');

Route::get('consignee/vueSelect/{term}', 'ConsigneeController@vueSelect');
Route::get('shipper/vueSelect/{term}', 'ShipperController@vueSelect');

Route::get('documento/vueSelectGeneral/{table}/{term}', 'DocumentoController@vueSelectGeneral');
Route::get('documento/vueSelect/{term}', 'DocumentoController@vueSelect');
Route::get('documento/vueSelectSucursales/{term}', 'DocumentoController@vueSelectSucursales');
Route::get('documento/vueSelectTransportadorMaster/{term}', 'DocumentoController@vueSelectTransportadorMaster');
Route::get('documento/vueSelectServicios/{term}', 'DocumentoController@vueSelectServicios');
Route::get('documento/searchDataByNavbar/{data}/{element}', 'DocumentoController@searchDataByNavbar');
Route::get('master/vueSelectConsolidados/{term}', 'MasterController@vueSelectConsolidados');
Route::get('consignee/vueSelectClientes/{term}', 'ConsigneeController@vueSelectClientes');

/* VALIDAR EMAIL DE CLIENTE */
Route::post('clientes/existEmail', 'ClienteController@existEmail');

/* VALIDAR EMAIL DE CONSIGNEE */
Route::post('consignee/existEmail', 'ConsigneeController@existEmail');

/* VALIDAR EMAIL DE SHIPPER */
Route::post('shipper/existEmail', 'ShipperController@existEmail');

/* VALIDAR USERNAME */
Route::get('validarUsername/{element}', 'UserController@validarUsername');

/*--- REGISTRO CASILLERO ---*/
Route::post('registro/validar/validar_email', 'CasilleroController@validar_email');
Route::get('registro/vueSelectCiudad/{term}', 'CasilleroController@buscar_ciudad');
Route::post('registro', 'CasilleroController@store');
Route::get('registro/{id}', 'CasilleroController@index');

Route::get('obtener_contactos/{id}/{table}', 'ShipperController@getContactos');
Route::post('agregar_contactos/{id}/{table}', 'ShipperController@storeContacto');

/* PREALERTA */
Route::get('prealerta/{id_agencia}', 'PrealertaController@index')->name('prealerta.index');
Route::get('prealerta/{id_agencia}/all', 'PrealertaController@getAll')->name('prealerta.all');
Route::post('prealerta/{id_agencia}', 'PrealertaController@store');
Route::post('prealerta/{id_agencia}/existEmailPost', 'PrealertaController@existEmailPost');
Route::post('prealerta/{id_agencia}/validar_tracking', 'PrealertaController@validar_tracking');

/* RASTREO */
Route::get('rastreo', 'RastreoController@index');
Route::get('rastreo/getStatusReport/{data}/{idStatus?}', 'RastreoController@getStatusReport');


Route::get('formatNumber', 'AplexConfigController@formatNumber');

Route::get('ciudad/getSelectCity/{filter}', 'CiudadController@getSelectCity');

Route::get('selfService/{id?}', 'PuntosController@index');



Route::get('/shipperSearch/{doc}/{type?}', function (Shipper $shipper, $doc, $type = null) {
  return DB::table($type)->select('*')->where('documento', $doc)->get();
});

Route::post('/shipperSave/{type}/{shipper_id?}', 'PuntosController@saveShipperConsignee');

Route::get('/getConsigneesByShipper/{shipper_id}', function (Shipper $shipper, $shipper_id) {
  return DB::table('consignee AS a')
    ->join('shipper_consignee AS b', 'b.consignee_id', 'a.id')
    ->select('a.*')
    ->where('b.shipper_id', $shipper_id)
    ->get();
});

Route::get('/getProductsPoint', function () {
  return DB::table('puntos_cuba_productos AS a')
    ->join('maestra_multiple AS b', 'a.unidad_medida_id', 'b.id')
    ->select('a.*', 'b.descripcion')
    ->get();
});

Route::get('/getConsigneesById/{id}', function (Consignee $consignee, $id) {
  return $consignee->find($id);
});
Route::post('documento/ajaxCreatePublic/{document}', 'DocumentoController@ajaxCreate');
Route::post('saveProductDetail', 'PuntosController@saveProductDetail');
