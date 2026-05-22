<?php

use App\Http\Controllers\DistanceMatrixController;
use App\Http\Controllers\ShortestPathController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverRouteController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/deriveTSP', [ShortestPathController::class, 'deriveTSP']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/driver/today-route', [DriverRouteController::class, 'todayRoute']);
    Route::post('/driver/location', [DriverRouteController::class, 'storeLocation']);

    Route::post('/driver/route/{route}/start', [DriverRouteController::class, 'startRoute']);
    Route::post('/driver/route/{route}/complete', [DriverRouteController::class, 'completeRoute']);

    Route::post('/driver/route/{route}/stop/{index}/status', [DriverRouteController::class, 'updateStopStatus']);
});
