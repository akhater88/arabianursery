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
Route::get('/nursery/seedlings', 'SeedlingsController@seedlings');
Route::get('/nursery/seedlings/{seedlingID}', 'SeedlingsController@getSeedlingById');
Route::get('/post/{post}', 'PostsController@getPostById');

Route::get('/page/{code}', 'PagesController@getPageByCode');


Route::group(['middleware'=>'auth:farmer_api'], function () {
    Route::put('/farmer_reserve/trays', 'FarmController@reserveSeedlings' );
    Route::get('/farmer_reserve/seedlings', 'FarmController@getReserveSeedlings' );
    Route::put('/farmer/update-fcm-token','AuthController@updateFcmToken' );
    Route::get('/farmer/profile', 'FarmController@getFarmerProfile');
    Route::get('/farmer/notifications', 'FarmController@getFarmerNotifications');
    Route::get('/farmer/all-seedlings', 'FarmController@getSeedling');
    Route::get('/farmer/seedlings/{seedlingService}', 'FarmController@getSeedlingById');
    Route::put('/farmer/update-profile', 'AuthController@updateProfile' );
    Route::put('/farmer/change-password', 'AuthController@changePassword' );
    Route::delete('/farmer/remove-account','AuthController@removeAccount' );
});
