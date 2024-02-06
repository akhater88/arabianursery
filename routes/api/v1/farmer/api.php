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

Route::get('/config/app', 'ConfigController@configuration');
Route::post('/login/farmer', 'AuthController@login');
Route::post('/register/farm', 'AuthController@farmRegister');
Route::get('/posts', 'PostsController@posts');
Route::get('/post/{post}', 'PostsController@getPostById');


Route::group(['middleware'=>'auth:farmer_api'], function () {
    Route::put('/farmer/update-fcm-token','AuthController@updateFcmToken' );
    Route::get('/farmer/profile', 'FarmController@getFarmerProfile');
    Route::get('/farmer/notifications', 'FarmController@getFarmerNotifications');
    Route::get('/farmer/all-seedlings', 'FarmController@getSeedling');
});
