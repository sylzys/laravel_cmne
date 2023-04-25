<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/amenities', '\App\Http\Controllers\Api\AmenityController@index');
Route::post('/api/amenity/{id}', '\App\Http\Controllers\Api\AmenityController@show');

Route::post('/api/residences', '\App\Http\Controllers\Api\ResidenceController@index');
Route::post('/api/residence/{id}', '\App\Http\Controllers\Api\ResidenceController@show');