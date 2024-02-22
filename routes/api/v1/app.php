<?php

use Illuminate\Support\Facades\Route;

// Initailize
Route::get('/', 'MainController@index');


// //Auth
// Route::post('/auth/login', 'AuthController@login');
// Route::post('/auth/logout', 'AuthController@logout');
// Route::post('/auth/refresh-token', 'AuthController@refreshToken');



// route group for api version 1
Route::group(['prefix' => 'v1'], function () {
    //Setting
   
});   

// setting
Route::get('/settings', 'SettingController@index');

// banner  
Route::get('/banners', 'BannerController@index');

// fruit 
Route::get('/fruits', 'FruitController@index');

// meal
Route::get('/meals', 'MealController@index');

// vegetable 
Route::get('/vegetables', 'VegetableController@index');

// beverage
Route::get('/beverages', 'BeverageController@index');
