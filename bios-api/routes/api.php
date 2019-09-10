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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('user-dump', 'Api\UserController@getUser');
Route::get('bid-dump', 'Api\BidController@getBid');
Route::get('section-dump', 'Api\SectionController@getSection');
Route::get('dump', 'Api\AdminController@getDump');

Route::get('authenticate', 'Api\AdminController@authenticate'); // post
Route::post('bootstrap', 'Api\AdminController@bootstrap');
Route::post('start', 'Api\AdminController@start');
Route::post('stop', 'Api\AdminController@stop');

Route::get('update-bid', 'Api\BidController@updateBid');
Route::get('delete-bid', 'Api\BidController@deleteBid');

Route::get('drop-section', 'Api\BidController@dropSection');
