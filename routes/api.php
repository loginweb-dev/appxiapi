<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ApiController;
use App\Http\Controllers\NotificationsController;

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
Route::get('/test', [ApiController::class, 'test']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [ApiController::class, 'login']);
Route::post('/auth/register', [ApiController::class, 'register']);

// VehicleTypes
Route::get('/vehicle_types/list', [ApiController::class, 'vehicle_types_list']);

// Rutas de aplicaciones externas
Route::post('external/service/init', [ApiController::class, 'external_service_init']);
Route::get('external/service/map/{id}', [ApiController::class, 'external_service_map']);
Route::post('external/service/store', [ApiController::class, 'external_service_store'])->name('api.external.service.store');

Route::group(['middleware' => ['auth:api']], function () {
    // Services
    Route::get('services/{driver_id}/list', [ApiController::class, 'services_list']);
    Route::get('services/{user_id}/notifications/list', [ApiController::class, 'services_notifications_list']);
    Route::post('services/driver/accept', [ApiController::class, 'accept_service']);


    // Vehicles
    Route::post('vehicle/tracking', [ApiController::class, 'vehicle_tracking']);
});
// Route::get('services/{user_id}/notifications/list', [ApiController::class, 'services_notifications_list']);