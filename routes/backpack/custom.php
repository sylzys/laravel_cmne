<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('housing', 'HousingCrudController');
    Route::crud('residence', 'ResidenceCrudController');
    Route::crud('amenity', 'AmenityCrudController');
    Route::get('charts/chart-users', 'Charts\ChartUsersChartController@response')->name('charts.chart-users.index');
    Route::get('charts/chart-c-a', 'Charts\ChartCAChartController@response')->name('charts.chart-c-a.index');
    Route::get('charts/chart-repartition', 'Charts\ChartRepartitionChartController@response')->name('charts.chart-repartition.index');
}); // this should be the absolute last line of this file