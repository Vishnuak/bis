<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'OutletMapController@index');

Route::get('/routing', 'OutletMapController@routing');

Route::post('/', 'OutletMapController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*
 * Outlets Routes
 */
Route::get('/our_outlets', 'OutletMapController@index')->name('outlet_map.index');
Route::resource('outlets', 'OutletController');

/*
 * Stops Routes
 */
Route::resource('stops', 'StopController');

/*
 * Buses Routes
 */
Route::resource('buses', 'BusController');

/*
 * Trip Routes
 */
Route::resource('trips', 'TripController');

Route::get('/about', 'PageController@index');
