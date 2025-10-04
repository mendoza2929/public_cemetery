<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('cemetery/admin', [
    'as' => 'cemetery.admin',
    'uses' => 'CemeteryController@index',
    'middleware' => 'auth'
]);

Route::get('cemetery/login', 'CemeteryController@login');


Route::get('cemetery/admin', 'CemeteryController@index');
Route::get('cemetery/{id}/map', 'CemeteryController@showMap');
Route::post('cemetery/plot_create', 'CemeteryController@createPlot');
Route::post('cemetery/add_burial', 'CemeteryController@addBurialPlot');


Route::get('cemetery/{encryptedId}', 'CemeteryController@show');

Route::get('plot/{id}/guide', 'CemeteryController@guide');
Route::get('cemetery/{id}/search', 'CemeteryController@searchBurials');  

Route::get('reservation', 'ReservationController@create');
Route::post('reservation', 'ReservationController@postCreate');

Route::get('reservation/track', 'ReservationController@trackForm');
Route::post('reservation/track', 'ReservationController@track');
Route::get('reservation/gcash', 'ReservationController@gcashForm');


