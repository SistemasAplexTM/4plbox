<?php

use Illuminate\Http\Request;
use App\Shipper;
use App\Consignee;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('paises', function () {
    return datatables()->eloquent(App\Pais::query())->toJson();
});

Route::namespace('Auth')->group(function () {
  Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function() {
      Route::get('logout', 'AuthController@logout');
      Route::get('user', 'AuthController@user');
    });
  });
});
Route::group(['prefix' => 'user'], function() {
    Route::get('/', function() {
      // authenticated user. Use User::find() to get the user from db by id
     return request()->user();
    })->middleware('auth:api');
});
Route::group(['middleware' => 'auth:api'], function(){
  Route::get('rastreo/getStatusReport/{data}/{idStatus?}', 'RastreoController@getStatusReport');
});

Route::get('/shipperSearch/{doc}/{type?}', function (Shipper $shipper, $doc, $type = null) {
  // return $shipper->where('documento', $doc)->get();
  return DB::table($type)->select('*')->where('documento', $doc)->get();
})->middleware('client');

Route::post('/shipperSave/{type}', function (Request $request, $type) {
  // Shipper::insert($request->all());
  DB::table($type)->insert($request->all());
})->middleware('client');

Route::get('/getConsigneesByShipper/{shipper_id}', function (Shipper $shipper, $shipper_id) {
  return DB::table('consignee AS a')
  ->join('shipper_consignee AS b', 'b.consignee_id', 'a.id')
  ->select('a.*')
  ->where('b.shipper_id', $shipper_id)
  ->get();
})->middleware('client');

Route::get('/getProductsPoint', function () {
  return DB::table('puntos_cuba_productos AS a')
  ->join('maestra_multiple AS b', 'a.unidad_medida_id', 'b.id')
  ->select('a.*', 'b.descripcion')
  ->get();
})->middleware('client');

Route::get('/getConsigneesById/{id}', function (Consignee $consignee, $id) {
  return $consignee->find($id);
})->middleware('client');

Route::get('/ciudad/getSelectCity', 'CiudadController@getSelectCity')->middleware('client');
