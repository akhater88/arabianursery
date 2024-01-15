<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'config'], function () {
    Route::get('/app', 'ConfigController@configuration');
});

Route::group(['prefix' => 'login'], function () {
    Route::post('/farmer', 'AuthController@login');
});
Route::group(['namespace' => 'Api\V1\Farmer', 'middleware'=>'auth:farmer_api'], function () {

});
