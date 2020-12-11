<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ApiController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas de aplicaciones externas
Route::post('external/service/init', [ApiController::class, 'external_service_init'])->name('api.external.service.init');
Route::get('external/service/map/{id}', [ApiController::class, 'external_service_map'])->name('api.external.service.map');
Route::post('external/service/store', [ApiController::class, 'external_service_store'])->name('api.external.service.store');
