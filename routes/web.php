<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ApiController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('map/{id}', [ApiController::class, 'external_service_map']);

Route::get('/test', function(){
    event(new \App\Events\TestEvent());
});

Route::get('/test1', function(){
    $vehicle = \App\Models\Vehicle::find(1);
    event(new \App\Events\TrackingDriverEvent(1, $vehicle));
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
