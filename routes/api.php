<?php

use Illuminate\Http\Request;
use App\Shipper;
use App\Consignee;
use Illuminate\Support\Facades\DB;

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

Route::get('getLogo/{agency_id}', 'CasilleroApiController@getLogo');
Route::group(['prefix' => 'user'], function() {
    Route::get('/', function() {
      // authenticated user. Use User::find() to get the user from db by id
     return request()->user()->id();
    })->middleware('auth:api');
});

Route::group(['middleware' => 'auth:api'], function(){
  Route::get('cantNotification', 'NotificationController@cant');
  Route::get('getNotification', 'NotificationController@get');
  Route::get('viewedAllNotification', 'NotificationController@viewedAll');
  Route::get('rastreo/getStatusReport/{data}/{idStatus?}/{user_id?}', 'RastreoController@getStatusReport');
  Route::get('getConfig/{key}', 'Controller@getConfig');
  Route::get('getAllWarehouse/{user_id?}/{idStatus?}', 'CasilleroApiController@getAllWarehouse');
  Route::get('getWarehouse/{warehouse}/{idStatus?}', 'CasilleroApiController@getWarehouse');
  Route::get('getAllPrealert/{agency_id}/{consignee_id}', 'CasilleroApiController@getAllPrealert');
  Route::get('getCantPrealert/{agency_id}/{consignee_id}', 'CasilleroApiController@getCantPrealert');
  Route::post('setPrealert', 'CasilleroApiController@setPrealert');
  Route::get('user/{id}', 'CasilleroApiController@findUser');
  Route::get('user/contacts/{id}', 'CasilleroApiController@getContacts');
  Route::post('user/setContacts/{id}', 'CasilleroApiController@setContacts');
  Route::put('user/update', 'CasilleroApiController@updateUser');
  Route::get('getSelectCity/{filter}', 'CiudadController@getSelectCity');

  Route::get('getUrlZopim/{agency_id}', 'CasilleroApiController@getUrlZopim');
  Route::get('getPaypal/{agency_id}', 'CasilleroApiController@getPaypal');
});
