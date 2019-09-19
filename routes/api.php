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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['as' => 'api.', 'namespace' => 'Api'], function () {
    /*
     * Outlets Endpoints
     */
    Route::get('outlets', 'OutletController@index')->name('outlets.index');
    Route::get('stops/{id}', 'OutletController@stops')->name('outlets.stops');
    Route::get('stopslatlng/{id}', 'OutletController@stopslatlng')->name('outlets.stopslatlng');
    Route::post('updatelocation', 'OutletController@updateLocation')->name('outlets.updateLocation');
});
