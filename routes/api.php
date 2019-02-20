<?php

use Illuminate\Http\Request;

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
